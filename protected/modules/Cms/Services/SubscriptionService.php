<?php

namespace Cms\Services;

use DSLive\Services\SuperService;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubscriptionService
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class SubscriptionService extends SuperService {

    public function add($data) {
        $form = new \Cms\Forms\SubscriptionForm();
        $form->setData($data);
        return ($form->isValid() && $this->upsert($form->getModel(), 'email'));
    }

}
