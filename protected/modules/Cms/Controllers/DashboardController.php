<?php

namespace Cms\Controllers;

class DashboardController extends SuperController {

    /**
     *
     * @var \Cms\Services\DashboardService
     */
    protected $service;

    public function accessRules() {
        return array(
            array('allow', array(
                    'role' => 'editor',
                    'actions' => 'editor'
                )),
            array('allow', array(
                    'role' => 'admin',
                    'actions' => 'admin'
                )),
            array('deny'),
        );
    }

    public function noCache() {
        return true;
    }

    public function adminAction() {
        return $this->view->variables(array(
                    'articles' => $this->service->getArticleService()->fetchAll(),
                    'pages' => $this->service->getPageService()->fetchAll(),
                    'users' => $this->service->getUserService()->fetchAll(),
        ));
    }

    public function editorAction() {
        return $this->view->variables(array(
                    'articles' => $this->service->getArticleService()->fetchAll(),
        ));
    }

    public function subscriberAction() {
        
    }

}
