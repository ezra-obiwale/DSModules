<?php

namespace Cms\Services;

use Cms\Models\Comment,
    Cms\Models\Page,
    DScribe\Core\AUser,
    DScribe\Core\Cache,
    DSLive\Services\SuperService,
    DsUtil\Services\FormsService;

class PageService extends SuperService {

    /**
     *
     * @var CategoryService
     */
    protected $categoryService;

    /**
     *
     * @var SlideService
     */
    protected $slideService;

    /**
     *
     * @var FormsService
     */
    protected $dsUtilFormsService;

    protected function init() {
        parent::init();
    }

    public function getCategoryService() {
        if (!$this->categoryService)
            $this->categoryService = new CategoryService();

        return $this->categoryService;
    }

    public function getForm($model = null) {
        parent::getForm();
        $noOfPagesInCategory = ($model) ? $this->repository->count('id', array(array('category' => $model->getCategory()))) : 0;
        return $this->form->completeConstruct($noOfPagesInCategory);
    }

    public function create(Page $model) {
        if ($this->findOneWhere(array(array('category' => $model->getCategory(), 'link' => $model->getLink())), false)) {
            return false;
        }

        $this->updateHomePage($model);
        $model->setPosition($this->repository->count('*', array(array('category' => $model->getCategory()))) + 1);

        if ($return = parent::create($model)) {
            if (!$model->getStatus()) {
                $this->model->populate($model->toArray(true));
                $this->removeCachedPage($this->model);
            }
            return true;
        }

        return false;
    }

    public function save(Page &$model) {
        $this->updateHomePage($model);
        if (!$model->getStatus()) {
            $this->removeCachedPage($model);
        }
        if ($model->getOldPosition() > $model->getPosition()) {
            $pages = $this->repository->customWhere('`:TBL:`.`position` >= ' . $model->getPosition() .
                                    ' AND `:TBL:`.`position` < ' . $model->getOldPosition() . ' AND `:TBL:`.`category`="' . $model->getCategory() . '"')
                            ->select()->execute()->getArrayCopy();
            $includeModel = true;
            foreach ($pages as &$page) {
                if ($page->getId() === $model->getId()) {
                    $includeModel = false;
                    $page->setPosition($model->getPosition());
                }
                else
                    $page->setPosition($page->getPosition() + 1);
            }
            if ($includeModel) {
                $pages[] = $model;
            }
            $this->repository->update($pages)->execute();
        }
        else if ($model->getOldPosition() < $model->getPosition()) {
            $pages = $this->repository->customWhere('`:TBL:`.`position` > ' . $model->getOldPosition() .
                                    ' AND `:TBL:`.`position` <= ' . $model->getPosition() . ' AND `:TBL:`.`category`="' . $model->getCategory() . '"')
                            ->select()->execute()->getArrayCopy();
            foreach ($pages as &$page) {
                $page->setPosition($page->getPosition() - 1);
            }
            $pages[] = $model;
            $this->repository->update($pages)->execute();
        }
        else {
            $this->repository->update($model)->execute();
        }

        return $this->flush();
    }

    private function updateHomePage(Page $model) {
        if ($model->getIsHomePage()) {
            if ($homePage = $this->findOneBy('isHomePage', true, false)) {
                $this->save($homePage->setIsHomePage(false), null, false);
            }
        }
    }

    private function removeCachedPage(Page $model) {
        $cache = new Cache();
        $category = $model->category()->first();
        $cache->remove('page/view/' . $category->getLink() . '/' . $model->getLink());
    }

    public function delete() {
        if (parent::delete(FALSE)) {
            $pages = $this->repository->customWhere('`:TBL:`.`position` > ' . $this->model->getPosition() . ' AND `:TBL:`.`category`="' . $this->model->getCategory() . '"')
                            ->select()->execute()->getArrayCopy();
            foreach ($pages as &$page) {
                $page->setPosition($page->getPosition() - 1);
            }
            if (!empty($pages))
                $this->repository->update($pages)->execute();
            return $this->flush();
        }

        return false;
    }

    public function copyPage($id) {
        $model = $this->findOne($id);
        $modelCopy = clone $model;
        $modelCopy->setId(null);
        $modelCopy->setTitle('Copy of ' . $model->getTitle());
        $modelCopy->setLink('copy-of-' . $model->getLink());
        $modelCopy->setDateCreated(null);
        $modelCopy->setViewCount(0);
        if ($this->create($modelCopy, NULL, false)) {
            $modelCopy->setPosition($model->getPosition() + 1);
            if ($this->save($modelCopy)) {
                return $modelCopy;
            }
        }

        return false;
    }

    public function saveComment(Page $page, Comment $comment, AUser $user) {
        $comment->setUser($user->getId());
        if (!$comment->getParent()) {
            $comment->setParent(NULL);
            $comment->setPage($page->getId());
        }
        $commentService = new CommentService();
        return $commentService->create($comment);
    }

}
