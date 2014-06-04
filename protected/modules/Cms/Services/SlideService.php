<?php

namespace Cms\Services;

use DSLive\Services\SuperService;

class SlideService extends SuperService {
    protected function init() {
        parent::init();
//        $this->repository->alwaysJoin('media');
    }
    
}
