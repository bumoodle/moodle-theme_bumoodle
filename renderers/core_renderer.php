<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle@BU Customizations to the core renderer.
 *
 * @package    theme_bumoodle
 * @copyright  2012 Binghamton University
 * @author     Kyle J. Temkin
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once($CFG->dirroot.'/question/engine/renderer.php');

/**
 * Core renderer, modified for Moodle@BU
 * 
 * @uses core_renderer
 * @package theme_bumoodle
 * @copyright 2011, 2012 Binghamton University
 * @author Kyle Temkin <ktemkin@binghamton.edu> 
 * @license GNU Public License, {@link http://www.gnu.org/copyleft/gpl.html}
 */
class theme_bumoodle_core_renderer extends core_renderer {

    /**
     * Do not call this function directly.
     *
     * To terminate the current script with a fatal error, call the {@link print_error}
     * function, or throw an exception. Doing either of those things will then call this
     * function to display the error, before terminating the execution.
     *
     * @param string $message The message to output
     * @param string $moreinfourl URL where more info can be found about the error
     * @param string $link Link for the Continue button
     * @param array $backtrace The execution backtrace
     * @param string $debuginfo Debugging information
     * @return string the HTML to output.
     */
    public function fatal_error($message, $moreinfourl, $link, $backtrace, $debuginfo = null) {
        global $CFG;

        $output = '';
        $obbuffer = '';



        // <code copied from the parent renderer>

        // If the page's output has already started, try our best to get the page into a displayable form.
        // We won't always be able to recover here- but it really won't be possible to construct an system that will work
        // in every case- we'd need to handle an error at every possible step!
        if ($this->has_started()) {
            $output .= $this->opencontainers->pop_all_but_last();
        } else {

            // It is really bad if library code throws exception when output buffering is on,
            // because the buffered text would be printed before our start of page.
            // NOTE: this hack might be behave unexpectedly in case output buffering is enabled in PHP.ini

            error_reporting(0); // disable notices from gzip compression, etc.
            while (ob_get_level() > 0) {
                $buff = ob_get_clean();
                if ($buff === false) {
                    break;
                }
                $obbuffer .= $buff;
            }
            error_reporting($CFG->debug);

            // Header not yet printed
            if (isset($_SERVER['SERVER_PROTOCOL'])) {
                // server protocol should be always present, because this render
                // can not be used from command line or when outputting custom XML
                @header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            }

            $this->page->set_context(null); // ugly hack - make sure page context is set to something, we do not want bogus warnings here
            $this->page->set_url('/'); // no url
            //$this->page->set_pagelayout('base'); //TODO: MDL-20676 blocks on error pages are weird, unfortunately it somehow detect the pagelayout from URL :-(
            $this->page->set_title(get_string('error'));
            $this->page->set_heading($this->page->course->fullname);
            $output .= $this->header();
        }


        // </parent code>

        // Debug:

        $output .= '<div id="errorscreen">';

        // Pretty print our error header.
        // Note that we don't use HTMLWriter, to minimize dependencies and thus limit the possibility of a fatal error during fatal error output.
        $output .= '<div class="prettyerrorheader">&nbsp;</div>';

        // Output the error message
        $message = '<p class="errormessage">' . $message . '</p>';

        // Display the "don't panic" line after the error message.
        $message .= '<p class="errorsuffix">' . get_string('errorsuffix', 'theme_bumoodle') . '</p>';

        // Display the error record number.
        $reference = sha1(format_backtrace($backtrace, true) . $debuginfo . $CFG->wwwroot);
        $message .= '<p class="errorreference">'.get_string('errorreference', 'theme_bumoodle', $reference).'</p>';


        if (empty($CFG->rolesactive)) {
            $message .= '<p class="errormessage">' . get_string('installproblem', 'error') . '</p>';
            //It is usually not possible to recover from errors triggered during installation, you may need to create a new database or use a different database prefix for new installation.
        }


        //If debugging is on, or the user is an administrator, display debugging details.
        if (debugging('', DEBUG_DEVELOPER) || is_siteadmin()) {
            if (!empty($debuginfo)) {
                $debuginfo = s($debuginfo); // removes all nasty JS
                $debuginfo = str_replace("\n", '<br />', $debuginfo); // keep newlines
                $message .= $this->notification('<strong>Debug info:</strong> '.$debuginfo, 'notifytiny');
            }
            if (!empty($backtrace)) {
                $message .= $this->notification('<strong>Stack trace:</strong> '.format_backtrace($backtrace), 'notifytiny');
            }
            if ($obbuffer !== '' ) {
                $message .= $this->notification('<strong>Output buffer:</strong> '.s($obbuffer), 'notifytiny');
            }
        }

        $output .= $this->box($message, 'errorbox');
        $output .= '</div>';

        if (empty($CFG->rolesactive)) {
            // continue does not make much sense if moodle is not installed yet because error is most probably not recoverable
        } else if (!empty($link)) {
            $output .= $this->continue_button($link);
        }

        $output .= $this->footer();

        // Padding to encourage IE to display our error page, rather than its own.
        $output .= str_repeat(' ', 512);

        return $output;
    }


}
