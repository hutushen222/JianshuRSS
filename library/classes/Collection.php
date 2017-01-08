<?php

namespace JianshuRss;

class Collection extends Node
{
    protected $notes = [];

    /**
     * @return array
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * @param array $notes
     *
     * @return Collection
     */
    public function setNotes(array $notes): Collection
    {
        $this->notes = $notes;

        return $this;
    }
}
