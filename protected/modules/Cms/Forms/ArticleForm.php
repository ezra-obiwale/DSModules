<?php

namespace Cms\Forms;

use DScribe\Form\Form;

class ArticleForm extends Form {

    public function __construct() {
        parent::__construct('articleForm');

        $this->setModel(new \Cms\Models\Article);

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'category',
            'type' => 'text',
            'options' => array(
                'label' => 'Category',
                'value' => 'Default'
            ),
            'attributes' => array(
                'maxlength' => 160,
                'class' => 'span12',
                'placeholder' => 'Type to see exiting categories',
                'autocomplete' => 'off',
                'autofocus' => 'autofocus',
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => 'Title'
            ),
            'attributes' => array(
                'maxlength' => 160,
                'class' => 'span12',
                'required' => 'required',
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
                'class' => 'span12',
                'required' => 'required',
                'autocomplete' => 'off',
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Description'
            ),
            'attributes' => array(
                'class' => 'span12',
                'rows' => 2,
                'maxlength' => 200
            )
        ));

        $this->add(array(
            'name' => 'content',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Content'
            ),
            'attributes' => array(
                'class' => 'span12 tiny',
                'rows' => 20,
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => 'Status',
                'values' => array(
                    'Active' => 1,
                    'Inactive' => 0,
                ),
            ),
            'attributes' => array(
            )
        ));

        $this->add(array(
            'name' => 'allowComments',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Allow Comments',
            ),
            'attributes' => array(
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
            'title' => array(
                'required' => true,
            ),
            'link' => array(
                'required' => true,
            ),
            'content' => array(
                'required' => true,
            ),
            'status' => array(
                'required' => true,
            ),
        );
    }

}
