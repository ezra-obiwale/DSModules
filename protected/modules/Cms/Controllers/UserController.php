<?php

namespace Cms\Controllers;

use Cms\Services\UserService,
    DSLive\Controllers\UserController as UC;

class UserController extends UC {

    /**
     *
     * @var UserService
     */
    protected $service;

    public function accessDenied($action, $args = array()) {
        return parent::accessDenied($action, $args, array('module' => 'cms', 'controller' => 'guest'));
    }

    public function init() {
        parent::init();
        $this->guest['module'] = 'cms';
        $this->guest['controller'] = 'guest';
    }

    public function noCache() {
        return true;
    }

    public function editPasswordAction() {
        return parent::editPasswordAction(array(
            'module' => 'cms',
            'controller' => 'guest'
        ));
    }

}
