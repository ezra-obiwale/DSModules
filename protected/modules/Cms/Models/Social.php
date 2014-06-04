<?php

namespace Cms\Models;

use DSLive\Models\File;

class Social extends File {

    /**
     * @DBS\String (size="100")
     */
    protected $name;

    /**
     * @DBS\String (size="500")
     */
    protected $link;

    /**
     * @DBS\String
     */
    protected $image;

    public function __construct() {
        $this->setExtensions('image', array('png', 'jpg', 'gif', 'bmp'))
                ->setMaxSize('200kb');
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setLink($link) {
        $this->link = $link;
        return $this;
    }

    public function getLink() {
        return $this->link;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

}
