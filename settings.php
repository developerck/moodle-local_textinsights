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
 * Settings page
 *
 * @package    local_textinsights
 * @copyright  2025 DeveloperCK <developerck@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_textinsights', get_string('pluginname', 'local_textinsights'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext(
        'local_textinsights/apikey',
        get_string('apikey', 'local_textinsights'),
        get_string('apikey_desc', 'local_textinsights'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configselect(
        'local_textinsights/model',
        get_string('model', 'local_textinsights'),
        get_string('model_desc', 'local_textinsights'),
        'gpt-3.5-turbo',
        [
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
            'gpt-4' => 'GPT-4',
        ]
    ));

    $settings->add(new admin_setting_configtext(
        'local_textinsights/maxlength',
        get_string('maxlength', 'local_textinsights'),
        get_string('maxlength_desc', 'local_textinsights'),
        '1000',
        PARAM_INT
    ));
}
