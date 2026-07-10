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
 * Language strings
 *
 * @package    local_textinsights
 * @copyright  2025 DeveloperCK <developerck@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Text Insights';
$string['textinsights:useexplain'] = 'Use explain feature';
$string['textinsights:usesummarize'] = 'Use summarize feature';
$string['textinsights:usevalidate'] = 'Use validate feature';
$string['explain'] = 'Explain';
$string['summarize'] = 'Summarize';
$string['validate'] = 'Validate';
$string['loading'] = 'Thinking...';
$string['error'] = 'Error processing request';
$string['settingheader'] = 'Text Insights Settings';
$string['apikey'] = 'OpenAI API Key';
$string['apikey_desc'] = 'Enter your OpenAI API key';
$string['model'] = 'GPT Model';
$string['model_desc'] = 'Select the GPT model to use';
$string['ollama_apikey'] = 'Ollama API Key';
$string['ollama_apikey_desc'] = 'Ollama API key [if any], will be passed in the header as bearer token';
$string['ollama_apiurl'] = 'Ollama API URL';
$string['ollama_apiurl_desc'] = 'Enter the Ollama API URL (e.g., http://localhost:11434)';
$string['ollama_model'] = 'Ollama Model';
$string['ollama_model_desc'] = 'Select the Ollama model to use';
$string['openai_settings'] = 'OpenAI Settings';
$string['ollama_settings'] = 'Ollama Settings';
$string['maxlength'] = 'Maximum text length';
$string['maxlength_desc'] = 'Maximum number of characters that can be processed';
$string['poweredby'] = 'Powered by AI';
$string['privacy:metadata:local_textinsights'] = 'No personal data is stored by the Text Insights plugin.';
$string['textoollong'] = 'The text is too long.';
$string['invalidaction'] = 'Invalid action specified.';
$string['apierror'] = 'Error communicating with API.';
$string['provider'] = 'Provider';
$string['provider_desc'] = 'Select the AI provider for text insights';
$string['scope'] = 'Scope';
$string['scope_desc'] = 'You can use capability to restrict access to the Text Insights features.It works on course content page and page activity only.<br/>';
$string['prompt_settings'] = 'Custom Prompts';
$string['prompt_settings_desc'] = 'Override the default prompts for each action. Use variables: <code>{text}</code>, <code>{coursename}</code>, <code>{summary}</code>, <code>{topicname}</code>, <code>{modulename}</code>. Leave blank to use the default prompt.';
$string['customprompt_explain'] = 'Custom Explain Prompt';
$string['customprompt_summarize'] = 'Custom Summarize Prompt';
$string['customprompt_validate'] = 'Custom Validate Prompt';
$string['customprompt_desc'] = 'Leave blank to use the default prompt. Available variables: {text}, {coursename}, {summary}, {topicname}, {modulename}';
$string['pagetype_settings'] = 'Page Type Settings';
$string['pagetype_settings_desc'] = 'Control which page types the Text Insights toolbar appears on.';
$string['enable_course'] = 'Enable on Course page';
$string['enable_course_desc'] = 'Show the Text Insights toolbar on the main course page.';
$string['enabled_modules'] = 'Enabled Course Modules';
$string['enabled_modules_desc'] = 'Select which course module types should show the Text Insights toolbar. Typically you would enable Page and File, but not Quiz or Assignment.';
