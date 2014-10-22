<?php

namespace Cms\Forms;

use DScribe\Form\Form;

class NewsletterForm extends Form {

    public function __construct() {
        parent::__construct('newsletterForm');

        $this->setModel(new \Cms\Models\Newsletter);

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => 'Title'
            ),
            'attributes' => array(
                'maxlength' => 160,
                'class' => 'span12'
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
                'class' => 'span12'
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
        );
    }

}
