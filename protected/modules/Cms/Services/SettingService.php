<?php

namespace Cms\Services;

use Cms\Forms\SettingForm,
    DScribe\Core\Engine,
    DSLive\Services\SuperService,
    Util;

class SettingService extends SuperService {

    public function getForm() {
        if (!$this->form) {
            $this->form = new SettingForm($this->getTemplates());
        }
        return $this->form;
    }

    private function getTemplates() {
        $dirs = Util::readDir(Engine::getModulePath() . 'View' . DIRECTORY_SEPARATOR . 'layouts', Util::DIRS_ONLY, false, null, true);
        return \DSLive\Stdlib\Util::prepareArrayForSelect($dirs);
    }

    public function updateSettings(\Object $data, \Object $files) {
        if (!empty($files->logo->name)) {
            if ($files->logo->error !== UPLOAD_ERR_OK || $files->logo->size > \DSLive\Stdlib\File::getMaxSize())
                return false;

            if (!move_uploaded_file($files->logo->tmpName, Engine::getModulePath() . 'View' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo')) {
                return false;
            }
        }

        if (!empty($data->template))
            $data->template .= DIRECTORY_SEPARATOR;

        return Util::updateConfig(Engine::getModulePath() . 'Config' . DIRECTORY_SEPARATOR . 'local.php', array(
                    'settings' => array(
                        'siteName' => $data->siteName,
                        'template' => $data->template,
                        'listOnHomePage' => ($data->listOnHomePage == 0) ? false : true
                    )
        ));
    }

}
