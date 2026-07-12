<?php
session_start();

require 'autoload.php';

$codex = new Codex();
$infernal = new Infernal($codex);

define('BASE_URL', $infernal->getParam('base_url'));

if (!$infernal->loadTheme()) {
    echo 'Theme not found.';
    $infernal->purgatory();
    exit;
}

$gatekeeper = new Gatekeeper();
$route = $gatekeeper->getRoute();

$infernal->getHeader();
$infernal->loadCss();

switch ($route['name']) {
    case 'entry':

        $articles = new Vault();

        $articles->setCurrentEntry($route['value']);

        $infernal->getTemplatePart(
            'entry',
            $articles
        );

        break;

    case 'page':

        $articles = new Vault(true, 4);

        $articles->setCurrentPage($route['value']);

        $infernal->getTemplatePart(
            'homepage',
            $articles
        );

        break;

    case 'index':

        $articles = new Vault(true, 4);

        $articles->setCurrentPage(1);
        $articles->setCurrentIndex($route['value']);

        $infernal->getTemplatePart(
            'homepage',
            $articles
        );

        break;

    default:

        $articles = new Vault(true, 4);

        $infernal->getTemplatePart(
            'homepage',
            $articles
        );

        break;

}

echo '<script>';
echo "var entries = [";
$items = $articles->getEntryTitle();
foreach ($items as $item) {
    $item = explode('#', $item);
    echo "{'slug': '" . $item[1] . "', 'value': '" . $item[0] . "' }, ";
}
echo "];";
echo '</script>';

$infernal->getFooter();

$infernal->display();