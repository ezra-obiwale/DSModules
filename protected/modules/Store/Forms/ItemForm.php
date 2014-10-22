<?php

namespace Store\Forms;

use DSLive\Forms\FileForm,
    Store\Models\Item;

class ItemForm extends FileForm {

    public function __construct() {
        parent::__construct(new Item, 'itemForm');

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'maxlength' => 200
            )
        ));

        $this->add(array(
            'name' => 'shortDescription',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Short Description'
            ),
            'attributes' => array(
                'maxlength' => 120,
                'rows' => 4
            )
        ));

        $this->add(array(
            'name' => 'longDescription',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Long Description'
            ),
            'attributes' => array(
                'rows' => 6
            )
        ));

        $this->add(array(
            'name' => 'images',
            'type' => 'file',
            'options' => array(
                'label' => 'Images'
            ),
            'attributes' => array(
                'multiple' => 'multiple'
            )
        ));

        $this->add(array(
            'name' => 'price',
            'type' => 'number',
            'options' => array(
                'label' => 'Price'
            ),
            'attributes' => array(
                'min' => 0
            )
        ));

        $this->add(array(
            'name' => 'inStock',
            'type' => 'number',
            'options' => array(
                'label' => 'In Stock',
            ),
            'attributes' => array(
                'min' => 0
            )
        ));

        $this->add(array(
            'name' => 'tooLowCount',
            'type' => 'number',
            'options' => array(
                'label' => 'Too Low Count'
            ),
            'attributes' => array(
                'min' => 0
            )
        ));

        $this->add(array(
            'name' => 'tooLowMessage',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Too Low Message'
            ),
            'attributes' => array(
                'maxlength' => 200,
                'rows' => 4,
            )
        ));

        $this->add(array(
            'name' => 'shippingInformation',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Shipping Information'
            ),
            'attributes' => array(
                'maxlength' => 300,
                'rows' => 4,
            )
        ));

        $this->add(array(
            'name' => 'paymentOptions',
            'type' => 'select',
            'options' => array(
                'label' => 'Payment Options'
            ),
            'attributes' => array(
                'maxlength' => 220
            )
        ));

//        $this->add(array(
//            'name' => 'csrf',
//            'type' => 'hidden'
//        ));

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
            'price' => array(
                'required' => true,
            ),
        );
    }

}
