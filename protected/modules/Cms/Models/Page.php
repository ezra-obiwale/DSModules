<?php

namespace Cms\Models;

use DSLive\Models\Model;

class Page extends Model {

    /**
     * @DBS\Reference (model="Category", nullable=true, onUpdate="no action", onDelete="cascade")
     */
    protected $category;

    /**
     * @DBS\String (size="160")
     */
    protected $title;

    /**
     * @DBS\String (size="220")
     */
    protected $link;

    /**
     * @DBS\String (size=200)
     */
    protected $description;

    /**
     * @DBS\String
     */
    protected $content;

    /**
     * @DBS\Boolean
     */
    protected $status;

    /**
     * @DBS\Boolean
     */
    protected $isHomePage;

    /**
     * @DBS\Boolean
     */
    protected $allowComments;

    /**
     * @DBS\Int(default=0, nullable=true)
     */
    protected $position;

    /**
     * @DBS\Date
     */
    protected $dateCreated;

    /**
     * @DBS\Date
     */
    protected $dateModified;

    /**
     * @DBS\Int (default=0)
     */
    protected $viewCount;
    private $oldPosition;

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
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

    public function setLink($link) {
        $this->link = $link;
        return $this;
    }

    public function getLink() {
        return $this->link;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getIsHomePage() {
        return $this->isHomePage;
    }

    public function setIsHomePage($isHomePage) {
        $this->isHomePage = $isHomePage;
        return $this;
    }

    public function setPosition($position) {
        $this->oldPosition = ($this->position) ? $this->position : $position;
        $this->position = $position;
        return $this;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function setDateModified($dateModified) {
        $this->dateModified = $dateModified;
        return $this;
    }

    public function getDateModified() {
        return $this->dateModified;
    }

    public function getViewCount() {
        return $this->viewCount;
    }

    public function setViewCount($viewCount) {
        $this->viewCount = $viewCount;
        return $this;
    }

    public function getAllowComments() {
        return $this->allowComments;
    }

    public function setAllowComments($allowComments) {
        $this->allowComments = $allowComments;
        return $this;
    }

    public function increaseViewCount() {
        $this->viewCount++;
    }

    public function preSave() {
        if ($this->dateCreated === null) {
            $this->dateCreated = \DBScribe\Util::createTimestamp();
        }

        $this->dateModified = \DBScribe\Util::createTimestamp();


        if (empty($this->category))
            $this->category = '-cms-main-';

        if ($this->viewCount === NULL)
            $this->viewCount = 0;

        parent::preSave();
    }

    public function getOldPosition() {
        return $this->oldPosition;
    }

}
