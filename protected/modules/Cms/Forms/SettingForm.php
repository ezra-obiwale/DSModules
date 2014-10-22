<?php

namespace Cms\Forms;

use DSLive\Forms\FileForm;

class SettingForm extends FileForm {

    public function __construct($templates) {
        parent::__construct('settingForm');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'logo',
            'type' => 'file',
            'options' => array(
                'label' => 'Change Logo'
            ),
        ));

        $this->add(array(
            'name' => 'siteName',
            'type' => 'text',
            'options' => array(
                'label' => 'Site Name / Header'
            ),
            'attributes' => array(
                'maxlength' => 160,
                'class' => 'span12'
            )
        ));

        $this->add(array(
            'name' => 'template',
            'type' => 'select',
            'options' => array(
                'label' => 'Template',
                'emptyValue' => 'default',
                'values' => $templates,
            ),
        ));

        $this->add(array(
            'name' => 'listOnHomePage',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'List Articles On Home Page'
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
            'siteName' => array(
                'required' => true,
            ),
        );
    }

}
