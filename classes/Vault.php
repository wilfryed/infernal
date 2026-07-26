<?php

/**
 * Vault content handler.
 *
 * Loads content files and transforms them into Entry objects.
 */
class Vault
{
    private string $path;
    private string $urlPath;
    private int $maxItems;
    private int $currentPage;
    private string|false $currentEntry;
    private Markdown $markdown;


    public function __construct(Markdown $markdown, string $path = '')
    {
        $this->markdown = $markdown;

        $this->path = "contents";

        $this->urlPath = str_replace('contents/', '', $path);

        if ($path !== '') {
            $this->path .= "/" . trim($path, "/");
        }

        $this->maxItems = 24;
        $this->currentPage = 1;
        $this->currentEntry = false;
    }

    public function resolve(string $path): array
    {
        $directory = "contents/" . $path;
        $file = "contents/" . $path . ".md";


        if (is_dir($directory)) {
            $configFile = $directory . '/_config.md';

            return [
                'type' => 'archive',
                'path' => $path,
                'config' => is_file($configFile)
                    ? $configFile
                    : null
            ];
        }

        if (is_file($file)) {

            // Est-ce un fichier à la racine ?
            if (substr_count($path, '/') === 0) {

                return [
                    'type' => 'page',
                    'file' => $file,
                    'path' => ''
                ];
            }

            return [
                'type' => 'single',
                'file' => $file,
                'path' => dirname($path)
            ];
        }


        return [
            'type' => '404'
        ];
    }

    /**
     * Return all entries.
     */
    private function getData(): array
    {
        $entries = [];

        foreach ($this->getFiles() as $filename) {

            $entries[] = $this->createEntry($filename);
        }

        return $entries;
    }

    public function load(string $filename): Entry
    {
        return $this->createEntry($filename);
    }

    public function loadConfig(string $filename): array
    {
        return $this->getCollectionConfig($filename);
    }

    /**
     * Create an Entry object from a file.
     */
    private function createEntry(string $filename): Entry
    {
        $content = file_get_contents($filename);
        $data = $this->parseMarkdownFile($content);

        $data['file'] = $filename;

        return new Entry($data, $this->markdown, $this->urlPath);
    }

    private function getCollectionConfig(string $filename): array
    {
        $content = file_get_contents('contents/' . $filename . '/_config.md');
        $data = $this->parseConfigFile($content);

        return $data;
    }

    private function parseMarkdownFile(string $content): array
    {
        $data = [
            'title' => '',
            'slug' => '',
            'date' => '',
            'categories' => [],
            'tags' => [],
            'content' => ''
        ];

        if (!preg_match('/^---(.*?)---(.*)$/s', $content, $matches)) {
            $data['content'] = $content;
            return $data;
        }


        $metadata = trim($matches[1]);
        $data['content'] = trim($matches[2]);


        foreach (explode("\n", $metadata) as $line) {

            if (!str_contains($line, ':')) {
                continue;
            }


            [$key, $value] = explode(':', $line, 2);

            $key = trim($key);
            $value = trim($value);


            // Tableau simple : ["a", "b"]
            if (
                str_starts_with($value, '[') &&
                str_ends_with($value, ']')
            ) {

                $value = trim($value, '[]');

                if ($value === '') {
                    $value = [];
                } else {

                    $value = array_map(
                        function ($item) {
                            return trim($item, " \"'");
                        },
                        explode(',', $value)
                    );
                }
            } else {

                // Valeur texte
                $value = trim($value, "\"'");
            }


            $data[$key] = $value;
        }


        return $data;
    }

    private function parseConfigFile(string $content): array
    {
        $data = [
            'type' => '',
            'template' => ''
        ];

        if (!preg_match('/^---(.*?)---(.*)$/s', $content, $matches)) {
            $data['content'] = $content;
            return $data;
        }

        $metadata = trim($matches[1]);

        foreach (explode("\n", $metadata) as $line) {

            if (!str_contains($line, ':')) {
                continue;
            }


            [$key, $value] = explode(':', $line, 2);

            $key = trim($key);
            $value = trim($value);


            // Tableau simple : ["a", "b"]
            if (
                str_starts_with($value, '[') &&
                str_ends_with($value, ']')
            ) {

                $value = trim($value, '[]');

                if ($value === '') {
                    $value = [];
                } else {

                    $value = array_map(
                        function ($item) {
                            return trim($item, " \"'");
                        },
                        explode(',', $value)
                    );
                }
            } else {
                // Valeur texte
                $value = trim($value, "\"'");
            }


            $data[$key] = $value;
        }


        return $data;
    }

    /**
     * Find markdown and text files.
     */
    private function getFiles(): array
    {
        return array_merge(
            glob($this->path . '/*.md') ?: [],
            glob($this->path . '/*.txt') ?: []
        );
    }


    public function setCurrentPage(int $page): void
    {
        $this->currentPage = $page;
    }


    public function setCurrentEntry(string $entry): void
    {
        $this->currentEntry = $entry;
    }


    public function haveEntries(): bool
    {
        return count($this->getData()) > 0;
    }


    /**
     * Display list of entries.
     */
    public function getEntries(?int $limit = null): array
    {
        $entries = $this->getData();
        $offset = ($this->currentPage - 1) * $this->maxItems;

        $limit ??= $this->maxItems;

        return array_slice(
            $entries,
            $offset,
            $limit
        );
    }

    public function getAllEntries(): array
    {
        return $this->getData();
    }


    public function renderEntries($maxItems = null): string
    {
        $entries = $this->getData();

        $maxItems ??= $this->maxItems;


        $offset = ($this->currentPage - 1) * $maxItems;


        $entries = array_slice(
            $entries,
            $offset,
            $maxItems
        );


        $return = '';


        foreach ($entries as $entry) {

            $content = $this->markdown->parse(
                $entry->getContent()
            );


            $excerpt = substr(
                strip_tags($content),
                0,
                100
            );


            $return .= '<article>';

            $return .= '<p>';
            $return .= $excerpt . '...';
            $return .= '</p>';

            $return .= '<p>';
            $return .= '<a href="'
                . BASE_URL
                . '/entry/'
                . $entry->getSlug()
                . '">';
            $return .= 'Lire la suite';
            $return .= '</a>';
            $return .= '</p>';

            $return .= '</article>';
        }


        return $return;
    }



    /**
     * Display one entry.
     */
    public function getEntry(): string
    {
        if (!$this->currentEntry) {
            return '';
        }


        foreach ($this->getData() as $entry) {

            if ($entry->getSlug() === $this->currentEntry) {

                return $this->markdown->parse(
                    $entry->getContent()
                );
            }
        }


        return '';
    }

    /**
     * Pagination.
     */
    public function pagination($maxItems = null): string
    {
        $maxItems ??= $this->maxItems;


        $count = count($this->getData());

        $pages = ceil($count / $maxItems);


        if ($pages <= 1) {
            return '';
        }


        $return = '<ul>';


        for ($i = 1; $i <= $pages; $i++) {

            $return .= '<li>';
            $return .= '<a href="'
                . BASE_URL
                . '/page/'
                . $i
                . '">';
            $return .= $i;
            $return .= '</a>';
            $return .= '</li>';
        }


        $return .= '</ul>';


        return $return;
    }
}
