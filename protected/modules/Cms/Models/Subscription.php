<?php

namespace Cms\Models;

use DSLive\Models\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Subscription
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class Subscription extends Model {

    /**
     * @DBS\String (size=300)
     */
    protected $email;

    /**
     * @DBS\Timestamp
     */
    protected $dateCreated;

    public function getEmail() {
        return $this->email;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function preSave() {
        $this->dateCreated = \DBScribe\Util::createTimestamp();
        parent::preSave();
    }

}
