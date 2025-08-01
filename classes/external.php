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
 * External Web Service
 *
 * @package    local_textinsights
 * @copyright  2025 DeveloperCK <developerck@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_textinsights;
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

/**
 * This is the external API for this component.
 *
 * @package    local_textinsights
 * @copyright  2025 DeveloperCK <developerck@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends \external_api {
    /**
     * Returns description of process_text parameters
     * @return \external_function_parameters
     */
    public static function process_text_parameters() {
        return new \external_function_parameters([
            'text' => new \external_value(PARAM_TEXT, 'The text to process'),
            'action' => new \external_value(PARAM_ALPHA, 'Action to perform (explain/summarize/validate)'),
            'courseid' => new \external_value(PARAM_INT, 'Course ID'),
        ]);
    }

    /**
     * Process text with GPT
     * @param string $text The text to process
     * @param string $action The action to perform
     * @param int $courseid The course ID
     * @return array
     */
    public static function process_text($text, $action, $courseid) {
        global $USER;

        // Parameter validation.
        $params = self::validate_parameters(self::process_text_parameters(), [
            'text' => $text,
            'action' => $action,
            'courseid' => $courseid,
        ]);

        // Context validation.
        $context = \context_course::instance($params['courseid']);
        self::validate_context($context);

        // Capability checks.
        $capability = "local/textinsights:use{$action}";
        require_capability($capability, $context);

        // Length validation.
        $maxlength = get_config('local_textinsights', 'maxlength');
        if (strlen($params['text']) > $maxlength) {
            throw new \moodle_exception('textoollong', 'local_textinsights');
        }

        // Prepare prompt based on action.
        switch ($params['action']) {
            case 'explain':
                $prompt = "Explain this text in simple terms: {$params['text']}";
                break;
            case 'summarize':
                $prompt = "Summarize this text concisely: {$params['text']}";
                break;
            case 'validate':
                $prompt = "Validate the accuracy of this text and point out any issues: {$params['text']}";
                break;
            default:
                throw new \moodle_exception('invalidaction', 'local_textinsights');
        }

        // Call OpenAI API.
        $apikey = get_config('local_textinsights', 'apikey');
        $model = get_config('local_textinsights', 'model');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apikey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful educational assistant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]));

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            throw new \moodle_exception('apierror', 'local_textinsights');
        }

        $result = json_decode($response, true);
        return [
            'result' => $result['choices'][0]['message']['content'],
        ];
    }

    /**
     * Returns description of process_text return values
     * @return \external_single_structure
     */
    public static function process_text_returns() {
        return new \external_single_structure([
            'result' => new \external_value(PARAM_TEXT, 'The processed text result'),
        ]);
    }
}
