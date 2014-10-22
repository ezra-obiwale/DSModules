<?php

namespace Cms\Forms;

use Cms\Models\Category,
    DScribe\Core\Engine,
    DScribe\Form\Form,
    DSLive\Stdlib\Util;

class CategoryForm extends Form {

    public function __construct() {
        parent::__construct('categoryForm');

        $this->setModel(new Category);

        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'parent',
            'type' => 'select',
            'options' => array(
                'label' => 'Parent Category',
                'object' => array(
                    'class' => '\Cms\Models\Category',
                    'values' => 'getId',
                    'labels' => 'getName',
                    'sort' => 'name'
                )
            ),
            'attributes' => array(
                'maxlength' => 160,
                'autofocus' => 'autofocus'
            )
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'maxlength' => 160,
                'autocomplete' => 'off',
            )
        ));

        $this->add(array(
            'name' => 'link',
            'type' => 'text',
            'options' => array(
                'label' => 'Link'
            ),
            'attributes' => array(
                'maxlength' => 220,
                'autocomplete' => 'off',
            )
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => 'Status',
                'values' => array('Active' => 1, 'Inactive' => 0),
            ),
        ));

        $this->add(array(
            'name' => 'position',
            'type' => 'select',
            'options' => array(
                'label' => 'Position',
                'values' => Util::prepareArrayForSelect(range(1, Engine::getDB()->table('category')->count('*', array(
                    array('parent' => NULL)
                )) - 1))
            ),
        ));

        $this->add(array(
            'name' => 'csrf',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'options' => array(
                'value' => 'Save'
            ),
            'attributes' => array(
                'class' => 'btn btn-success'
            )
        ));
    }

    public function getFilters() {
        return array(
            'name' => array(
                'required' => true,
            ),
            'status' => array(
                'required' => true,
            ),
            'position' => array(
                'required' => true,
            ),
        );
    }

}
