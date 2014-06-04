<?php

namespace Cms\Controllers;

class SettingController extends SuperController {

    /**
     *
     * @var \Cms\Services\SettingService
     */
    protected $service;

    public function noCache() {
        return true;
    }

    public function viewAction() {
        $form = $this->service->getForm();
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid() && $this->service->updateSettings($form->getData(), $this->request->getFiles())) {
                $this->flash()->setSuccessMessage('Settings saved successfully');
                $this->redirect();
            }
            $this->flash()->setErrorMessage('Save settings failed');
        }
        else {
            $settings = \DScribe\Core\Engine::getConfig('modules', 'Cms', 'settings');
            if ($template = stristr($settings['template'], DIRECTORY_SEPARATOR, true)) {
                $settings['template'] = $template;
            }
            $form->setData($settings);
        }

        $this->view->variables(array(
            'form' => $form,
            'settings' => $settings,
        ));
    }

}
