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

    $settings->add(new admin_setting_description(
        'local_textinsights/scope',
        get_string('scope', 'local_textinsights'),
        get_string('scope_desc', 'local_textinsights')
    ));
    $settings->add(new admin_setting_configtext(
        'local_textinsights/maxlength',
        get_string('maxlength', 'local_textinsights'),
        get_string('maxlength_desc', 'local_textinsights'),
        '1000',
        PARAM_INT
    ));

    $settings->add(new admin_setting_configselect(
        'local_textinsights/provider',
        get_string('provider', 'local_textinsights'),
        get_string('provider_desc', 'local_textinsights'),
        'openai',
        [
            'openai' => 'OPENAI [ChatGPT]',
            'ollama' => 'Ollama',
        ]
    ));

     $settings->add(new admin_setting_heading(
        'local_textinsights/openai_settings',
        get_string('openai_settings', 'local_textinsights'),
        ''
       
    ));
    $settings->add(new admin_setting_configpasswordunmask(
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

    $settings->add(new admin_setting_heading(
        'local_textinsights/ollama_settings',
        get_string('ollama_settings', 'local_textinsights'),
       ''
    ));

    $settings->add(new admin_setting_configtext(
        'local_textinsights/ollama_apiurl',
        get_string('ollama_apiurl', 'local_textinsights'),
        get_string('ollama_apiurl_desc', 'local_textinsights'),
        '',
        PARAM_TEXT
    ));
    $settings->add(new admin_setting_configpasswordunmask(
        'local_textinsights/ollama_apikey',
        get_string('ollama_apikey', 'local_textinsights'),
        get_string('ollama_apikey_desc', 'local_textinsights'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configselect(
        'local_textinsights/ollama_model',
        get_string('ollama_model', 'local_textinsights'),
        get_string('ollama_model_desc', 'local_textinsights'),
        'llama3',
        [
            'llama3' => 'llama3',
            'mistral' => 'mistral',
            'phi3' => 'phi3',
        ]
    ));


    $settings->add(new admin_setting_heading(
        'local_textinsights/prompt_settings',
        get_string('prompt_settings', 'local_textinsights'),
        get_string('prompt_settings_desc', 'local_textinsights')
    ));

    $settings->add(new admin_setting_configtextarea(
        'local_textinsights/customprompt_explain',
        get_string('customprompt_explain', 'local_textinsights'),
        get_string('customprompt_desc', 'local_textinsights'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtextarea(
        'local_textinsights/customprompt_summarize',
        get_string('customprompt_summarize', 'local_textinsights'),
        get_string('customprompt_desc', 'local_textinsights'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtextarea(
        'local_textinsights/customprompt_validate',
        get_string('customprompt_validate', 'local_textinsights'),
        get_string('customprompt_desc', 'local_textinsights'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_heading(
        'local_textinsights/pagetype_settings',
        get_string('pagetype_settings', 'local_textinsights'),
        get_string('pagetype_settings_desc', 'local_textinsights')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_textinsights/enable_course',
        get_string('enable_course', 'local_textinsights'),
        get_string('enable_course_desc', 'local_textinsights'),
        1
    ));

    $moduleoptions = [];
    $installedmods = $DB->get_records('modules', ['visible' => 1], 'name ASC', 'name');
    foreach ($installedmods as $mod) {
        $modname = $mod->name;
        $strkey = 'modulename';
        $modstring = get_string_manager()->string_exists($strkey, 'mod_' . $modname)
            ? get_string($strkey, 'mod_' . $modname)
            : $modname;
        $moduleoptions[$modname] = $modstring;
    }
    $settings->add(new admin_setting_configmulticheckbox(
        'local_textinsights/enabled_modules',
        get_string('enabled_modules', 'local_textinsights'),
        get_string('enabled_modules_desc', 'local_textinsights'),
        ['page' => 1, 'file' => 1],
        $moduleoptions
    ));
}
