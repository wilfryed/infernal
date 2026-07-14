<?php


class Invoker
{

    private $inferno;

    public function __construct($inferno)
    {
        $this->inferno = $inferno;
    }

    public function castRendering(array $route)
    {
        $this->getHeader();
        $this->getMain($route);
        $this->getFooter();
    }

    public function getFooter()
    {
        echo $this->inferno->loadTemplate('footer');
    }

    public function getHeader()
    {
        echo $this->inferno->loadTemplate('header');
    }

    public function getMain(array $route)
    {
        $articles = $this->inferno->loadArticles($route);

        switch ($route['name']) {
            case 'entry':

                $this->getTemplatePart('entry', $articles);

                break;

            case 'page':

                $this->getTemplatePart('homepage', $articles);

                break;

            case 'index':

                $this->getTemplatePart('homepage', $articles);

                break;

            default:

                $this->getTemplatePart('homepage', $articles);

                break;
        }
    }

    public function getTemplatePart($part, $articles)
    {
        echo $this->inferno->loadTemplate($part, ['articles' => $articles]);
    }


    public function getParam(string $param)
    {
        return $this->inferno->get($param);
    }
}
