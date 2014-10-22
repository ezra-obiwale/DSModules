<?php

namespace Cms\Models;

use DSLive\Models\Model;

class Slide extends Model {

    /**
     * @DBS\String (size="220", unique=true)
     */
    protected $codeName;

    /**
     * @DBS\ReferenceMany (model="Media")
     */
    protected $media;

    /**
     * @DBS\String (size=6, nullable=true)
     */
    protected $width;

    /**
     * @DBS\String (size=6, nullable=true)
     */
    protected $height;

    public function setCodeName($codeName) {
        $this->codeName = $codeName;
        return $this;
    }

    public function getCodeName() {
        return $this->codeName;
    }

    public function setMedia($media) {
        $this->media = $media;
        return $this;
    }

    public function getMedia() {
        return $this->media;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

}
