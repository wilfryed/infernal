<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require 'class.php';
$infernal = new Infernal();

$infernal->getHeader();
$infernal->loadCss();
$infernal->display();

if (isset($_GET['entry'])) {
    //entry
    
    $index = substr($_GET['entry'], 0, 1);
    
    $articles = new articles();
    $articles->setCurrentEntry($_GET['entry']);
    $infernal->getTemplatePart('entry', $articles);
    
} elseif (isset($_GET['page'])) {
    // page
    
    $articles = new articles(true, "4");
    $articles->setCurrentPage($_GET['page']);
    $infernal->getTemplatePart('homepage', $articles);

} elseif ((isset($_GET['index']))) {    
    //index
    
    $articles = new articles(true, "4");
    $articles->setCurrentPage(1);
    $articles->setCurrentIndex($_GET['index']);
    $infernal->getTemplatePart('homepage', $articles);

} else {
    //homepage

    $articles = new articles(true, "4");
    $infernal->getTemplatePart('homepage', $articles);

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

$infernal->loadJs('http://wilfryed.com/app/infernal/assets/js/vendor/typeahead.bundle.js');
$infernal->loadJs('http://wilfryed.com/app/infernal/assets/js/app.js');
$infernal->getFooter();
$infernal->display();
?>