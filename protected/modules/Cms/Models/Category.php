<?php

namespace Cms\Models;

use DSLive\Models\Model;

class Category extends Model {

    /**
     * @DBS\Reference (model="Category", onUpdate="no action", onDelete="cascade", nullable=true)
     */
    protected $parent;
    
    /**
     * @DBS\String (nullable=true)
     */
    protected $parentPath;

    /**
     * @DBS\String (size="160", unique=true)
     */
    protected $name;

    /**
     * @DBS\String (size="220", unique=true)
     */
    protected $link;

    /**
     * @DBS\Boolean
     */
    protected $status;

    /**
     * @DBS\Int (nullable=true, default=1)
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
    private $oldPosition;

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
        return $this;
    }

    public function getParentPath() {
        return $this->parentPath;
    }

    public function setParentPath($parentPath) {
        $this->parentPath = $parentPath;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function getLink() {
        return $this->link;
    }

    public function setLink($link) {
        $this->link = $link;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setPosition($position) {
        $this->oldPosition = $this->position;
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

    public function preSave() {
        if ($this->dateCreated === null) {
            $this->dateCreated = \DBScribe\Util::createTimestamp();
        }

        $this->dateModified = \DBScribe\Util::createTimestamp();

        parent::preSave();
    }

    public function getOldPosition() {
        return $this->oldPosition;
    }

}
