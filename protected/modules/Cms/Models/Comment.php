<?php

namespace Cms\Models;

use DSLive\Models\Model;

class Comment extends Model {

    /**
     * @DBS\Reference (model="Comment", onUpdate="no action", onDelete="cascade", nullable=true)
     */
    protected $parent;

    /**
     * @DBS\Reference (model="User", onUpdate="no action", onDelete="cascade")
     */
    protected $user;

    /**
     * @DBS\Reference (model="Page", onUpdate="no action", onDelete="cascade", nullable=true)
      ) */
    protected $page;

    /**
     * @DBS\Reference (model="Article", onUpdate="no action", onDelete="cascade", nullable=true)
      ) */
    protected $article;

    /**
     * @DBS\String (size="250")
     */
    protected $content;

    /**
     * @DBS\Int (nullable=true, default=0)
     */
    protected $upVotes;

    /**
     * @DBS\Int (nullable=true, default=0)
     */
    protected $downVotes;

    /**
     * @DBS\Timestamp
     */
    protected $dateModified;

    /**
     * @DBS\Timestamp
     */
    protected $dateCreated;
    private $oldContent;

    public function getParent() {
        return $this->parent;
    }

    public function getUser() {
        return $this->user;
    }

    public function getContent() {
        return $this->content;
    }

    public function getUpVotes() {
        return (!$this->upVotes) ? 0 : $this->upVotes;
    }

    public function getDownVotes() {
        return (!$this->downVotes) ? 0 : $this->downVotes;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getDateModified() {
        return $this->dateModified;
    }

    public function setParent($parent) {
        $this->parent = $parent;
        return $this;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function setContent($content) {
        $this->oldContent = $this->content;
        $this->content = $content;
        return $this;
    }

    public function setUpVotes($upVotes) {
        $this->upVotes = $upVotes;
        return $this;
    }

    public function setDownVotes($downVotes) {
        $this->downVotes = $downVotes;
        return $this;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function setDateModified($dateModified) {
        $this->dateModified = $dateModified;
        return $this;
    }

    public function getPage() {
        return $this->page;
    }

    public function getArticle() {
        return $this->article;
    }

    public function setPage($page) {
        $this->page = $page;
        return $this;
    }

    public function setArticle($article) {
        $this->article = $article;
        return $this;
    }

    public function voteUp($inc = true) {
        if ($inc)
            $this->upVotes++;
        else
            $this->upVotes--;
    }

    public function voteDown($inc = true) {
        if ($inc)
            $this->downVotes++;
        else
            $this->downVotes--;
    }

    public function preSave() {
        if ($this->dateCreated === null) {
            $this->dateCreated = \DBScribe\Util::createTimestamp();
        }

        if ($this->oldContent !== $this->newContent)
            $this->dateModified = \DBScribe\Util::createTimestamp();

        parent::preSave();
    }

}
