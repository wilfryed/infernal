<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require 'class.php';

if (isset($_GET['entry'])){

    $index = substr($_GET['entry'], 0, 1);

    $articles = new articles($index. '.txt');

    echo $articles->displayArticle($_GET['entry']);

}else{

    $articles = new articles(true, "4");

    //echo $articles->searchBar();

    echo $articles->menu();

    if (isset($_GET['page'])){
        echo $articles->displayPage($_GET['page']); 
    }else{
        echo $articles->displayPage();
    }

    echo $articles->pagination();
}

?>