<?php

namespace Cms\Models;

use DSLive\Models\Model;

class Article extends Model {

    /**
     * @DBS\Reference (model="User", onUpdate="no action", onDelete="cascade")
     */
    protected $author;

    /**
     * @DBS\String (size="160", default="Default", nullable=true)
     */
    protected $category;

    /**
     * @DBS\String (size="160")
     */
    protected $title;

    /**
     * @DBS\String (size=200)
     */
    protected $description;

    /**
     * @DBS\String (size="220")
     */
    protected $link;

    /**
     * @DBS\String
     */
    protected $content;

    /**
     * @DBS\Date
     */
    protected $dateCreated;

    /**
     * @DBS\Date
     */
    protected $dateModified;

    /**
     * @DBS\Boolean
     */
    protected $status;

    /**
     * @DBS\Boolean
     */
    protected $allowComments;

    /**
     * @DBS\Int (default=0, nullable=true)
     */
    protected $viewCount;

    public function getAuthor() {
        return $this->author;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
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

    public function getDateModified() {
        return $this->dateModified;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getViewCount() {
        return $this->viewCount;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    public function setDateModified($dateModified) {
        $this->dateModified = $dateModified;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getAllowComments() {
        return $this->allowComments;
    }

    public function setAllowComments($allowComments) {
        $this->allowComments = $allowComments;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }

    public function setViewCount($viewCount) {
        $this->viewCount = $viewCount;
    }

    public function increaseViewCount() {
        $this->viewCount++;
    }

    public function getCategoryLink() {
        return str_replace(' ', '-', strtolower($this->category));
    }

    public function preSave() {
        if ($this->dateCreated === null) {
            $this->dateCreated = \DBScribe\Util::createTimestamp();
        }

        $this->dateModified = \DBScribe\Util::createTimestamp();

        if ($this->viewCount === NULL)
            $this->viewCount = 0;

        parent::preSave();
    }

}
