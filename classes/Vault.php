<?php

/**
 * Vault content handler.
 *
 * Loads content files and transforms them into Entry objects.
 */
class Vault
{
    private string $path;
    private int $maxItems;
    private int $currentPage;
    private string|false $currentEntry;
    private Markdown $markdown;


    public function __construct(Markdown $markdown, $path = true, $maxItems = 10)
    {
        $this->markdown = $markdown;

        $this->path = $path === true
            ? "contents"
            : "contents/" . $path;

        $this->maxItems = $maxItems;
        $this->currentPage = 1;
        $this->currentEntry = false;
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


    /**
     * Create an Entry object from a file.
     */
    private function createEntry(string $filename): Entry
    {
        $content = file_get_contents($filename);

        $data = $this->parseMarkdownFile($content);

        $data['file'] = $filename;

        return new Entry($data, $this->markdown);
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
    public function getEntries(): array
    {
        $entries = $this->getData();

        $offset = ($this->currentPage - 1) * $this->maxItems;

        return array_slice(
            $entries,
            $offset,
            $this->maxItems
        );
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
