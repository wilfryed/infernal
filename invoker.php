<?php

function have_entries(){
    global $articles;
    
    if ($articles->haveEntries()){
        return true;
    }else{
        return false;
    }
}

function the_entries(){
    global $articles;
    echo $articles->getEntries();
}

?>