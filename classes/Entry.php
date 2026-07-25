<?php

class Entry
{
    private $title;
    private $slug;
    private $url;
    private $date;
    private $author;
    private $categories;
    private $tags;
    private $thumbnail;
    private $content;
    private Markdown $markdown;

    public function __construct(array $data, Markdown $markdown)
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
        $this->url = $data['slug'] ?? '';
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
