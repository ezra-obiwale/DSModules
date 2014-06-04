<?php

namespace Cms\Forms;

use Cms\Models\Page,
    DScribe\Form\Form,
    DSLive\Stdlib\Util;

class PageForm extends Form {

    public function __construct() {
        parent::__construct('pageForm');

        $this->setModel(new Page);

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'category',
            'type' => 'select',
            'options' => array(
                'label' => 'Category <a id="newCategory" href="/cms/category/new">(New Category)</a>',
                'object' => array(
                    'class' => 'Cms\Models\Category',
                    'values' => 'getId',
                    'labels' => 'getName',
                    'sort' => 'name'
                )
            ),
            'attributes' => array(
                'autofocus' => 'autofocus',
            )
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => 'Title'
            ),
            'attributes' => array(
                'maxLength' => 160,
                'class' => 'span12',
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
                'maxLength' => 220,
                'class' => 'span12',
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
                'maxLength' => 200
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
                'rows' => 20
            )
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => 'Status',
                'values' => array('Active' => 1, 'Inactive' => 0),
            ),
            'attributes' => array(
            )
        ));
    }

    public function completeConstruct($noOfPagesInCategory) {
        $this->add(array(
            'name' => 'position',
            'type' => 'select',
            'options' => array(
                'label' => 'Position',
                'values' => Util::prepareArrayForSelect(range(1, $noOfPagesInCategory))
            ),
            'attributes' => array(
            )
        ));

        $this->add(array(
            'name' => 'isHomePage',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Home Page',
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

        return $this;
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
