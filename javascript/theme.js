M.theme_bumoodle = {};

M.theme_bumoodle.toggle_fullscreen = function () {
    if (Y.one(".region-content").hasClass('fullscreen')) {
        M.theme_bumoodle.switch_out_of_fullscreen();
    } else {
        M.theme_bumoodle.switch_to_fullscreen();
    }
};

M.theme_bumoodle.switch_to_fullscreen = function () {
    Y.one("body").addClass("suppressed");
    Y.one(".region-content").addClass("fullscreen");
};

M.theme_bumoodle.switch_out_of_fullscreen = function () {
    Y.one("body").removeClass("suppressed");
    Y.one(".region-content").removeClass("fullscreen");
};

M.theme_bumoodle.initialize = function() {
    Y.one("#btn-fullscreen").on('click', M.theme_bumoodle.toggle_fullscreen);
    Y.one("#btn-fullscreen").removeClass("hidden");
}
