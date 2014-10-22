<?php

namespace Store\Forms;

use DScribe\Form\Form,
    Store\Fieldsets\MoreOptionsFieldset,
    Store\Models\PaymentOption;

class PaymentOptionForm extends Form {

    public function __construct() {
        parent::__construct('paymentOptionForm');

        $this->setModel(new PaymentOption);

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'maxlength' => 100
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Description'
            ),
            'attributes' => array(
                'rows' => 6
            )
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Status',
                'default' => true,
            ),
            'attributes' => array(
            )
        ));

        $this->add(array(
            'name' => 'data',
            'type' => 'fieldset',
            'options' => array(
                'label' => 'More Options',
                'value' => new MoreOptionsFieldset(),
                'multiple' => array(
                    'buttonLabel' => 'More Options',
                    'max' => 4
                ),
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
        );
    }

}
