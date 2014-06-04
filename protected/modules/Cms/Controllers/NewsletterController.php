<?php

namespace Cms\Controllers;

class NewsletterController extends SuperController {

    public function accessRules() {
        return array(
            array('allow', array(
                    'role' => 'admin'
                )),
            array('allow', array(
                    'role' => 'guest',
                    'actions' => array('view')
                )),
            array('deny')
        );
    }

    public function newAction() {
        parent::newAction()->variables(array(
            'type' => 'New',
        ))->file('form');
    }

    public function editAction($id) {
        parent::editAction($id)->variables(array(
            'type' => 'Edit',
        ))->file('form');
    }

}
