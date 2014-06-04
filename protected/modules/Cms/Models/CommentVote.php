<?php

namespace Cms\Models;

use DSLive\Models\Model;

class CommentVote extends Model {

    /**
     * @DBS\Reference (model="Comment", onUpdate="no action", onDelete="cascade")
     */
    protected $comment;

    /**
     * @DBS\Reference (model="User", onUpdate="no action", onDelete="cascade")
     */
    protected $user;

    /**
     * @DBS\Boolean (nullable=true)
     */
    protected $upVote;

    /**
     * @DBS\Boolean (nullable=true)
     */
    protected $downVote;

    /**
     * @DBS\Date
     */
    protected $dateCreated;

    /**
     * @DBS\Date
     */
    protected $dateModified;

    public function getComment() {
        return $this->comment;
    }

    public function getUser() {
        return $this->user;
    }

    public function getUpVote() {
        return $this->upVote;
    }

    public function getDownVote() {
        return $this->downVote;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getDateModified() {
        return $this->dateModified;
    }

    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function setUpVote($upVote) {
        $this->upVote = $upVote;
        return $this;
    }

    public function setDownVote($downVote) {
        $this->downVote = $downVote;
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

    public function preSave() {
        if ($this->dateCreated === null) {
            $this->dateCreated = \DBScribe\Util::createTimestamp();
        }

        $this->dateModified = \DBScribe\Util::createTimestamp();

        parent::preSave();
    }

    public function voteUp() {
        $this->setUpVote(TRUE)->setDownVote(false);
        return $this;
    }

    public function voteDown() {
        $this->setUpVote(false)->setDownVote(true);
        return $this;
    }

}
