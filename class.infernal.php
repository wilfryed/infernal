<?php

class Infernal {

    private $_content;
    private $_js;
    private $_theme;
    private $param;

    public function __construct() {
        $_content = '';
        $_js = '';
    }

    public function getFooter() {
        $return = file_get_contents('themes/' .$this->_theme. '/footer.php', FILE_USE_INCLUDE_PATH);
        $return = str_replace("%%%JS%%%", $this->_js, $return);
        $this->_content .= $return;
    }

    public function getHeader() {
        $this->_content = file_get_contents('themes/' .$this->_theme. '/header.php', FILE_USE_INCLUDE_PATH);
    }

    public function getTemplatePart($part, $articles) {
        include('themes/' .$this->_theme. '/' . $part . '.php');
    }

    public function loadCss() {

    }

    public function loadJs($script) {
        $return = '<script src="' . $script . '"></script>' . PHP_EOL;
        $this->_js .= $return;
    }

    public function display() {
        echo $this->_content;
        $this->_content = '';
    }

    public function getParam($param) {
        $lines = file('config.ini');
        $return = false;
        foreach ($lines as $line) {
            if (strpos($line, $param) !== false) {
                $line = explode(':',$line);
                if (count($line) > 1){
                    $return = $line[1];
                }

                break;
            }
        }

        return $return;
    }
    
    public function loadTheme(){
        if ($this->getParam("theme")){
            $this->_theme = $this->getParam("theme");
            return true;
        }else{
            return false;
        }
    }

    public function purgatory($case = '') {
        $return = file_get_contents('purgatory.html', FILE_USE_INCLUDE_PATH);
        
        switch ($case) {
            case "404":
                $return = str_replace("%%%TITLE%%%", "Vous cherchez quoi au juste ?", $return);
                $return = str_replace("%%%CONTENT%%%", "Vous cherchez quoi au juste ?", $return);
                break;
            default:
                $return = str_replace("%%%TITLE%%%", "Rien à voir pour le moment !", $return);
                $return = str_replace("%%%CONTENT%%%", "Rien à voir pour le moment !", $return);
        }
        
        
        echo $return;
    }

}

?>