<?php

/**
 * Vault content handler for loading, listing, and rendering text entries.
 */
class Vault
{

    private $path;
    private $maxItems;
    private $content;
    private $currentPage;
    private $currentIndex;
    private $currentEntry;
    private $markdown;

    public function __construct($markdown,$path = true, $maxItems = null)
    {

        $this->markdown = $markdown;
        
        if ($path === true) {
            $this->path = "contents";
        } else {
            $this->path = "contents/" . $path;
        }

        $this->maxItems = $maxItems;
        $this->currentPage = 1;
        $this->currentIndex = false;
        $this->currentEntry = false;
    }

    private function getData()
    {
        $data = [];

        foreach ($this->getFiles() as $filename) {

            $data[] = [
                'slug' => pathinfo($filename, PATHINFO_FILENAME),
                'content' => file_get_contents($filename),
                'file' => $filename
            ];
        }

        return $data;
    }

    private function getFiles()
    {
        $files = [];

        $files = array_merge(
            $files,
            glob($this->path . '/*.txt') ?: []
        );

        $files = array_merge(
            $files,
            glob($this->path . '/*.md') ?: []
        );

        return $files;
    }

    private function getExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    private function getIndexes()
    {
        $indexes = array();

        foreach (glob($this->path) as $filename) {
            $index = explode(".", basename($filename));
            $index = $index[0];

            $indexes[] = $index;
        }

        return $indexes;
    }

    private function countItems()
    {
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
            foreach ($this->getFiles() as $filename) {
                $lines = file($filename);
                $lines = array_values(array_filter($lines, "trim"));
                foreach ($lines as $line) {
                    $i++;
                }
            }
        }

        return $i;
    }

    private function replace(string $item, bool $clean = false): string
    {
        if ($clean) {
            $return = preg_replace("/\{{[^}}]+\}}/", "", $item);
            $markdown = array("{", "}");
            $return = str_replace($markdown, "", $return);
        } else {
            $markdown2 = array("{{", "}}");
            $replace = array('<img src="/contents/uploads/' . $this->sanitize($this->itemLink($item)) . '_', '.jpg" alt="">');
            $return = str_replace($markdown2, $replace, $item);
            $markdown = array("{", "}");
            $return = str_replace($markdown, "", $return);
        }

        return $return;
    }

    private function itemLink(string $item): string
    {
        $item = explode("{", $item ?? '');
        $item = explode("}", $item[1] ?? '');

        return $item[0];
    }

    public function setCurrentPage(int $page)
    {
        $this->currentPage = $page;
    }

    public function setCurrentIndex(string $index)
    {
        $this->currentIndex = $index;
    }

    public function setCurrentEntry(string $entry)
    {
        $this->currentEntry = $entry;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function haveEntries()
    {
        if ($this->countItems() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function displayPage($page = 1, $maxItems = null)
    {
        $items = array();
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
            $return .= '<p><a href="http://' . BASE_URL . '/app/infernal/entry/' . $this->sanitize($this->itemLink($item)) . '">Lire la suite</a></p>';
        }

        return $return;
    }

    public function getEntry()
    {
        $data = $this->getData();
        $return = '';

        if ($this->currentEntry) {

            foreach ($data as $item) {

                if ($item['slug'] === $this->currentEntry) {

                    $return .= '<p>';
                    $return .= $this->markdown->parse($item['content']);
                    $return .= '</p>';

                    break;
                }
            }
        }

        return $return;
    }

    public function getEntries($maxItems = null)
    {
        $entries = $this->getData();

        if ($maxItems == null) {
            $maxItems = $this->maxItems;
        }

        $offset = ($this->currentPage - 1) * $maxItems;

        $entries = array_slice(
            $entries,
            $offset,
            $maxItems
        );

        $return = '';

        foreach ($entries as $entry) {

            $return .= '<p>';
            $return .= substr(
                $this->replace($entry['content'], true),
                0,
                100
            );
            $return .= '...</p>';

            $return .= '<p>';
            $return .= '<a href="' . BASE_URL . '/entry/' . $entry['slug'] . '">';
            $return .= 'Lire la suite';
            $return .= '</a>';
            $return .= '</p>';
        }

        return $return;
    }

    public function pagination($maxItems = null)
    {
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
            $return .= '<li><a href="' . BASE_URL . '/page/' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
        }
        $return .= '</ul>';

        return $return;
    }

    public function menu()
    {
        $items = $this->getIndexes();

        $return = '<ul>';

        foreach ($items as $item) {

            $return .= '<li>';
            $return .= '<a href="' . BASE_URL . '/contents/' . $item . '">';
            $return .= $item;
            $return .= '</a>';
            $return .= '</li>';
        }

        $return .= '</ul>';

        return $return;
    }

    public function get_preventry(string $entry)
    {
        $items = array();
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

    public function get_nextentry(string $entry)
    {
        $items = array();
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

    private function sanitize(string $texte): string
    {
        $texte = mb_strtolower($texte, 'UTF-8');
        $texte = str_replace(
            array(
                'à',
                'â',
                'ä',
                'á',
                'ã',
                'å',
                'î',
                'ï',
                'ì',
                'í',
                'ô',
                'ö',
                'ò',
                'ó',
                'õ',
                'ø',
                'ù',
                'û',
                'ü',
                'ú',
                'é',
                'è',
                'ê',
                'ë',
                'ç',
                'ÿ',
                'ñ',
            ),
            array(
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'i',
                'i',
                'i',
                'i',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'u',
                'u',
                'u',
                'u',
                'e',
                'e',
                'e',
                'e',
                'c',
                'y',
                'n',
            ),
            $texte
        );

        return $texte;
    }

    public function getEntryTitle()
    {
        $items = array();
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

    public function randomEntry()
    {
        $items = array();
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
