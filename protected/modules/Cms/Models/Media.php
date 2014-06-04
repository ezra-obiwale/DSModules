<?php

namespace Cms\Models;

use DSLive\Models\File;

class Media extends File {

    /**
     * @DBS\String (size="220")
     */
    protected $name;

    /**
     * @DBS\String
     */
    protected $path;

    /**
     * @DBS\String (size="300", nullable=true)
     */
    protected $description;

    public function __construct() {
        $this->setExtensions('path', array('png', 'jpg', 'bmp', 'jpeg', 'gif'));
        $this->setAltNameProperty('name');
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    public function getPath() {
        return $this->path;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

}
