<?php

namespace Store\Forms;

use DSLive\Forms\FileForm,
    Store\Models\Category;

class CategoryForm extends FileForm {

    public function __construct() {
        parent::__construct(new Category, 'categoryForm');

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'maxlength' => 120,
                'required' => 'required',
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Description'
            ),
            'attributes' => array(
                'maxlength' => 200,
                'rows' => 5,
            )
        ));

        $this->add(array(
            'name' => 'image',
            'type' => 'file',
            'options' => array(
                'label' => 'Image'
            ),
            'attributes' => array(
            )
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'radio',
            'options' => array(
                'label' => 'Status',
                'values' => array(
                    'Active' => 1,
                    'Inactive' => 0,
                ),
                'default' => 1,
            ),
            'attributes' => array(
                'required' => 'required'
            )
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
        );
    }

}
