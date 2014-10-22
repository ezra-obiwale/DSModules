<?php

namespace Cms\Services;

use Cms\Models\User as CMU,
    DSLive\Services\UserService as US;

class UserService extends US {

    protected $passwordForm;

    protected function init() {
        parent::init();
        $this->setModel(new CMU);
    }

    public function getForm() {
        return parent::getForm()->setModel(new CMU());
    }

    public function getPasswordForm() {
        return parent::getPasswordForm()->setModel(new CMU());
    }

}
