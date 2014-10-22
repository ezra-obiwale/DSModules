<?php

namespace Store\Controllers;

use DSLive\Controllers\SuperController,
    Store\Services\CategoryService;

class CategoryController extends SuperController {

    /**
     *
     * @var CategoryService
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
            array('allow', array(
                    'role' => 'guest',
                    'actions' => array('index', 'items')
                )),
            array('deny'),
        );
    }

    public function indexAction() {
        return $this->view->variables(array(
                    'models' => $this->service->getRepository()
                            ->orderBy('name')->findBy('status', true),
        ));
    }

    public function itemsAction($categoryLink) {
        $category = $this->service->findOneBy('link', $categoryLink);
        return $this->view->variables(array(
                    'category' => $category,
        ));
    }

}
