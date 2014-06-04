<?php

namespace Cms\Services;

use Cms\Models\Newsletter,
    DScribe\Core\Engine,
    DSLive\Services\SuperService,
    Object;

class NewsletterService extends SuperService {

    public function create(Newsletter $model, Object $files = null, $flush = true) {
        $model->setAuthor(Engine::getUserIdentity()->getId());
        return parent::create($model, $files, $flush);
    }
    
}
