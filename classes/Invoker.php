<?php


class Invoker
{

    private $inferno;

    public function __construct($inferno)
    {
        $this->inferno = $inferno;

        $inferno->loadTheme();
    }

    public function castRendering(array $page)
    {
        $template = $page['template'];
        $data = $page['data'];
        $this->inferno->loadTemplate($template, $data);
    }

    public function render(array $page)
    {
        $template = $page['template'];

        $data = $page['data'];

        include "themes/" . $theme . "/" . $template . ".php";
    }
}