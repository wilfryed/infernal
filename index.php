<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require 'class.php';
$infernal = new Infernal();

$infernal->getHeader();
$infernal->loadCss();
$infernal->display();
<<<<<<< HEAD
=======

if (isset($_GET['entry'])) {
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f

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

<<<<<<< HEAD
} elseif ((isset($_GET['index']))) {    
    //index
    
=======
    $articles = new articles();

    echo $articles->displayArticle($_GET['entry']);
    echo $articles->get_preventry($_GET['entry']);
    echo $articles->get_nextentry($_GET['entry']);
} elseif (isset($_GET['page'])) {

>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
    $articles = new articles(true, "4");
    $articles->setCurrentPage(1);
    $articles->setCurrentIndex($_GET['index']);
    $infernal->getTemplatePart('homepage', $articles);

<<<<<<< HEAD
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
=======
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
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
}
echo "];";
echo '</script>';

<<<<<<< HEAD
$infernal->loadJs('http://wilfryed.com/app/infernal/assets/js/vendor/typeahead.bundle.js');
$infernal->loadJs('http://wilfryed.com/app/infernal/assets/js/app.js');
=======
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
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
$infernal->getFooter();
$infernal->display();
?>