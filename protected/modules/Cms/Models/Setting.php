<?php

namespace Cms\Models;

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
class Setting {

    /**
     * @DBS\String (size=160, default="DScribe")
     */
    protected $siteName;

    /**
     * @DBS\String (size=100, nullable=true)
     */
    protected $template;

    /**
     * @DBS\Boolean (default=0, nullable=true)
     */
    protected $listOnHomePage;

    public function getSiteName() {
        return $this->siteName;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function getListOnHomePage() {
        return $this->listOnHomePage;
    }

    public function setSiteName($siteName) {
        $this->siteName = $siteName;
        return $this;
    }

    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    public function setListOnHomePage($listOnHomePage) {
        $this->listOnHomePage = $listOnHomePage;
        return $this;
    }

}
