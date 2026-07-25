<?php
require 'autoload.php';

$codex = new Codex();
$markdown = new Markdown();
$inferno = new Inferno($codex, $markdown);

define('BASE_URL', $inferno->getParam('base_url'));

$invoker = new Invoker($inferno);
$gatekeeper = new Gatekeeper();

$route = $gatekeeper->getRoute();
$response = $inferno->dispatch($route);
$invoker->castRendering($response);