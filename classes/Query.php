<?php

class Query
{
    private Vault $vault;
    private ?int $limit = null;
    private string $orderBy = 'date';
    private string $direction = 'desc';

    public function __construct(Vault $vault)
    {
        $this->vault = $vault;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
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

            if ($entry->getSlug() === $current->getSlug()) {

                return $entries[$i - 1] ?? null;
            }
        }

        return null;
    }

    public function next(Entry $current): ?Entry
    {
        $entries = $this->getSortedEntries();

        foreach ($entries as $i => $entry) {

            if ($entry->getSlug() === $current->getSlug()) {

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
        $entries = $this->vault->getAllEntries();

        usort($entries, function ($a, $b) {

            $getter = 'get' . ucfirst($this->orderBy);

            $valueA = method_exists($a, $getter)
                ? $a->$getter()
                : '';

            $valueB = method_exists($b, $getter)
                ? $b->$getter()
                : '';

            $compare = $valueA <=> $valueB;

            return $this->direction === 'desc'
                ? -$compare
                : $compare;
        });

        return $entries;
    }
}
