<?php

namespace Cms\Controllers;

use DScribe\Core\Engine;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubscriptionController
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class SubscriptionController extends SuperController {

    /**
     *
     * @var \Cms\Services\SubscriptionService
     */
    protected $service;

    public function noCache() {
        return true;
    }

    public function accessRules() {
        return array(
            array('allow',
                array(
                    'role' => 'guest',
                    'actions' => 'add'
                )),
            array('allow',
                array(
                    'role' => 'admin',
                )),
            array('deny'),
        );
    }

    public function addAction() {
        $settings = Engine::getConfig('modules', 'Cms', 'settings');
        $this->layout = $settings['template'];
        $this->layout .= Engine::getConfig('modules', 'Cms', 'defaults', 'defaultLayout');
        
        if ($this->request->isPost()) {
            if ($this->service->add($this->request->getPost())) {
                $this->flash()->setSuccessMessage('Subscription successful. <strong><i>' . $this->request->getPost()->email . '</i></strong> will now receive emails of content updates');
            }
            else {
                $this->flash()->setErrorMessage('Subscription failed. Please try again');
            }
        }
        else {
            $this->flash()->setErrorMessage('Invalid action');
        }
    }

}
