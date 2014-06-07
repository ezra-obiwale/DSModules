<?php

namespace Cms\Controllers;

use CMS,
    DScribe\Core\Engine,
    Cms\Services\ArticleService;

class ArticleController extends SuperController {

    /**
     * @var ArticleService         
     */
    protected $service;

    public function accessRules() {
        return array_merge(array(
            array('allow', array(
                    'actions' => array('view', 'cache-all', 'get-events', 'view-category', 'comments', 'list-categories'),
                )),
                ), parent::accessRules());
    }

    public function noCache() {
        return true;
    }

    public function init() {
        parent::init();
        $this->layout = 'admin';
    }

    public function indexAction() {
        $this->view->variables(array(
            'groupedArticles' => $this->service->parseArticles(),
        ));

        return $this->request->isAjax() ?
                $this->view->partial() :
                $this->view;
    }

    public function getCategoriesAction() {
        $json = new \Json($this->service->getCategories());
        $json->toScreen(true);
    }

    public function newAction() {
        return parent::newAction()->variables(array(
                    'type' => 'New',
                ))->file('form');
    }

    public function editAction($id) {
        return parent::editAction($id)->variables(array(
                    'type' => 'Edit',
                ))->file('form');
    }

    public function viewAction($category, $link) {
        $model = $this->service->getRepository()
                ->findOneWhere(array(array(
                'category' => str_replace('-', ' ', $category),
                'link' => $link,
                'status' => 1
            ))
        );

        if (!$model)
            throw new Exception('Page not found');

        if (in_array($this->currentUser->getRole(), array('subscriber', 'guest', 'admin'))) {
            $settings = Engine::getConfig('modules', 'Cms', 'settings');
            $this->layout = $settings['template'];
            $this->layout .= Engine::getConfig('modules', 'Cms', 'defaults', 'articleLayout');
        }

        CMS::init($this->currentUser, $this->service->getRepository(), $this->request, $this->view);
        CMS::setSettings($settings);
        CMS::setArticle($model);

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
        $cache = new \DScribe\Core\Cache();
//        echo '<pre>';
        foreach ($this->service->getRepository()->findWhere(array(array('status' => 1))) as $key => $page) {
            $category = $page->category()->first();
            echo $category->getName() . '<br />';
            $view = new \DScribe\View\View();
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
        $this->view->variables(array(
            'page' => $this->service->getRepository()->join('comment')->findOne($id),
            'ajax' => $this->request->isAjax(),
        ));

        return ($this->request->isAjax()) ?
                $this->view->partial() :
                $this->view;
    }

    public function listCategoriesAction() {
        if (in_array($this->currentUser->getRole(), array('subscriber', 'guest', 'admin'))) {
            $settings = Engine::getConfig('modules', 'Cms', 'settings');
            $this->layout = $settings['template'];
            $this->layout .= Engine::getConfig('modules', 'Cms', 'defaults', 'articleCategoriesLayout');
        }

        CMS::init($this->currentUser, $this->service->getRepository(), $this->request, $this->view);

        return $this->indexAction();
    }

    public function viewCategoryAction($category) {
        if (in_array($this->currentUser->getRole(), array('subscriber', 'guest', 'admin'))) {
            $settings = Engine::getConfig('modules', 'Cms', 'settings');
            $this->layout = $settings['template'];
            $this->layout .= Engine::getConfig('modules', 'Cms', 'defaults', 'articleCategoryLayout');
        }

        CMS::init($this->currentUser, $this->service->getRepository(), $this->request, $this->view);
        $category = ucfirst(str_replace('-', ' ', $category));
        CMS::setArticleCategory($category);

        return $this->view->variables(array(
                    'articles' => $this->service->getRepository()
                            ->findWhere(array(array(
                                    'category' => $category,
                                    'status' => 1
                        )))
        ));
    }

}
