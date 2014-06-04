<?php

namespace Cms\Controllers;

class CategoryController extends SuperController {

    /**
     *
     * @var \Cms\Services\CategoryService
     */
    protected $service;

    public function noCache() {
        return true;
    }

    public function init() {
        parent::init();
        $this->layout = 'admin';
    }

    public function newAction() {
        if (isset($this->request->getPost()->saveAndNewPage)) {
            $id = \DBScribe\Util::createGUID();
            $this->request->getPost()->id = $id;
        }
        return parent::newAction(array(), array(
                    'removeElements' => array('position')
                        ), array(
                    'controller' => 'page',
                    'action' => isset($this->request->getPost()->saveAndNewPage) ? 'new' : 'index',
                    'params' => isset($this->request->getPost()->saveAndNewPage) ? array($id) : array(),
                        )
                )->variables(array(
                    'type' => 'New'
                ))->file('form');
    }

    public function editAction($id) {
        parent::editAction($id, array(), array(), array(
            'controller' => 'page',
            'action' => 'index',
            'hash' => $id,
        ))->variables(array(
            'type' => 'Edit'
        ))->file('form');
    }

    public function formListAction($selectedId) {
        if (!$this->request->isAjax())
            throw new \Exception('Invalid action');

        return $this->view->variables(array(
                    'categories' => $this->service
                            ->getRepository()
                            ->join('category', array('push' => true))
                            ->findWhere(array(array('id' => 'ROOT'))),
                    'selected' => $selectedId,
                ))->partial();
    }

    public function deleteAction($id, $confirm = null) {
        parent::deleteAction($id, $confirm, array(), array(
            'controller' => 'page'
        ));
    }

}
