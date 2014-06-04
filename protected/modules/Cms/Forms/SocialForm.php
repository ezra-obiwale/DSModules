<?php

namespace Cms\Forms;

use Cms\Models\Social,
    DSLive\Forms\FileForm;

class SocialForm extends FileForm {

    public function __construct() {
        parent::__construct(new Social, 'socialForm');

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'maxLength' => 100
            )
        ));

        $this->add(array(
            'name' => 'link',
            'type' => 'url',
            'options' => array(
                'label' => 'Link'
            ),
            'attributes' => array(
                'maxLength' => 500
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
            'link' => array(
                'required' => true,
            ),
            'image' => array(
                'required' => true,
            ),
        );
    }

}
