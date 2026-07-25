# Infernal

Infernal is a zero-database, zero-build, zero-dependency CMS for publishing simple websites and blogs from flat files.
It reads content from the contents/ directory, parses Markdown, supports optional front matter metadata, and renders pages through a lightweight PHP class-based workflow.

## Features

- Flat-file content storage using .md files
- Front matter metadata such as title, date, author, categories, and tags
- Route-based rendering for the homepage, archive views, single entries, and content pages
- Theme-based rendering through the themes/ directory
- Markdown support for headings, emphasis, links, images, inline code, blockquotes, horizontal rules, and paragraphs
- Built-in fallback/error rendering
- No database or build step required

## Recent changes

The current codebase uses a small class-based bootstrap with dedicated responsibilities:

- autoload.php registers the autoloader and loads the core classes automatically.
- index.php boots the application, resolves the current route, and renders the response.
- Gatekeeper translates the request into a route array.
- Inferno dispatches the route to the correct template and data payload.
- Vault loads file-based content, parses metadata, and creates Entry objects.
- Entry wraps a single document with metadata and rendered content.
- Markdown converts content into HTML fragments.
- The Infernal theme now provides dedicated templates for homepage, archive, single entry, page, and 404 views.

## Requirements

- PHP 8.0+ recommended
- Apache with mod_rewrite enabled
- A web server document root pointing to this project directory
- Read/write access to contents/ and themes/ if you plan to add content or customize templates

## Installation

1. Copy or clone this project into your web server document root.
2. Make sure Apache is allowed to serve the folder and that .htaccess rewrites are enabled.
3. Review config.ini and adjust the site title, subtitle, theme, and base_url values if needed.
4. Add content files under contents/ using .md or .txt.
5. Open the site in your browser.

## Configuration

The main configuration file is config.ini.

Example:

```ini
site_title = Infernal
site_subtitle = a simple static CMS
theme = infernal
base_url = /infernal
```

### Settings

- site_title: Main site title displayed by the theme
- site_subtitle: Subtitle shown on the homepage
- theme: Theme folder name inside themes/
- base_url: Base URL used by templates and links

## Content format

Content files are stored in the contents/ directory as .md or .txt files.

Each file may start with optional front matter between --- delimiters:

```md
---
title: My first post
date: 2026-07-20
author: Example
categories: [news, updates]
tags: [php, cms]
---

# Hello world

This is the body of the entry.
```

If no front matter is present, the file is treated as plain Markdown content. New files are discovered automatically when the site is rendered.

## Routing

The project uses URL rewriting rules from .htaccess to send requests through index.php.

Typical behavior is:

- / renders the homepage
- /section/ renders an archive view for the matching folder
- /section/page-name renders a single entry or page view for the matching file
- Unknown routes fall back to the 404 template

## Project structure

```text
.
├── assets/                # Frontend assets
├── classes/               # Core PHP classes
│   ├── Codex.php          # INI configuration loader
│   ├── Entry.php         # Entry object wrapper for a content file
│   ├── Gatekeeper.php    # Route resolution from the request
│   ├── Inferno.php       # Dispatch and template orchestration
│   ├── Invoker.php       # Rendering helper wrapper
│   ├── Markdown.php      # Markdown-to-HTML parser
│   └── Vault.php         # Content loading and content resolution
├── contents/              # Content entries (.md/.txt)
├── themes/                # Theme templates
├── autoload.php           # Application bootstrap and autoloader
├── config.ini             # Site configuration
├── index.php              # Application entry point
├── .htaccess              # URL rewriting rules
└── purgatory.html        # Fallback/error page template
```

## Theme customization

Themes live in themes/<theme-name>/ and can include:

- header.php
- footer.php
- homepage.php
- archive.php
- single.php
- page.php
- 404.php

To create a new theme, add a new folder under themes/ and update config.ini to use it.

## License

This project is distributed under the Apache License 2.0. See LICENSE for details.
