<?php

namespace Store\Controllers;

use DSLive\Controllers\SuperController,
    Store\Services\PaymentOptionService;

class PaymentOptionController extends SuperController {

    /**
     * @var PaymentOptionService         
     */
    protected $service;
    
    public function noCache() {
        return true;
    }

    public function accessRules() {
        return array(
            array('allow', array(
                    'role' => array('admin', 'guest')
                )),
            array('deny'),
        );
    }

}
