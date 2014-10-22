<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cms\Models;

use DSLive\Models\Model;

/**
 * Description of LinksMeta
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class LinksMeta extends Model {

    /**
     * @DBS\String (size=160)
     */
    protected $categoryName;

    /**
     * @DBS\String (size=220)
     */
    protected $categoryLink;

    /**
     * @DBS\String (size=160)
     */
    protected $pageTitle;

    /**
     * @DBS\String (size=220)
     */
    protected $pageLink;

    /**
     * @DBS\Int
     */
    protected $position;

    /**
     * @DBS\Boolean (default=false, nullable=true)
     */
    protected $visible;

    public function getCategoryName() {
        return $this->categoryName;
    }

    public function getCategoryLink() {
        return $this->categoryLink;
    }

    public function getPageTitle() {
        return $this->pageTitle;
    }

    public function getPageLink() {
        return $this->pageLink;
    }

    public function getPosition() {
        return $this->position;
    }

    public function getVisible() {
        return $this->visible;
    }

    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
        return $this;
    }

    public function setCategoryLink($categoryLink) {
        $this->categoryLink = $categoryLink;
        return $this;
    }

    public function setPageTitle($pageTitle) {
        $this->pageTitle = $pageTitle;
        return $this;
    }

    public function setPageLink($pageLink) {
        $this->pageLink = $pageLink;
        return $this;
    }

    public function setPosition($position) {
        $this->position = $position;
        return $this;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
        return $this;
    }

}
