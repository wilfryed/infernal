<?php
session_start();

require 'autoload.php';

$codex = new Codex();
$markdown = new Markdown();
$inferno = new Inferno($codex, $markdown);
$invoker = new Invoker($inferno);
$gatekeeper = new Gatekeeper();
$route = $gatekeeper->getRoute();

define('BASE_URL', $inferno->getParam('base_url'));

if (!$inferno->loadTheme()) {
    echo 'Theme not found.';
    $inferno->purgatory();
    exit;
}

$invoker->castRendering($route);