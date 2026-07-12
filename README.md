# Infernal

Infernal is a lightweight, file-based PHP content management system for publishing simple text entries as a small website or blog. It requires no database and is designed to be easy to deploy on a standard Apache/PHP environment.

## Features

- Flat-file content storage using plain `.txt` files
- Simple routing for homepage, entry pages, and paginated pages
- Theme-based rendering through the `themes/` directory
- Basic search/autocomplete support via the frontend script
- No database required

## Recent changes

The project has been reorganized around a lightweight class-based bootstrap:

- An `autoload.php` file now initializes the application and loads the core classes automatically.
- The main logic is split into dedicated classes under `classes/` for configuration, content handling, rendering, and access control.
- The entry point and theme rendering flow have been simplified for easier maintenance.

## Requirements

- PHP 7+ recommended
- Apache with `mod_rewrite` enabled
- A web server root pointing to this project directory

## Installation

1. Clone or copy this project into your web server document root.
2. Make sure the project folder is accessible by your web server.
3. Update the configuration in `config.ini` if needed.
4. Open the site in your browser.

Example local setup with Laragon:

- Place the project in a folder such as `C:\laragon\www\infernal`
- Open `http://infernal/` or the matching local URL for your setup

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

Content entries are stored in the `contents/` directory as `.txt` files.

Each entry can contain one or more paragraphs. The current parser uses the content as plain text and extracts titles/links from markup patterns such as:

```text
{Abaddon}, le destructeur; chef des démons de la septième hiérarchie.
```

You can add new content files and the system will include them automatically when the site is rendered.

## Routing

The project uses URL rewriting rules defined in `.htaccess`:

- `/entry/<slug>` displays a single entry
- `/page/<number>` displays a paginated page
- `/<page>.html` displays a page based on the index name

## Project structure

```text
.
├── assets/            # Frontend assets
├── classes/           # Core PHP classes
│   ├── Codex.php      # INI configuration loader
│   ├── Gatekeeper.php # Access-control helper
│   ├── Infernal.php   # Main rendering workflow
│   └── Vault.php      # Content loading and rendering logic
├── contents/          # Text content files
├── themes/            # Theme templates
├── autoload.php       # Application bootstrap and autoloader
├── config.ini         # Site configuration
├── index.php          # Entry point
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
