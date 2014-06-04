<?php

namespace Cms\Services;

use Cms\Models\Category,
    DSLive\Services\SuperService;

class CategoryService extends SuperService {

    protected function init() {
        $this->setModel(new Category);
        if (!\Session::fetch('catUp') && $this->repository->findOneBy('name', '-- None --') === null) {
            $model = new Category();
            $model->setName('-- None --')->setPosition('0')->setStatus(1)->setLink('')->setId('ROOT');
            $this->repository->insert($model)->execute();
            if ($this->flush())
                \Session::save('catUp', TRUE);
        }

        parent::init();
//        $this->repository->alwaysJoin('category');
    }

    public function create(Category $model, \Object $files = null, $flush = true) {
        $model->setPosition($this->repository->count('id', array(array('parent' => $model->getParent()))) + 1);
        if ($model->getParent() !== 'ROOT') {
            $mod = $this->findOne($model->getParent());
            $model->setParentPath($mod->getParentPath() ? $mod->getParentPath() . '/' . $mod->getLink() : $mod->getLink());
        }

        return parent::create($model, $files, $flush);
    }

    public function save(Category $model, \Object $files = null, $flush = true) {
        if ($model->getOldPosition() > $model->getPosition()) {
            $cats = $this->repository->customWhere('`:TBL:`.`position` >= ' . $model->getPosition() . ' AND `:TBL:`.`position` < ' . $model->getOldPosition())->select()->execute()->getArrayCopy();
            $includeModel = true;
            foreach ($cats as &$cat) {
                if ($cat->getId() === $model->getId()) {
                    $includeModel = false;
                    $cat->setPosition($model->getPosition());
                }
                else
                    $cat->setPosition($cat->getPosition() + 1);
            }
            if ($includeModel) {
                $cats[] = $model;
            }
            $this->repository->update($cats)->execute();
        }
        else if ($model->getOldPosition() < $model->getPosition()) {
            $cats = $this->repository->customWhere('`:TBL:`.`position` > ' . $model->getOldPosition() . ' AND `:TBL:`.`position` <= ' . $model->getPosition())->select()->execute()->getArrayCopy();
            foreach ($cats as &$cat) {
                $cat->setPosition($cat->getPosition() - 1);
            }
            $cats[] = $model;
            $this->repository->update($cats)->execute();
        }
        else {
            $this->repository->update($model)->execute();
        }

        return $this->flush();
    }

    public function delete() {
        if (parent::delete(FALSE)) {
            $cats = $this->repository->customWhere('`:TBL:`.`position` > ' . $this->model->getPosition())->select()->execute()->getArrayCopy();
            foreach ($cats as &$cat) {
                $cat->setPosition($cat->getPosition() - 1);
            }
            if (!empty($cats))
                $this->repository->update($cats)->execute();
            return $this->flush();
        }

        return false;
    }

}
