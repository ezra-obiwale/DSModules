<?php

namespace Cms\Services;

use Cms\Models\Article,
    DScribe\Core\Engine,
    DSLive\Services\SuperService,
    Object;

class ArticleService extends SuperService {

    public function create(Article $model, Object $files = null, $flush = true) {
        $model->setAuthor(Engine::getUserIdentity()->getId());
        return parent::create($model, $files, $flush);
    }
    
    public function parseArticles() {
        $return = array();
        foreach ($this->repository->orderBy('dateCreated')->orderBy('title')->fetchAll() as $article) {
            $return[$article->getCategory()][] = $article;
        }

        ksort($return);
        return $return;
    }

    public function getCategories() {
        $return = array();
        foreach ($this->repository->distinct('category')->getArrayCopy() as $article) {
            $return[] = $article->getCategory();
        }
        return $return;
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

}
