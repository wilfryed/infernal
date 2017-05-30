<?php

class Articles {

    private $path;
    private $maxItems;

    public function __construct($path = true, $maxItems = null) {
        if ($path === true) {
            $this->path = "contents/*.txt";
        } else {
            $this->path = "contents/" . $path;
        }

        $this->maxItems = $maxItems;
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
        foreach (glob($this->path) as $filename) {
            $lines = file($filename);
            $lines = array_values(array_filter($lines, "trim"));
            foreach ($lines as $line) {
                $i++;
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

    public function getContent() {
        return $this->content;
    }

    public function displayArticle($title) {
        $data = $this->getData();
        $return = '';

        foreach ($data as $parts) {
            foreach ($parts as $item) {
                if (strtolower($this->itemLink($item)) == $title) {
                    $return .= '<p>' . $this->replace($item) . '</p>';
                }
            }
        }

        return $return;
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
            $return .= '<p><a href="entry/' . strtolower($this->itemLink($item)) . '">Lire la suite</a></p>';
        }

        return $return;
    }

    public function displayIndex($index) {
        $data = $this->getData();
        $data = $data['contents/' . $index];
        $return="";

        foreach ($data as $item) {
            $return .= '<p>' . substr($this->replace($item, true), 0, 100) . '...</p>';
            $return .= '<p><a href="entry/' . strtolower($this->itemLink($item)) . '">Lire la suite</a></p>';
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

}

?>