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
 * Library functions
 *
 * @package    local_textinsights
 * @copyright  2025 DeveloperCK <developerck@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Inject JavaScript into course pages
 *
 * @param moodle_page $page The current page
 */
function local_textinsights_before_standard_html_head() {
    global $COURSE, $PAGE;
    $page = $PAGE;
    $contextlevel = $page->context->contextlevel;

    $allowed = false;
    $moduletype = '';

    if ($contextlevel === CONTEXT_COURSE) {
        $allowed = (bool) get_config('local_textinsights', 'enable_course');
    } else if ($contextlevel === CONTEXT_MODULE) {
        $enabledmodules = get_config('local_textinsights', 'enabled_modules');
        $enabled = !empty($enabledmodules) ? explode(',', $enabledmodules) : [];
        $cm = get_coursemodule_from_id('', $page->context->instanceid);
        if ($cm) {
            $moduletype = $cm->modname;
            $allowed = in_array($moduletype, $enabled);
        }
    }

    if (!$allowed) {
        return;
    }

    // Check capabilities.
    $capabilities = [
        'explain'   => has_capability('local/textinsights:useexplain', $page->context),
        'summarize' => has_capability('local/textinsights:usesummarize', $page->context),
        'validate'  => has_capability('local/textinsights:usevalidate', $page->context),
    ];

    if (!array_filter($capabilities)) {
        return;
    }

    $page->requires->jquery();
    $page->requires->js_call_amd('local_textinsights/module', 'init', [
        $COURSE->id,
        $capabilities,
    ]);
    $page->requires->strings_for_js([
        'explain',
        'summarize',
        'validate',
        'loading',
        'error',
        'poweredby',
    ], 'local_textinsights');
}
