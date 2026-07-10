# TextInsights #

A local Moodle plugin that provides AI-powered text analysis capabilities through a convenient context menu in course content.

[![TextInsights](https://github.com/developerck/moodle-local_textinsights/actions/workflows/ci.yml/badge.svg)](https://github.com/developerck/moodle-local_textinsights/actions/workflows/ci.yml)

## Features

- Context menu for selected text in course content
- Three AI-powered analysis options:
  - **Explain** — Simplifies and explains the selected text
  - **Summarize** — Provides a concise summary
  - **Validate** — Checks accuracy and identifies potential issues
- Role-based access control for each feature
- Responsive tooltip-style results display
- Word-boundary aware text selection
- Supports OpenAI (ChatGPT) and Ollama providers

## Requirements

- Moodle 4.5 or later
- OpenAI API key **or** a running Ollama instance

## AI Provider Setup

### OpenAI
Generate an API key from the [OpenAI platform](https://platform.openai.com/), then enter it under **Site Administration → Plugins → Local plugins → Text Insights**.

### Ollama
Provide the Ollama API URL (e.g. `http://localhost:11434`) and select a model. An API key is optional and only required if your Ollama instance is behind an authenticated proxy.

## Configuration

### Page Type Control
Control exactly which pages the toolbar appears on:
- **Course page** — toggle on/off independently
- **Course modules** — select individual module types from the full list of installed modules (e.g. enable Page and File, disable Quiz and Assignment)

### Custom Prompts
Override the default prompt for each action (Explain, Summarize, Validate) with your own wording. The following variables are available and are resolved server-side at call time:

| Variable | Description |
|---|---|
| `{text}` | The selected text |
| `{coursename}` | Full name of the course |
| `{summary}` | Course summary |
| `{topicname}` | Section/topic name (module pages only) |
| `{modulename}` | Activity display name (module pages only) |

Leave a prompt blank to use the built-in default.

---

## Release Notes

### v1.2.0 (2025-08-01)

- **Custom prompts** — Admins can now override the default explain/summarize/validate prompts per action via site settings.
- **Prompt variables** — Custom prompts support `{text}`, `{coursename}`, `{summary}`, `{topicname}`, and `{modulename}` placeholders, all resolved server-side before the AI call.
- **Improved default prompts** — Default prompts now include course name, topic, and activity name as context to produce more relevant AI responses.
- **Page type control** — New setting to enable/disable the toolbar on the course page independently from module pages.
- **Module type allowlist** — Admins can select which course module types show the toolbar. The list is dynamically built from all installed modules on the site (defaults to Page and File enabled). Quiz and Assignment are no longer active by default.
- **Context resolved server-side** — Course name, section/topic name, and activity name are now looked up in `external.php` at call time rather than being passed through JavaScript, keeping the client payload minimal and tamper-proof.


### v1.1.0

- Added Ollama provider support alongside OpenAI
- Added configurable maximum text length
- Added per-capability access control (explain / summarize / validate)

### v1.0.0

- Initial release
- OpenAI-powered explain, summarize, and validate actions
- Context menu on course and module pages

---

[developerck.com](https://developerck.com)

## License ##

2025 Chandra K <developerck@gmail.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program. If not, see <http://www.gnu.org/licenses/>.
