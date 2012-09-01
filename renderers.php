<?php

// Include all PHP files in the pdf_renderers directory.
foreach(glob($CFG->dirroot . '/theme/bumoodle/renderers/*.php') as $renderer) {
    include_once($renderer);
}
