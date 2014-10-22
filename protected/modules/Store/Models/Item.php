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
 * Description of Item
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class Item extends File {

    /**
     * @DBS\Reference (model=Category, property="link", onDelete="cascade", onUpdate="no action")
     */
    protected $category;

    /**
     * @DBS\String (size=120)
     */
    protected $name;

    /**
     * @DBS\String (size=200)
     */
    protected $link;

    /**
     * @DBS\String (size=120, nullable=true)
     */
    protected $shortDescription;

    /**
     * @DBS\String (nullable=true)
     */
    protected $longDescription;

    /**
     * @DBS\String (nullable=true)
     */
    protected $images;

    /**
     * @DBS\Float
     */
    protected $price;

    /**
     * @DBS\Int (nullable=true)
     */
    protected $inStock;

    /**
     * @DBS\Int (nullable=true)
     */
    protected $tooLowCount;

    /**
     * @DBS\String (size=200)
     */
    protected $tooLowMessage;

    /**
     * @DBS\String (size=300)
     */
    protected $shippingInformation;

    /**
     * @DBS\ReferenceMany (model=PaymentOption, onDelete="cascade", onUpdate="no action")
     */
//    protected $paymentOptions;

    /**
     * @DBS\Int (nullable=true, default=0)
     */
    protected $cartQuantity;

    public function __construct() {
        $this->setExtensions('images', array('png', 'jpg', 'gif'));
        $this->setAltNameProperty('images', 'name');
        $this->withThumbnails('images', 120);
        $this->limitFile('images', 5);
        $this->setOverwrite(false);
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
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

    public function getShortDescription() {
        return $this->shortDescription;
    }

    public function getLongDescription() {
        return $this->longDescription;
    }

    public function getImages() {
        return $this->images;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getInStock() {
        return $this->inStock;
    }

    public function getTooLowCount() {
        return $this->tooLowCount;
    }

    public function getTooLowMessage() {
        return $this->tooLowMessage;
    }

    public function getShippingInformation() {
        return $this->shippingInformation;
    }

    public function getPaymentOptions() {
        return $this->paymentOptions;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setShortDescription($shortDescription) {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    public function setLongDescription($longDescription) {
        $this->longDescription = $longDescription;
        return $this;
    }

    public function setImages($images) {
        $this->images = $images;
        return $this;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function setInStock($inStock) {
        $this->inStock = $inStock;
        return $this;
    }

    public function setTooLowCount($tooLowCount) {
        $this->tooLowCount = $tooLowCount;
        return $this;
    }

    public function setTooLowMessage($tooLowMessage) {
        $this->tooLowMessage = $tooLowMessage;
        return $this;
    }

    public function setShippingInformation($shippingInformation) {
        $this->shippingInformation = $shippingInformation;
        return $this;
    }

    public function setPaymentOptions($paymentOptions) {
        $this->paymentOptions = $paymentOptions;
        return $this;
    }

    public function getCartQuantity() {
        return $this->cartQuantity;
    }

    public function setCartQuantity($cartQuantity) {
        $this->cartQuantity = $cartQuantity;
        return $this;
    }

    // ---------------------------------------------

    public function increaseCartQuantity($by) {
        $this->cartQuantity += $by;
        return $this;
    }

    public function decreaseCartQuantity($by) {
        $this->cartQuantity -= $by;
        return $this;
    }

    public function preSave($createId = true) {
        $this->link = preg_replace('/[^a-z0-9-]/', '-', strtolower($this->name));
        parent::preSave($createId);
    }

}
