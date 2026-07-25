<?php

/**
 * Core class for the Infernal CMS rendering workflow.
 *
 * Handles theme loading, template parts, asset injection, and error pages.
 */
class Inferno
{

    private $codex;
    private $_content;
    private $_js;
    private $_theme;
    private $param;
    private $markdown;
    private $query;
    private $vault;
    private string $themePath;

    public function __construct(Codex $codex, Markdown $markdown)
    {
        $this->codex = $codex;
        $this->markdown = $markdown;
        $this->vault = new Vault($markdown);
    }

    public function query(string $path): Query
    {
        $vault = new Vault(
            $this->markdown,
            $path
        );

        return new Query($vault);
    }

    public function dispatch(array $route): array
    {
        $path = implode('/', $route['path']);

        $resource = $this->vault->resolve($path);
        if ($route['name'] === 'homepage') {
            if ($_SERVER['REQUEST_URI'][-1] !== '/') {
                header("Location: " . $_SERVER['REQUEST_URI'] . "/", true, 301);
                exit;
            }

            $vault = new Vault($this->markdown, $resource['path']);
            $page = $this->vault->load('contents/homepage.md');

            return [
                'template' => 'homepage',
                'data' => [
                    'title' => $resource['path'],
                    'entries' => $vault->getEntries(),
                    'page' => $page
                ]
            ];
        }

        if ($route['name'] !== 'path') {

            return [
                'template' => '404',
                'data' => []
            ];
        }

        switch ($resource['type']) {
            case 'archive':

                if ($_SERVER['REQUEST_URI'][-1] !== '/') {
                    header("Location: " . $_SERVER['REQUEST_URI'] . "/", true, 301);
                    exit;
                }

                $vault = new Vault($this->markdown, $resource['path']);
                $config = $vault->loadConfig($resource['path']);

                if ($config['type'] === 'page') {
                    $page = $this->vault->load('contents/widmo/' . $resource['path'] . '.md');

                    return [
                        'template' => $config['template'],
                        'data' => [
                            'title' => $resource['path'],
                            'page' => $page,
                            'path' => $resource['path']
                        ]
                    ];
                } else {

                    return [
                        'template' => 'archive',
                        'data' => [
                            'title' => $resource['path'],
                            'entries' => $vault->getEntries(),
                            'path' => $resource['path']
                        ]
                    ];
                }

            case 'single':

                $entry = $this->vault->load($resource['file']);

                return [
                    'template' => 'single',
                    'data' => [
                        'entry' => $entry
                    ]
                ];

            case 'page':

                $page = $this->vault->load(
                    $resource['file']
                );


                return [
                    'template' => 'page',
                    'data' => [
                        'page' => $page
                    ]
                ];

            default:

                return [
                    'template' => '404',
                    'data' => []
                ];
        }
    }

    public function getParam(string $param)
    {
        return $this->codex->get($param);
    }

    public function loadTheme()
    {
        if ($this->getParam("theme")) {
            $this->_theme = $this->getParam("theme");
            $this->themePath = "themes/" . $this->_theme;
            return true;
        } else {
            return false;
        }
    }

    public function loadTemplate($template, $data = [])
    {
        $inferno = $this;
        extract($data);
        include 'themes/' . $this->_theme . '/' . $template . '.php';
    }

    public function getTemplatePart(string $part): void
    {
        $file = $this->themePath . "/" . $part . ".php";

        if (!file_exists($file)) {
            return;
        }
        include $file;
    }

    public function getThemeUrl(): string
    {
        return BASE_URL . '/themes/' . $this->getParam('theme');
    }
}
