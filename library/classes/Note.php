<?php

namespace JianshuRss;

class Note extends Node
{
    protected $author;
    protected $notebook;
    protected $publishedAt;

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author ?? '';
    }

    /**
     * @param mixed $author
     *
     * @return Note
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotebook()
    {
        return $this->notebook ?? '';
    }

    /**
     * @param mixed $notebook
     *
     * @return Note
     */
    public function setNotebook($notebook)
    {
        $this->notebook = $notebook;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @param mixed $publishedAt
     *
     * @return Note
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

}
