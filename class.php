<?php

class Infernal {

    private $_content;
    private $_js;
    private $param;

    public function __construct() {
        $_content = '';
        $_js = '';
    }

    public function getFooter() {
        $return = file_get_contents('templates/footer.html', FILE_USE_INCLUDE_PATH);
        $return = str_replace("%%%JS%%%", $this->_js, $return);
        $this->_content .= $return;
    }

    public function getHeader() {
        $this->_content = file_get_contents('templates/header.html', FILE_USE_INCLUDE_PATH);
    }

    public function getTemplatePart($part, $articles) {
        include('templates/' . $part . '.html');
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
        $return = '';
        foreach ($lines as $line) {
            if (strpos($line, $param) !== false) {
                $line = explode(':',$line);
                $line = $line[1];
                $return = str_replace('"', "", $line);
                break;
            }
        }
        echo $return;
    }

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
        if ($this->currentIndex) {
            foreach (glob($this->path . '/' . $this->currentIndex . '.txt') as $filename) {
                $lines = file($filename);
                $lines = array_values(array_filter($lines, "trim"));
                foreach ($lines as $line) {
                    $i++;
                }
            }
        } else {
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
            $return = preg_replace("/\{{[^}}]+\}}/","",$item);
            $markdown = array("{", "}");
            $return = str_replace($markdown, "", $return);
        } else {
            $markdown2 = array("{{", "}}");
            $replace = array('<img src="http://wilfryed.com/app/infernal/contents/uploads/' . $this->sanitize($this->itemLink($item)) . '_', '.jpg" alt="">');
            $return = str_replace($markdown2, $replace, $item);
            $markdown = array("{", "}");
            $return = str_replace($markdown, "", $return);
        }

        return $return;
    }

    private function itemLink($item) {
        $item = explode("{", $item);
        $item = explode("}", $item[1]);

        return $item[0];
    }

    public function setCurrentPage($page) {
        $this->currentPage = $page;
    }

    public function setCurrentIndex($index) {
        $this->currentIndex = $index;
    }

    public function setCurrentEntry($entry) {
        $this->currentEntry = $entry;
    }

    public function getContent() {
        return $this->content;
    }

    public function displayPage($page = 1, $maxItems = null) {
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

        return $return;
    }

    public function getEntry() {
        $data = $this->getData();
        $return = '';
        if ($this->currentEntry) {
            foreach ($data as $parts) {
                foreach ($parts as $item) {
                    if ($this->sanitize($this->itemLink($item)) == $this->currentEntry) {
                        $return .= '<p>' . $this->replace($item) . '</p>';
                    }
                }
            }
        }

        return $return;
    }

    public function getEntries($maxItems = null) {
        $data = $this->getData();
        $return = '';
        $i = 0;
        if ($maxItems == null) {
            $maxItems = $this->maxItems;
        }
        if ($this->currentIndex) {
            $items = $data['contents/' . $this->currentIndex];
        } else {
            foreach ($data as $parts) {
                foreach ($parts as $item) {
                    $items[] = $item;
                }
            }
        }

        $items = new LimitIterator(new ArrayIterator($items), ($this->currentPage * $maxItems) - $maxItems, $maxItems * $this->currentPage);

        foreach ($items as $item) {
            $return .= '<p>' . substr($this->replace($item, true), 0, 100) . '...</p>';
            $return .= '<p><a href="http://' . $_SERVER['SERVER_NAME'] . '/app/infernal/entry/' . $this->sanitize($this->itemLink($item)) . '">Lire la suite</a></p>';
        }

        return $return;
    }

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
            $return .= '<p><a href="' . $items[$key - 1] . '">précédent</a></p>';
        } else {
            $return .= '<p></p>';
        }
        return $return;
    }

    public function get_nextentry($entry) {
        $data = $this->getData();
        $return = '';
        $i = 0;

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                $items[$i] = $this->sanitize($this->itemLink($item));
                $i++;
            }
        }
        $maxItems = count($items);
        $key = array_search($entry, $items);
        if (($key + 1) < $maxItems) {
            $return .= '<p><a href="' . $items[$key + 1] . '">suivant</a></p>';
        } else {
            $return .= '<p></p>';
        }
        return $return;
    }

    private function sanitize($texte) {
        $texte = mb_strtolower($texte, 'UTF-8');
        $texte = str_replace(
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
        );

        return $texte;
    }

    public function getEntryTitle() {
        $data = $this->getData();
        $i = 0;

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                $items[$i] = $this->itemLink($item) . '#' . $this->sanitize($this->itemLink($item));
                $i++;
            }
        }
        return $items;
    }

    public function randomEntry() {
        $data = $this->getData();
        $i = 0;

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                if (isset($_SESSION['count']) && ($_SESSION['count'] != "")) {
                    if ($_SESSION['count'] != $this->sanitize($this->itemLink($item))) {
                        $items[$i] = $this->sanitize($this->itemLink($item));
                    }
                } else {
                    $items[$i] = $this->sanitize($this->itemLink($item));
                }
                $i++;
            }
        }

        $item = $items[rand(0, count($items) - 1)];
        $_SESSION['random'] = $item;
        return '<a href="entry/' . $item . '">random</a>';
    }

}

?>