<?php

namespace Store\Controllers;

use DSLive\Controllers\SuperController,
    Store\Services\ItemService;

class ItemController extends SuperController {

    /**
     * @var ItemService         
     */
    protected $service;

    public function accessRules() {
        return array(
            array('allow', array(
                    'role' => array('admin', 'guest')
                )),
            array('allow', array(
                    'role' => 'guest',
                    'actions' => array('view')
                )),
            array('deny'),
        );
    }

    public function newAction($categoryLink) {
        $category = $this->service->getCategoryService()->findOneBy('link', $categoryLink);
        if ($this->request->isPost())
            $this->request->getPost()->category = $category->getId();
        return parent::newAction(array(
                    'controller' => 'category',
                    'action' => 'items',
                    'params' => array($categoryLink),
                ))->variables(array(
                    'category' => $category,
        ));
    }

    public function editAction($categoryLink, $itemLink) {
        $category = $this->service->getCategoryService()->findOneBy('link', $categoryLink);
        $item = $this->service->findOneWhere(array(array(
                'category' => $categoryLink,
                'link' => $itemLink,
        )));
        return parent::editAction($item, array(
                    'controller' => 'category',
                    'action' => 'items',
                    'params' => array($categoryLink),
                ))->variables(array(
                    'category' => $category,
        ));
    }

    public function viewAction($categoryLink, $itemLink) {
        $model = $this->service->findOneWhere(array(array(
                'category' => $categoryLink,
                'link' => $itemLink,
        )));
        return parent::viewAction($model);
    }

    public function deleteAction($categoryLink, $itemLink, $confirm = null) {
        $model = $this->service->findOneWhere(array(array(
                'category' => $categoryLink,
                'link' => $itemLink,
        )));
        return parent::deleteAction($model, $confirm, array(
                    'controller' => 'category',
                    'action' => 'items',
                    'params' => array($categoryLink),
        ));
    }

}
