<?php

class Query
{
    private array $paths;
    private Markdown $markdown;
    private ?int $limit = null;
    private string $orderBy = 'date';
    private string $direction = 'desc';

    public function __construct(Markdown $markdown, array $paths)
    {
        $this->markdown = $markdown;
        $this->paths = $paths;
    }
    /**
     * 
     * Alias to return the latest published items of a collection.
     *
     * @param int $limit Number of items to return.
     * @return self
     */
    public function latest(int $limit = 10): self
    {
        return $this
            ->orderBy('date', 'desc')
            ->limit($limit);
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function first(): ?Entry
    {
        return $this->get()[0] ?? null;
    }

    public function get(): array
    {
        $entries = $this->getSortedEntries();

        if ($this->limit !== null) {

            $entries = array_slice(
                $entries,
                0,
                $this->limit
            );
        }

        return $entries;
    }

    public function previous(Entry $current): ?Entry
    {
        $entries = $this->getSortedEntries();

        foreach ($entries as $i => $entry) {

            if ($entry->getId() === $current->getId()) {

                return $entries[$i - 1] ?? null;
            }
        }

        return null;
    }

    public function next(Entry $current): ?Entry
    {
        $entries = $this->getSortedEntries();

        foreach ($entries as $i => $entry) {

            if ($entry->getId() === $current->getId()) {

                return $entries[$i + 1] ?? null;
            }
        }

        return null;
    }

    public function orderBy(string $field, string $direction = 'asc'): self
    {
        $this->orderBy = $field;
        $this->direction = strtolower($direction);

        return $this;
    }

    private function getSortedEntries(): array
    {
        $entries = [];

        foreach ($this->paths as $path) {

            $vault = new Vault(
                $this->markdown,
                $path
            );

            $entries = array_merge(
                $entries,
                $vault->getAllEntries()
            );
        }

        usort($entries, function ($a, $b) {

            $valueA = $a->get($this->orderBy);
            $valueB = $b->get($this->orderBy);

            $compare = $valueA <=> $valueB;

            return $this->direction === 'desc'
                ? -$compare
                : $compare;
        });

        return $entries;
    }
}
