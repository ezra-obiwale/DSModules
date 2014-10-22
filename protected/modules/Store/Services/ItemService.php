<?php

namespace Store\Services;

use DSLive\Services\SuperService;

class ItemService extends SuperService {

    /**
     *
     * @var CategoryService
     */
    protected $categoryService;

    public function getCategoryService() {
        if (!$this->categoryService) {
            $this->categoryService = new CategoryService;
        }
        return $this->categoryService;
    }

}
