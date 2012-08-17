<?php


require_once($CFG->dirroot.'/question/engine/renderer.php');

/**
 * Core Question Renderer, modified for Moodle@BU
 * 
 * @uses core_question_renderer
 * @package theme_bumoodle
 * @version $id$
 * @copyright 2011, 2012 Binghamton University
 * @author Kyle Temkin <ktemkin@binghamton.edu> 
 * @license GNU Public License, {@link http://www.gnu.org/copyleft/gpl.html}
 */
class theme_bumoodle_core_question_renderer extends core_question_renderer
{

    /**
     * Generate the information bit of the question display that contains the
     * metadata like the question number, current state, and mark.
     * @param question_attempt $qa the question attempt to display.
     * @param qbehaviour_renderer $behaviouroutput the renderer to output the behaviour
     *      specific parts.
     * @param qtype_renderer $qtoutput the renderer to output the question type
     *      specific parts.
     * @param question_display_options $options controls what should and should not be displayed.
     * @param string|null $number The question number to display. 'i' is a special
     *      value that gets displayed as Information. Null means no number is displayed.
     * @return HTML fragment.
     */
    protected function info(question_attempt $qa, qbehaviour_renderer $behaviouroutput, qtype_renderer $qtoutput, question_display_options $options, $number) {
        $output = '';
        $output .= $this->number($number);
        $output .= $this->status($qa, $behaviouroutput, $options);
        $output .= $this->mark_summary($qa, $options);
        $output .= $this->question_flag($qa, $options->flags);
        $output .= $this->ask_instructor_link($qa, $number);
        $output .= $this->edit_question_link($qa, $options);
        return $output;
    }

    /**
     * Create an "add instructor" link, which should be added to the question info area.
     * 
     * @param question_attempt $qa   The question attempt being displayed.
     * @param string|null $number    The question number to display.
     * @return string                HTML fragment.
     */
    protected function ask_instructor_link($qa, $number) {

        // Collect the parameters needed to identify the question in the Ask Instructor field.
        $parameters = array(
            'type' => 'askinstructor',
            'id' => $qa->get_database_id(),
            'number' => $number
        );

        // ... and build the link to the Ask Instructor form.
        $url = new moodle_url('/blocks/quickmail/email.php', $parameters);

        // Compute the link text for the "ask instructor" link.
        $link_text = $this->pix_icon('i/askquestion', get_string('edit')) .  get_string('askinstructor', 'block_quickmail'); 

        // Create the ask instructor link...
        $output = html_writer::start_tag('div', array('class' => 'editquestion'));
        $output .=  html_writer::link( $url, $link_text, array('target' => '_blank'));
        $output .= html_writer::end_tag('div');

        // ... and return it.
        return $output;

    }
}