<?php

namespace Cms\Controllers;

class SlideController extends SuperController {

    /**
     *
     * @var \Cms\Services\SlideService
     */
    protected $service;

    public function viewAction($id, $codeName = false) {
        if ($codeName !== false) {
            $id = $this->service->getRepository()->findOneBy('codeName', $codeName);
        }
        return parent::viewAction($id)->variables(array(
                    'noHeader' => $codeName
        ));
    }

}
