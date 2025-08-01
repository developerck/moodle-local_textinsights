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
 * Module initialization
 *
 * @module     local_textinsights/module
 * @copyright  2025 DeveloperCK <developerck@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import {call as fetchMany} from 'core/ajax';
import Notification from 'core/notification';

/**
 * Initialize the module
 *
 * @param {number} courseId The course ID
 * @param {Object} capabilities The user capabilities
 */
export const init = (courseId, capabilities) => {
    // Create context menu element
    const menuHtml = `
        <div class="textinsights-menu" style="display:none;">
            <div class="list-group">
                ${capabilities.explain ?
                    `<a href="#" class="list-group-item list-group-item-action" data-action="explain">
                        <i class="fa fa-info-circle"></i> ${M.util.get_string('explain', 'local_textinsights')}
                    </a>` : ''}
                ${capabilities.summarize ?
                    `<a href="#" class="list-group-item list-group-item-action" data-action="summarize">
                        <i class="fa fa-compress"></i> ${M.util.get_string('summarize', 'local_textinsights')}
                    </a>` : ''}
                ${capabilities.validate ?
                    `<a href="#" class="list-group-item list-group-item-action" data-action="validate">
                        <i class="fa fa-check-circle"></i> ${M.util.get_string('validate', 'local_textinsights')}
                    </a>` : ''}
            </div>
            <div class="ai-icon-bottom-right">
        <div class="ai-icon-bottom-right"> ${M.util.get_string('poweredby', 'local_textinsights')}
        <i class="fa fa-robot fa-spin"></i>
        </div>
        </div>
        </div>`;

    // Create result tooltip element
    const tooltipHtml = `
        <div class="textinsights-tooltip" style="display:none;">
            <div class="textinsights-tooltip-content"></div>
            <button type="button" class="btn btn-link btn-sm close-tooltip">
                <i class="fa fa-times"></i>
            </button>
        </div>`;

    const $menu = $(menuHtml).appendTo('body');
    const $tooltip = $(tooltipHtml).appendTo('body');
    // Add styles
    $('head').append(`
        <style>
            .textinsights-tooltip {
                position: absolute;
                max-width: 400px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 15px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                z-index: 1050;
            }
            .textinsights-tooltip .close-tooltip {
                position: absolute;
                top: 5px;
                right: 5px;
                padding: 0;
                width: 24px;
                height: 24px;
                line-height: 24px;
                text-align: center;
            }
            .textinsights-menu {
                position: absolute;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                z-index: 1000;
            }
            .textinsights-loading {
                text-align: center;
                padding: 10px;
            }
            .ai-icon-bottom-right {
            position: relative;
            left: 5px;   
            color: #007bff;  
            opacity: 0.7;    
        }
        </style>
    `);

    // Function to get selected text and its container
    const getSelectedText = () => {
        const selection = window.getSelection();
        if (!selection.rangeCount) {return null;}

        const range = selection.getRangeAt(0);
        const text = range.toString().trim();
        if (!text) {return null;}

        // Expand selection to include whole words
        const startNode = range.startContainer;
        const endNode = range.endContainer;
        if (startNode.nodeType === Node.TEXT_NODE) {
            const startOffset = range.startOffset;
            const text = startNode.textContent;
            let start = startOffset;
            while (start > 0 && /\S/.test(text[start - 1])) {
                start--;
            }
            range.setStart(startNode, start);
        }
        if (endNode.nodeType === Node.TEXT_NODE) {
            const endOffset = range.endOffset;
            const text = endNode.textContent;
            let end = endOffset;
            while (end < text.length && /\S/.test(text[end])) {
                end++;
            }
            range.setEnd(endNode, end);
        }

        return {
            text: range.toString().trim(),
            range: range
        };
    };

    // Process text through GPT
    const processText = async (text, action) => {
        try {
            const result = await fetchMany([{
                methodname: 'local_textinsights_process_text',
                args: {
                    text: text,
                    action: action,
                    courseid: courseId
                }
            }])[0];
            return result.result;
        } catch (error) {
            Notification.exception(error);
            return M.util.get_string('error', 'local_textinsights');
        }
    };

    // Position tooltip near selection
    const positionTooltip = (range) => {
        const rect = range.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        $tooltip.css({
            top: rect.bottom + scrollTop + 5 + 'px',
            left: rect.left + scrollLeft + 'px'
        });
    };

    // Handle context menu positioning and display
    $(document).on('mouseup', '.course-content, .course-content *', async () => {
        const selection = getSelectedText();
        if (!selection || !selection.text) {
            $menu.hide();
            return;
        }

        // Position menu near selection
        const rect = selection.range.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        $menu.css({
            top: rect.bottom + scrollTop + 5 + 'px',
            left: rect.left + scrollLeft + 'px'
        }).show();

        // Store selection data
        $menu.data('selection', selection);
    });

    // Handle menu item clicks
    $menu.on('click', '[data-action]', async function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        const selection = $menu.data('selection');
        if (!selection) {return;}

        // Hide menu
        $menu.hide();

        // Show loading tooltip
        $tooltip.find('.textinsights-tooltip-content')
            .html('<div class="textinsights-loading"><i class="fa fa-loading fa-spin"></i> ' +
                  M.util.get_string('loading', 'local_textinsights') + '</div>');
        $tooltip.show();
        positionTooltip(selection.range);

        // Process text
        const result = await processText(selection.text, action);

        // Update tooltip with result
        $tooltip.find('.textinsights-tooltip-content').html(result);
        positionTooltip(selection.range);
    });

    // Handle tooltip close button
    $tooltip.on('click', '.close-tooltip', () => {
        $tooltip.hide();
    });

    // Hide menu and tooltip on click outside
    $(document).on('mousedown', (e) => {
        if (!$(e.target).closest('.textinsights-menu, .textinsights-tooltip').length) {
            $menu.hide();
            $tooltip.hide();
        }
    });

    // Handle window resize and scroll
    $(window).on('resize scroll', () => {
        if ($tooltip.is(':visible')) {
            const selection = $menu.data('selection');
            if (selection) {
                positionTooltip(selection.range);
            }
        }
    });
};
