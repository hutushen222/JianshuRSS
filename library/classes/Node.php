<?php

namespace JianshuRss;

abstract class Node
{
    protected $title;
    protected $link;
    protected $description;

    public function __construct($title = null, $link = null, $description = null)
    {
        $this->title = $title ?? '';
        $this->link = $link ?? '';
        $this->description = $description ?? '';
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

}
