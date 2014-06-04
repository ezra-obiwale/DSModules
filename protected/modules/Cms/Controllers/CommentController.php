<?php

namespace Cms\Controllers;

use Cms\Services\CommentService;

class CommentController extends SuperController {

    /**
     * @var CommentService         
     */
    protected $service;
    
    public function noCache() {
        return true;
    }

    public function accessRules() {
        return array(
            array('allow', array(
                    'role' => 'guest',
                    'actions' => 'responses'
                )),
            array('allow', array(
                    'role' => '@',
                )),
            array('deny'),
        );
    }
    
    public function voteUpAction($commentId) {
        $json = new \Json();
        if ($this->service->voteUp($commentId, $this->currentUser->getId())) {
            $json->addData('Voted up <i class="icon-thumbs-up"></icon>', 'message');
        }
        else {
            $json->addData('Vote up failed', 'message');
        }
        $json->addData($this->service->getModel()->toArray(), 'comment');
        $json->toScreen(TRUE);
    }

    public function voteDownAction($commentId) {
        $json = new \Json();
        if ($this->service->voteDown($commentId, $this->currentUser->getId())) {
            $json->addData('Voted down <i class="icon-thumbs-down"></icon>', 'message');
        }
        else {
            $json->addData('Vote down failed', 'message');
        }
        $json->addData($this->service->getModel()->toArray(), 'comment');
        $json->toScreen(TRUE);
    }

    public function responsesAction($commentId) {
        $comment = $this->service->getRepository()
                ->join('page')
                ->join('article')
                ->join('comment', array('push' => true))
                ->findOne($commentId);
        
        \CMS::setView($this->view);
        \CMS::setUser($this->getCurrentUserFromDB());

        $this->view->variables(array(
            'comment' => $comment,
            'ajax' => $this->request->isAjax(),
        ));
        
        return $this->request->isAjax() ? $this->view->partial() : $this->view;
    }

}
