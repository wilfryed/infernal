<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require 'class.php';
$infernal = new Infernal();

$infernal->getHeader();
$infernal->loadCss();
$infernal->display();

if (isset($_GET['entry'])) {

    $index = substr($_GET['entry'], 0, 1);

    $articles = new articles();

    echo $articles->displayArticle($_GET['entry']);
    echo $articles->get_preventry($_GET['entry']);
    echo $articles->get_nextentry($_GET['entry']);
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
    
    echo '<p>Search : </p>';
    echo '<div id="the-basics">';
    echo '<input class="typeahead" type="text">';
    echo '</div>';
    
    
    echo $articles->menu();
    echo $articles->displayPage();
    echo $articles->pagination();
}

echo '<script>';
echo "var entries = [";
$items = $articles->getEntryTitle();
foreach ($items as $item){
    $item = explode('#',$item);
    echo "{'slug': '".$item[1]."', 'value': '".$item[0]."' }, ";
}
echo "];";
echo '</script>';

$infernal->loadJs('assets/js/vendor/jquery.js');
$infernal->loadJs('assets/js/vendor/typeahead.bundle.js');
$infernal->loadJs('assets/js/app.js');
$infernal->getFooter();
$infernal->display();
?>