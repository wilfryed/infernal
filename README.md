# Infernal

Infernal is a zero-database, zero-build, zero-dependency CMS.
A lightweight, file-based PHP content management system for publishing simple text and markdown entries as a small website or blog.

Write your content in Markdown.
Upload it via FTP.
Publish instantly.

## Features

- Flat-file content storage using `.txt` and `.md` files
- URL-based routing for homepage, entry, page, and index-based views
- Theme-based rendering through the `themes/` directory
- Markdown parsing for headings, emphasis, links, images, code, quotes, and paragraphs
- Built-in fallback/error rendering
- No database or build step required

## Recent changes

The project now runs through a lightweight class-based bootstrap:

- `autoload.php` registers the autoloader and loads the core classes automatically.
- `index.php` initializes the session, resolves the current route, loads the active theme, and renders the page.
- `classes/Invoker.php` coordinates the header, main content, and footer rendering flow.
- `classes/Markdown.php` converts content files into HTML fragments.
- `classes/Vault.php` loads, paginates, and renders file-based entries.
- `classes/Gatekeeper.php` resolves the current route from the request.

## Requirements

- PHP 7+ recommended
- Apache with `mod_rewrite` enabled
- A web server root pointing to this project directory
- Read/write access to `contents/` and `themes/` if you plan to add content or customize templates

## Installation

1. Copy or clone this project into your web server document root.
2. Make sure Apache is allowed to serve the folder and that `.htaccess` rewrites are enabled.
3. Update the settings in `config.ini` if needed.
4. Add content files under `contents/` using `.txt` or `.md`.
5. Open the site in your browser.

## Configuration

The main configuration file is `config.ini`.

Example:

```ini
site_title = Infernal
site_subtitle = a simple static CMS
theme = infernal
base_url = /infernal
```

### Settings

- `site_title`: Main site title shown in the theme
- `site_subtitle`: Subtitle shown on the homepage
- `theme`: Theme folder name inside `themes/`
- `base_url`: Base URL used by the templates and links

## Content format

Content entries are stored in the `contents/` directory as `.txt` or `.md` files.

Each file can contain multiple paragraphs and supports a simple Markdown syntax. The parser currently handles headings, emphasis, links, images, inline code, blockquotes, horizontal rules, and paragraph formatting. New files are picked up automatically when the site is rendered.

## Routing

The project uses URL rewriting rules defined in `.htaccess`:

- `/entry/<slug>` displays a single entry
- `/page/<number>` displays a paginated list of entries
- `/<index>.html` routes to an index-based view using the matching content index
- The default homepage is rendered when no route is provided

## Project structure

```text
.
├── assets/            # Frontend assets
├── classes/           # Core PHP classes
│   ├── Codex.php      # INI configuration loader
│   ├── Gatekeeper.php # Route resolution
│   ├── Inferno.php    # Theme and rendering helpers
│   ├── Invoker.php    # Rendering orchestration
│   ├── Markdown.php   # Markdown-to-HTML parser
│   └── Vault.php      # Content loading and pagination
├── contents/          # Content entries (.txt/.md)
├── themes/            # Theme templates
├── autoload.php       # Application bootstrap and autoloader
├── config.ini         # Site configuration
├── index.php          # Application entry point
├── .htaccess          # URL rewriting rules
└── purgatory.html     # Fallback/error page template
```

## Theme customization

Themes live in `themes/<theme-name>/` and include:

- `header.php`
- `footer.php`
- `homepage.php`
- `entry.php`

To create a new theme, add a new folder under `themes/` and update `config.ini` to use it.

## License

This project is distributed under the Apache License 2.0. See `LICENSE` for details.
