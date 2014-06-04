<?php

namespace Cms\Controllers;

use DSLive\Controllers\SuperController as SC;

class SuperController extends SC {

    public function accessDenied($action, $args = array()) {
        return parent::accessDenied($action, $args, array('module' => 'cms', 'controller' => 'guest'));
    }

}
