<?php

 
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) 
{

    // Logo file setting
    $name = 'theme_bumoodle/logo';
    $title = get_string('logo','theme_bumoodle');
    $description = get_string('logodesc', 'theme_bumoodle');
    $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
    $settings->add($setting);

    // link color setting
        $name = 'theme_bumoodle/linkcolor';
        $title = get_string('linkcolor','theme_bumoodle');
        $description = get_string('linkcolordesc', 'theme_bumoodle');
        $default = '#113759';
        $previewconfig = NULL;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $settings->add($setting);

    // main color setting
        $name = 'theme_bumoodle/maincolor';
        $title = get_string('maincolor','theme_bumoodle');
        $description = get_string('maincolordesc', 'theme_bumoodle');
        $default = '#006a4e';
        $previewconfig = NULL;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $settings->add($setting);


}
