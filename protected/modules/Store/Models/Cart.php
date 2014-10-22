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

use Table;

/**
 * Description of Cart
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class Cart {

    /**
     *
     * @var array
     */
    protected $items;

    public function add(Item $item) {
        $this->items[$item->getId()] = $item;
        return $this;
    }

    public function remove(Item $item) {
        unset($this->items[$item->getId()]);
        return $this;
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getPrice() * $item->getCartQuantity();
        }
        return $total;
    }

    public function render() {
        if ($this->items):
            Table::init(array('class' => 'table table-hover table-condensed table-striped'));
            Table::setHeaders(array('Item', 'Quantity'));
            $total = 0;
            foreach ($this->items as $item) {
                Table::newRow();
                Table::addRowData($item->getName() . ' <p><small>' .
                        $item->getShortDescription() . '</small></p>');
                Table::addRowData($item->getCartQuantity());

                $total += $item->getPrice() * $item->getCartQuantity();
            }

            Table::setFooters(array('Total', $total));
            return Table::render();
        endif;
    }

}
