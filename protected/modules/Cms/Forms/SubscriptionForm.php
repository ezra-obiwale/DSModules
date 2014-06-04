<?php

namespace Cms\Forms;

use DScribe\Form\Form;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubscriptionForm
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class SubscriptionForm extends Form {

    public function __construct() {
        parent::__construct('subscriptionForm');
        $this->setModel(new \Cms\Models\Subscription);

        $this->add(array(
            'name' => 'email',
            'type' => 'email',
        ));
    }

    public function getFilters() {
        return array(
            'email' => array(
                'required' => array(),
                'Email' => array(),
            )
        );
    }

}
