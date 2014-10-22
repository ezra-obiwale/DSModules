<?php

namespace Cms\Forms;

use Cms\Models\Media,
    DSLive\Forms\FileForm;

class MediaForm extends FileForm {

    public function __construct() {
        parent::__construct('mediaForm', new Media);

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'maxlength' => 220,
                'autofocus' => 'autofocus'
            )
        ));

        $this->add(array(
            'name' => 'path',
            'type' => 'file',
            'options' => array(
                'label' => 'Path'
            ),
            'attributes' => array(
                'multiple' => 'multiple'
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Description'
            ),
            'attributes' => array(
                'maxlength' => 300,
                'rows' => 6,
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
            'path' => array(
                'required' => true,
            ),
        );
    }

}
