<?php

namespace Cms\Controllers;

class LinksMetaController extends SuperController {

    /**
     *
     * @var \Cms\Service\LinksMetaService
     */
    protected $service;

    public function noCache() {
        return true;
    }

    public function accessRules() {
        return array(
            array('allow', array(
                    'role' => 'admin'
                )),
            array('allow', array(
                    'role' => 'guest',
                    'actions' => 'get-all'
                )),
            array('deny'),
        );
    }

    public function getAllAction() {
        $this->view->variables(array(
            'links' => $this->service->fetchAll(),
        ));
    }

}
