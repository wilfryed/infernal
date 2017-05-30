<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require 'class.php';

if (isset($_GET['entry'])) {

    $index = substr($_GET['entry'], 0, 1);

    $articles = new articles($index . '.txt');

    echo $articles->displayArticle($_GET['entry']);
} elseif (isset($_GET['page'])) {

    $articles = new articles(true, "4");

    echo $articles->menu();
    echo $articles->displayPage($_GET['page']);
    echo $articles->pagination();
} elseif ((isset($_GET['index']))) {    
    
    $articles = new articles(true, "4");
    echo $articles->menu();
    echo $articles->displayIndex($_GET['index']);
} else {
    $articles = new articles(true, "4");
    echo $articles->menu();
    echo $articles->displayPage();
    echo $articles->pagination();
}

?>