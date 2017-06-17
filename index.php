<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

function __autoload($class_name) {
    include 'class.' . strtolower($class_name) . '.php';
}

$infernal = new Infernal();

if ($infernal->loadTheme()){

    $infernal->getHeader();
    $infernal->loadCss();
    $infernal->display();

    if (isset($_GET['entry'])) {
        //entry

        $index = substr($_GET['entry'], 0, 1);

        $articles = new Vault();
        $articles->setCurrentEntry($_GET['entry']);
        $infernal->getTemplatePart('entry', $articles);

    } elseif (isset($_GET['page'])) {
        // page

        $articles = new Vault(true, "4");
        $articles->setCurrentPage($_GET['page']);
        $infernal->getTemplatePart('homepage', $articles);

    } elseif ((isset($_GET['index']))) {    
        //index
        $articles = new Vault(true, "4");
        $articles->setCurrentPage(1);
        $articles->setCurrentIndex($_GET['index']);
        $infernal->getTemplatePart('homepage', $articles);

    } else {
        //homepage

        $articles = new Vault(true, "4");
        include('invoker.php');
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

}else{
    if (isset($_GET['404'])){
        $infernal->purgatory("404");
    }else{
        $infernal->purgatory();
    }    
}


?>