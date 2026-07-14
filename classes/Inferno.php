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

    public function __construct($codex)
    {
        $this->codex = $codex;
        $this->_content = '';
        $this->_js = '';
    }

    public function loadCss() {}

    public function loadJs($script)
    {
        $return = '<script src="' . $script . '"></script>' . PHP_EOL;
        $this->_js .= $return;
    }

    public function getParam(string $param)
    {
        return $this->codex->get($param);
    }

    public function loadTheme()
    {
        if ($this->getParam("theme")) {
            $this->_theme = $this->getParam("theme");
            return true;
        } else {
            return false;
        }
    }

    public function purgatory($case = '')
    {
        $return = file_get_contents('purgatory.html', FILE_USE_INCLUDE_PATH);

        switch ($case) {
            case "404":
                $return = str_replace("%%%TITLE%%%", "Vous cherchez quoi au juste ?", $return);
                $return = str_replace("%%%CONTENT%%%", "Vous cherchez quoi au juste ?", $return);
                break;
            default:
                $return = str_replace("%%%TITLE%%%", "Rien à voir pour le moment !", $return);
                $return = str_replace("%%%CONTENT%%%", "Rien à voir pour le moment !", $return);
        }


        echo $return;
    }

    public function loadArticles(array $route)
    {
        switch ($route['name']) {
            case 'entry':

                $articles = new Vault();

                $articles->setCurrentEntry($route['value']);

                return $articles;
                break;

            case 'page':

                $articles = new Vault(true, 4);

                $articles->setCurrentPage($route['value']);
                return $articles;
                break;

            case 'index':

                $articles = new Vault(true, 4);

                $articles->setCurrentPage(1);
                $articles->setCurrentIndex($route['value']);
                return $articles;
                break;

            default:

                $articles = new Vault(true, 4);
                return $articles;
                break;
        }
    }

    public function loadTemplate($template, $vars = [])
    {
        $inferno = $this;
        extract($vars);
        ob_start();
        include 'themes/' . $this->_theme . '/' . $template . '.php';
        return ob_get_clean();
    }
}
