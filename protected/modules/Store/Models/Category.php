<?php

/*
 * The MIT License
 *
 * Copyright 2014 Ezra Obiwale <contact@ezraobiwale.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Store\Models;

use DSLive\Models\File;

/**
 * Description of Category
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class Category extends File {

    /**
     * @DBS\String (size=120, unique=true)
     */
    protected $name;

    /**
     * @DBS\String (size=200, unique=true)
     */
    protected $link;

    /**
     * @DBS\String (size=500, nullable=true)
     */
    protected $description;

    /**
     * @DBS\String (nullable=true)
     */
    protected $image;

    /**
     * @DBS\Boolean (default=true)
     */
    protected $status;

    public function __construct() {
        $this->setTableName('item_category');
        $this->setExtensions('image', array('png', 'jpg', 'gif'));
        $this->setAltNameProperty('image', 'name');
        $this->withThumbnails('image', 120);
        $this->setOverwrite(true);
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

    public function getDescription() {
        return $this->description;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getImage($pathOnly = false) {
        if ($pathOnly && $this->image) {
            $image = array_values($this->image);
            return $image[0];
        }
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    // ---------------------------------------------

    public function preSave($createId = true) {
        $this->link = preg_replace('/[^a-z0-9-]/', '-', strtolower($this->name));
        parent::preSave($createId);
    }

}
