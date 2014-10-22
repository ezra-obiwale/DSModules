<?php

namespace Cms\Forms;

use Cms\Models\Slide,
    DScribe\Form\Form;

class SlideForm extends Form {

    public function __construct() {
        parent::__construct('slideForm');

        $this->setModel(new Slide);

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'codeName',
            'type' => 'text',
            'options' => array(
                'label' => 'Code Name'
            ),
            'attributes' => array(
                'maxlength' => 220
            )
        ));

        $this->add(array(
            'name' => 'media',
            'type' => 'select',
            'options' => array(
                'label' => 'Select Media Files',
                'emptyValue' => '',
                'object' => array(
                    'class' => 'Cms\Models\Media',
                    'values' => 'getId',
                    'labels' => 'getName'
                )
            ),
            'attributes' => array(
                'class' => 'media',
                'multiple' => 'multiple'
            )
        ));
        
        $this->add(array(
            'name' => 'width',
            'type' => 'text',
            'options' => array(
                'label' => 'Width'
            ),
            'attributes' => array(
                'placeholder' => 'append px | %'
            )
        ));
        
        $this->add(array(
            'name' => 'height',
            'type' => 'text',
            'options' => array(
                'label' => 'height'
            ),
            'attributes' => array(
                'placeholder' => 'append px | %'
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
            'codeName' => array(
                'required' => true,
            ),
            'media' => array(
                'required' => true,
            ),
        );
    }

}
