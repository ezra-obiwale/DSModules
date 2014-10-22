<?php

namespace Store\Services;

use DSLive\Services\SuperService;

class CategoryService extends SuperService {

    /**
     *
     * @var \Store\Services\ItemService
     */
    protected $itemService;

    public function getItemService() {
        if (!$this->itemService)
            $this->itemService = new ItemService();

        return $this->itemService;
    }

}
