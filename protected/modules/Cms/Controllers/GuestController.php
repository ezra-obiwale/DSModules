<?php

namespace Cms\Controllers;

use Cms\Services\GuestService,
    DScribe\Core\Engine,
    DSLive\Controllers\GuestController as GC;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class GuestController extends GC {

    /**
     *
     * @var GuestService
     */
    protected $service;

    public function noCache() {
        return true;
    }

    protected function init() {
        parent::init();
        $settings = Engine::getConfig('modules', 'Cms', 'settings');
        $this->layout = $settings['template'];
        $this->layout .= Engine::getConfig('modules', 'Cms', 'defaults', 'defaultLayout');
        $this->dashBoardModule = 'cms';
    }

    public function indexAction() {
        $this->view->variables(array(
            'content' => $this->view->getOutput('cms', 'page', 'view'),
        ));
    }

    public function setupAction() {
        if ($this->service->getRepository()->limit(1)->select()->execute()->first()) {
            $this->redirect($this->getModule(), $this->getClassName(), 'login');
        }
        $this->setup = true;
        $return = $this->registerAction();
        $submit = $return->getVariables('form')->get('submit');
        $submit->options->value = 'Let\'s Begin';
        $return->getVariables('form')->remove('submit')
                ->add(array(
                    'name' => 'demo',
                    'type' => 'checkbox',
                    'options' => array(
                        'label' => 'Create demo categories and pages'
                    )
                ))
                ->add($submit->toArray());
        return $return->variables(array('title' => 'CMS Setup'));
    }
    
    public function loginAction($module = null, $controller = null, $action = null, $params = null) {
        $module = !$module ? 'cms' : $module;
        $controller = !$controller ? 'dashboard' : $controller;
        parent::loginAction($module, $controller, $action, $params);
    }

}
