<?php

namespace Cms\Services;

use Cms\Models\Category,
    Cms\Models\User,
    DScribe\Core\Repository,
    DScribe\Form\Form,
    DSLive\Services\GuestService as GS;

class GuestService extends GS {

    protected function init() {
        $this->setModel(new User());
    }

    public function getCategories() {
        $catRepo = new Repository(new Category());
        return $catRepo->join('page')->fetchAll();
    }

    public function register(\DScribe\View\View $view, array $controllerPath, Form $form, $setup = false, $flush = true) {
        if (parent::register($view, $controllerPath, $form, $setup, false)) {
            if ($form->getData()->demo == 1) {
                if (!$this->createDemo()) {
                    $this->addErrors('Demo categories and pages could not be successfully created');
                }
            }
            return $flush ? $this->flush() : true;
        }

        return false;
    }

    private function createDemo() {
        $mediaPath = ROOT . 'public' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR;
        if (!is_dir($mediaPath)) {
            mkdir($mediaPath);
        }
        copy(MODULES . 'Cms' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR .
                'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR .
                'logo', $mediaPath . 'logo.png');
    }

}
