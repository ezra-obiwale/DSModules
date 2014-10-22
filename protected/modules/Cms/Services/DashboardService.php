<?php

namespace Cms\Services;

use App\Models\AdminUser,
    DScribe\Core\AService;

class DashboardService extends AService {

    /**
     *
     * @var PageService
     */
    protected $pageService;

    /**
     *
     * @var ArticleService
     */
    protected $articleService;

    /**
     *
     * @var UserService
     */
    protected $userService;

    protected function init() {
        $this->setModel(new AdminUser);
    }

    protected function inject() {
        return array(
            'articleService' => array(
                'class' => 'Cms\Services\ArticleService',
            ),
            'pageService' => array(
                'class' => 'Cms\Services\PageService',
            ),
            'userService' => array(
                'class' => 'Cms\Services\UserService',
            ),
        );
    }

    /**
     * @return PageService
     */
    public function getPageService() {
        return $this->pageService;
    }

    /**
     * @return ArticleService
     */
    public function getArticleService() {
        return $this->articleService;
    }

    /**
     * @return UserService
     */
    public function getUserService() {
        return $this->userService;
    }

}
