<?php

/**
 * Represent a content entry in Infernal.
 *
 * An entry can be a page or a post being part of a collection.
 */
class Entry
{
    private $title;
    private $slug;
    private string $path;
    private $url;
    private $date;
    private $author;
    private $categories;
    private $tags;
    private $thumbnail;
    private $content;
    private Markdown $markdown;

    /**
     * Create an entry from data extracted of the Markdown's frontmatter.
     *
     * @param array $data Metadata and content of the file's Markdown
     * @param Markdown $markdown Parser Markdown used for the HTML render
     * @param string $path Entry's collection, if relevant
     */
    public function __construct(array $data, Markdown $markdown, string $path = '')
    {
        $this->markdown = $markdown;

        $this->title = $data['title'] ?? '';
        $this->slug = $data['slug'] ?? '';
        $this->date = $data['date'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->categories = $data['categories'] ?? [];
        $this->tags = $data['tags'] ?? [];
        $this->thumbnail = $data['thumbnail'] ?? '';
        $this->content = $data['content'] ?? '';

        $this->path = $path;
        $this->url = BASE_URL . '/' . $path . '/' . $this->slug;
    }

    public function belongsToCollection(): bool
    {
        return !empty($this->path);
    }

    public function getId(): string
    {
        return $this->path . ':' . $this->slug;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getContent()
    {
        return $this->markdown->parse($this->content);
    }

    public function getFormattedDate(): string
    {
        $date = new DateTime($this->date);

        return $date->format('d/m/Y');
    }

    public function get(string $field)
    {
        return match ($field) {
            'title'      => $this->title,
            'slug'       => $this->slug,
            'date'       => $this->date,
            'author'     => $this->author,
            'thumbnail'  => $this->thumbnail,
            default      => null,
        };
    }
}
