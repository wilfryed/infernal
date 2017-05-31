<?php

class Infernal {
<<<<<<< HEAD

    private $_content;
    private $_js;

    public function __construct(){
        $_content = '';
        $_js = '';
    }

    public function getFooter(){
        $return = file_get_contents('templates/footer.html', FILE_USE_INCLUDE_PATH);
        $return = str_replace("%%%JS%%%", $this->_js, $return);
        $this->_content .= $return;
    }

    public function getHeader(){
        $this->_content = file_get_contents('templates/header.html', FILE_USE_INCLUDE_PATH);
    }

    public function getTemplatePart($part, $articles){
        include('templates/'.$part.'.html');
    }

    public function loadCss(){

    }

    public function loadJs($script){
        $return = '<script src="'.$script.'"></script>'.PHP_EOL;
        $this->_js .= $return;
    }

    public function display(){
        echo $this->_content;
        $this->_content = '';
    }
=======
    
    private $_content;
    
    public function __construct(){
        
    }
    
    public function getFooter(){
        $return = '</body>'.PHP_EOL;
        $return .= '</html>'.PHP_EOL;
        $this->_content .= $return;
    }
    
    public function getHeader(){
        $return = '<html>'.PHP_EOL;
        $return .= '<head>'.PHP_EOL;
        $return .= '<title></title>'.PHP_EOL;
        $return .= '<meta charset="UTF-8">'.PHP_EOL;
        $return .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.PHP_EOL;
        $return .= '<link rel="stylesheet" href="style.css" />'.PHP_EOL;
        $return .= '</head>'.PHP_EOL;
        $return .= '<body>'.PHP_EOL;
        $this->_content = $return;
    }
    
    public function loadCss(){
        
    }
    
    public function loadJs($script){
        $return = '<script src="'.$script.'"></script>'.PHP_EOL;
        $this->_content .= $return;
    }
    
    public function display(){
        echo $this->_content;
        $this->_content = '';
    }
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
}

class Articles {

    private $path;
    private $maxItems;
    private $currentPage;
    private $currentIndex;
    private $currentEntry;

    public function __construct($path = true, $maxItems = null) {
        if ($path === true) {
            $this->path = "contents/*.txt";
        } else {
            $this->path = "contents/" . $path;
        }

        $this->maxItems = $maxItems;
        $this->currentPage = 1;
        $this->currentIndex = false;
        $this->currentEntry = false;
    }

    private function getData() {
        foreach (glob($this->path) as $filename) {
            $index = explode(".", $filename);
            $index = $index[0];

            $lines = file($filename);
            $lines = array_values(array_filter($lines, "trim"));

            foreach ($lines as $line_num => $line) {
                $data[$index][$line_num] = htmlspecialchars($line);
            }
        }

        return $data;
    }

    private function getIndexes() {
        foreach (glob($this->path) as $filename) {
            $index = explode(".", basename($filename));
            $index = $index[0];

            $indexes[] = $index;
        }

        return $indexes;
    }

    private function countItems() {
        $i = 0;
<<<<<<< HEAD
        if ($this->currentIndex){
            foreach (glob($this->path.'/'.$this->currentIndex.'.txt') as $filename) {
                $lines = file($filename);
                $lines = array_values(array_filter($lines, "trim"));
                foreach ($lines as $line) {
                    $i++;
                }
=======
        foreach (glob($this->path) as $filename) {
            $lines = file($filename);
            $lines = array_values(array_filter($lines, "trim"));
            foreach ($lines as $line) {
                $i++;
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
            }
        }else{
            foreach (glob($this->path) as $filename) {
                $lines = file($filename);
                $lines = array_values(array_filter($lines, "trim"));
                foreach ($lines as $line) {
                    $i++;
                }
            } 
        }

        return $i;
    }

    private function replace($item, $clean = false) {
        if ($clean) {
            $markdown = array("{", "}");
            $return = str_replace($markdown, "", $item);
        } else {
            $markdown = array("{", "}");
            $return = str_replace($markdown, "", $item);
        }

        return $return;
    }

    private function itemLink($item) {
        $item = explode("{", $item);
        $item = explode("}", $item[1]);

        return $item[0];
    }

<<<<<<< HEAD
    public function setCurrentPage($page) {
        $this->currentPage = $page;
    }

    public function setCurrentIndex($index) {
        $this->currentIndex = $index;
    }

    public function setCurrentEntry($entry) {
        $this->currentEntry = $entry;
    }

=======
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
    public function getContent() {
        return $this->content;
    }

<<<<<<< HEAD
    public function displayPage($page = 1, $maxItems = null) {
=======
    public function displayArticle($title) {
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
        $data = $this->getData();
        $return = '';
        $i = 0;
        if ($maxItems == null) {
            $maxItems = $this->maxItems;
        }

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                $items[] = $item;
            }
        }

        $items = new LimitIterator(new ArrayIterator($items), ($page * $maxItems) - $maxItems, $maxItems * $page);

        foreach ($items as $item) {
            $return .= '<p>' . substr($this->replace($item, true), 0, 100) . '...</p>';
            $return .= '<p><a href="http://' . $_SERVER['SERVER_NAME'] . '/app/infernal/entry/' . $this->sanitize($this->itemLink($item)) . '">Lire la suite</a></p>';
        }

<<<<<<< HEAD
        return $return;
    }

    public function getEntry() {
        $data = $this->getData();
        $return = '';
        if ($this->currentEntry){
            foreach ($data as $parts) {
                foreach ($parts as $item) {
                    if ($this->sanitize($this->itemLink($item)) == $this->currentEntry) {
                        $return .= '<p>' . $this->replace($item) . '</p>';
                    }
=======
        foreach ($data as $parts) {
            foreach ($parts as $item) {
                if ($this->sanitize($this->itemLink($item)) == $title) {
                    $return .= '<p>' . $this->replace($item) . '</p>';
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
                }
            }
        }

        return $return;
    }

<<<<<<< HEAD
    public function getEntries($maxItems = null) {
=======
    public function displayPage($page = 1, $maxItems = null) {
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
        $data = $this->getData();
        $return = '';
        $i = 0;
        if ($maxItems == null) {
            $maxItems = $this->maxItems;
        }
<<<<<<< HEAD
        if ($this->currentIndex){
            $items = $data['contents/' . $this->currentIndex];
        }else{
            foreach ($data as $parts) {
                foreach ($parts as $item) {
                    $items[] = $item;
                }
            }
        }

        $items = new LimitIterator(new ArrayIterator($items), ($this->currentPage * $maxItems) - $maxItems, $maxItems * $this->currentPage);
=======

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                $items[] = $item;
            }
        }

        $items = new LimitIterator(new ArrayIterator($items), ($page * $maxItems) - $maxItems, $maxItems * $page);
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f

        foreach ($items as $item) {
            $return .= '<p>' . substr($this->replace($item, true), 0, 100) . '...</p>';
            $return .= '<p><a href="http://' . $_SERVER['SERVER_NAME'] . '/app/infernal/entry/' . $this->sanitize($this->itemLink($item)) . '">Lire la suite</a></p>';
        }

        return $return;
    }

<<<<<<< HEAD
=======
    public function displayIndex($index) {
        $data = $this->getData();
        $data = $data['contents/' . $index];
        $return = "";

        foreach ($data as $item) {
            $return .= '<p>' . substr($this->replace($item, true), 0, 100) . '...</p>';
            $return .= '<p><a href="entry/' . strtolower($this->itemLink($item)) . '">Lire la suite</a></p>';
        }

        return $return;
    }

>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
    public function pagination($maxItems = null) {
        if ($maxItems == null) {
            $maxItems = $this->maxItems;
        }

        $items = $this->countItems();
        if (($items % $maxItems) == 0) {
            $pages = $items / $maxItems;
        } else {
            $pages = $items % $maxItems;
        }
        $return = '<ul>';
        for ($i = 0; $i < $pages; $i++) {
            $return .= '<li><a href="http://' . $_SERVER['SERVER_NAME'] . '/app/infernal/page/' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
        }
        $return .= '</ul>';

        return $return;
    }

    public function menu() {
        $items = $this->getIndexes();

        $return = '<ul>';
        foreach ($items as $item) {
            $return .= '<li><a href="./' . $item . '.html">' . $item . '</a></li>';
        }
        $return .= '</ul>';

        return $return;
    }

    public function get_preventry($entry) {
        $data = $this->getData();
        $return = '';
        $i = 0;

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                $items[$i] = $this->sanitize($this->itemLink($item));
                $i++;
            }
        }
        $key = array_search($entry, $items);
        if ($key > 0) {
            $return .= '<p><a href="'.$items[$key - 1].'">précédent</a></p>';
        } else {
            $return .= '<p></p>';
        }
        return $return;
    }

    public function get_nextentry($entry) {
        $data = $this->getData();
        $return = '';
        $i=0;

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                $items[$i] = $this->sanitize($this->itemLink($item));
                $i++;
            }
        }
        $maxItems = count($items);
        $key = array_search($entry, $items);
        if (($key + 1) < $maxItems) {
            $return .= '<p><a href="'.$items[$key + 1].'">suivant</a></p>';
        } else {
            $return .= '<p></p>';
        }
        return $return;
    }

    private function sanitize($texte) {
        $texte = mb_strtolower($texte, 'UTF-8');
        $texte = str_replace(
<<<<<<< HEAD
            array(
                'à', 'â', 'ä', 'á', 'ã', 'å',
                'î', 'ï', 'ì', 'í',
                'ô', 'ö', 'ò', 'ó', 'õ', 'ø',
                'ù', 'û', 'ü', 'ú',
                'é', 'è', 'ê', 'ë',
                'ç', 'ÿ', 'ñ',
            ), array(
                'a', 'a', 'a', 'a', 'a', 'a',
                'i', 'i', 'i', 'i',
                'o', 'o', 'o', 'o', 'o', 'o',
                'u', 'u', 'u', 'u',
                'e', 'e', 'e', 'e',
                'c', 'y', 'n',
            ), $texte
=======
                array(
            'à', 'â', 'ä', 'á', 'ã', 'å',
            'î', 'ï', 'ì', 'í',
            'ô', 'ö', 'ò', 'ó', 'õ', 'ø',
            'ù', 'û', 'ü', 'ú',
            'é', 'è', 'ê', 'ë',
            'ç', 'ÿ', 'ñ',
                ), array(
            'a', 'a', 'a', 'a', 'a', 'a',
            'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u',
            'e', 'e', 'e', 'e',
            'c', 'y', 'n',
                ), $texte
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
        );

        return $texte;
    }
<<<<<<< HEAD

=======
    
>>>>>>> bd7ff7642dd0bcb64437354d475bada611a0078f
    public function getEntryTitle(){
        $data = $this->getData();
        $i=0;

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                $items[$i] = $this->itemLink($item).'#'.$this->sanitize($this->itemLink($item));
                $i++;
            }
        }
        return $items;
    }

}

?>