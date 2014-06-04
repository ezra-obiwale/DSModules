<?php

namespace Cms\Controllers;

use CMS,
    Cms\Forms\CommentForm,
    Cms\Services\PageService,
    DScribe\Core\Cache,
    DScribe\Core\Engine,
    DScribe\View\View,
    DSLive\Stdlib\AjaxResponse,
    Exception;

class PageController extends SuperController {

    /**
     *
     * @var PageService
     */
    protected $service;

    public function accessRules() {
        return array_merge(array(
            array('allow', array(
                    'actions' => array('view', 'cache-all', 'get-events', 'comments'),
                )),
                ), parent::accessRules());
    }

    public function noCache() {
        return true;
    }

    public function indexAction() {
        $this->view->variables(array(
            'categories' => $this->service->getCategoryService()
                    ->getRepository()
                    ->orderBy('position')
                    ->orderBy('name')
                    ->join('page')
                    ->join('category', array(
                        'push' => true,
                    ))
                    ->fetchAll(),
        ));

        return $this->request->isAjax() ?
                $this->view->partial() :
                $this->view;
    }

    public function newAction($categoryId = null) {
        $this->service->getCategoryService();

        $variables = parent::newAction(array(), array(
                    'removeElements' => array('position')
                ))->variables(array(
                    'type' => 'New',
                    'categoryId' => $categoryId,
                ))->file('form')->getVariables();

        if ($categoryId) {
            $form = $variables['form'];
            $form->get('category')->options->default = $categoryId;
        }
    }

    public function editAction($id) {
        $this->service->getCategoryService();

        $model = $this->service->findOne($id);
        $form = $this->service->getForm($model);
        $form->setModel($model);
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid() && $this->service->save($form->getModel(), $this->request->getFiles())) {
                if ($this->request->isAjax()) {
                    $this->ajaxResponse()
                            ->setAction(AjaxResponse::ACTION_WAIT)
                            ->sendJson('Save successful');
                }
                $this->flash()->setSuccessMessage('Save successful');
            }
            else {
                if ($this->request->isAjax()) {
                    $this->ajaxResponse()
                            ->sendJson('Save failed', AjaxResponse::STATUS_FAILURE, AjaxResponse::ACTION_WAIT);
                }
                $this->flash()->setErrorMessage('Save failed');
            }
        }

        $this->view->variables(array(
            'model' => $model,
            'categoryId' => $model->getCategory(),
            'form' => $form,
        ))->variables(array('type' => 'Edit'))->file('form');

        return $this->request->isAjax() ?
                $this->view->partial() :
                $this->view;
    }

    public function viewAction($categoryLink = null, $link = null) {
        // $categoryLink and $link will be null if calling for home page
        if ($categoryLink && $link) {
            $category = $this->service->getCategoryService()->findOneBy('link', $categoryLink);
            $model = $this->service->getRepository()
                    ->join('category')
                    ->findOneWhere(array(array(
                    'category' => $category->getId(),
                    'link' => $link,
                    'status' => 1
                ))
            );
        }
        else { // get home page
            $model = $this->service->getRepository()
                    ->join('category')
                    ->findOneBy('isHomePage', 1);
            if ($model) {
                $category = $model->category()->first();
            }
        }

        $commentForm = new CommentForm();
        if ($this->request->isPost()) {
            $commentForm->setData($this->request->getPost());
            if ($commentForm->isValid() && $this->service->saveComment($model, $commentForm->getModel(), $this->currentUser)) {
                CMS::setCommentStatus('Comment added successfully');
                CMS::setCommentStatusType('success');
            }
            else {
                CMS::setCommentStatus('Add comment failed');
                CMS::setCommentStatusType('error');
            }
        }

        if (!$model)
            throw new Exception('Page not found');

        if (in_array($this->currentUser->getRole(), array('subscriber', 'guest', 'admin'))) {
            $settings = Engine::getConfig('modules', 'Cms', 'settings');
            $this->layout = $settings['template'];
            $this->layout .= ($model->getIsHomePage()) ?
                    Engine::getConfig('modules', 'Cms', 'defaults', 'homeLayout') :
                    Engine::getConfig('modules', 'Cms', 'defaults', 'pageLayout');
            
        }

        CMS::init($this->getCurrentUserFromDB(), $this->service->getRepository(), $this->request, $this->view);
        CMS::setSettings($settings);
        CMS::setPage($model);
        CMS::setPageCategory($category);
        return $this->request->isAjax() ?
                $this->view->partial() :
                $this->view;
    }

    public function copyPageAction($id) {
        $modelCopy = $this->service->copyPage($id);
        if ($modelCopy) {
            $this->flash()->setSuccessMessage('Page copied successfully');
        }
        else {
            $this->flash()->setErrorMessage('Copy page failed');
        }

        $this->redirect('cms', 'page', 'index');
    }

    public function staticsAction() {
//        if ($this->request->isAjax()) {
        $cache = new Cache();
//        echo '<pre>';
        foreach ($this->service->getRepository()->findWhere(array(array('status' => 1))) as $key => $page) {
            $category = $page->category()->first();
            echo $category->getName() . '<br />';
            $view = new View();
            $output = $view->getOutput('cms', 'page', 'view', array($category->getLink(), $page->getLink()), false);
//            echo $output;
//            if ($key)
//                die('end');
//            $cache->save(($alias ? $alias : 'cms') . '/page/view/' . $category->getLink() . '/' . $page->getLink(), $output);
        }
        die('</pre>');
//        }
    }

    public function commentsAction($id) {
        $page = $this->service->getRepository()->join('comment')->findOne($id);
        $this->view->variables(array(
            'page' => $page,
            'ajax' => $this->request->isAjax(),
        ));

        CMS::init($this->getCurrentUserFromDB(), $this->service->getRepository(), $this->request, $this->view);
        CMS::setPage($page);
        CMS::setPageCategory($page->category()->first());

        return ($this->request->isAjax()) ?
                $this->view->partial() :
                $this->view;
    }

}
