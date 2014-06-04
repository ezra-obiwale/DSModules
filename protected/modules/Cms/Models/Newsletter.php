<?php

namespace Cms\Models;

use DSLive\Models\Model;

class Newsletter extends Model {

    /**
     * @DBS\Reference (model="User", onUpdate="no action", onDelete="cascade")
     */
    protected $author;

    /**
     * @DBS\String (size="160")
     */
    protected $title;

    /**
     * @DBS\String (size="220")
     */
    protected $link;

    /**
     * @DBS\String
     */
    protected $content;

    /**
     * @DBS\Timestamp
     */
    protected $dateCreated;

    public function getAuthor() {
        return $this->author;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getLink() {
        return $this->link;
    }

    public function getContent() {
        return $this->content;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setLink($link) {
        $this->link = $link;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function preSave() {
        if ($this->dateCreated === null) {
            $this->dateCreated = \DBScribe\Util::createTimestamp();
        }

        if (!$this->link && $this->title)
            $this->link = preg_replace('/[^a-zA-Z0-9]/', '-', $this->title);

        parent::preSave();
    }

}
