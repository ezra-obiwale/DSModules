<?php

namespace Cms\Forms;

use DScribe\Form\Form;

class CommentForm extends Form {

    public function __construct() {
        parent::__construct('commentForm');

        $this->setModel(new \Cms\Models\Comment);

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'content',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Comment <span class="text-info pull-right" style="font-size:smaller">(<i>250 characters maximum</i>)</span>'
            ),
            'attributes' => array(
                'maxLength' => 250,
                'class' => 'span12',
                'rows' => 4
            )
        ));

        $this->add(array(
            'name' => 'parent',
            'type' => 'hidden',
        ));

        $this->add(array(
            'name' => 'csrf',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'options' => array(
                'value' => "Comment"
            ),
            'attributes' => array(
                'class' => 'btn'
            )
        ));
    }

    public function getFilters() {
        return array(
            'content' => array(
                'required' => true,
            ),
        );
    }

}
