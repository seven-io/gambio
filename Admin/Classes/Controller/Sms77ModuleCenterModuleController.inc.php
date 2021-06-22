<?php

class Sms77ModuleCenterModuleController extends AbstractModuleCenterModuleController {
    protected $db;

    protected $options = [
        'SMS77_API_KEY',
        'SMS77_SMS_FROM',
    ];

    protected $errors = [];

    public function actionProcess() {
        if ($this->_getPostDataCollection()->keyExists('delete_config'))
            return $this->_deleteConfig();

        if ($this->_validate($this->_getPostDataCollection()->getArray())) {
            $this->_saveConfig($this->_getPostDataCollection()->getArray());

            $GLOBALS['messageStack']->add($this->languageTextManager
                ->get_text('sms77_save_config_success'), 'success');
        }

        return $this->actionDefault();
    }

    protected function _deleteConfig() {
        return $this->actionDefault();
    }

    public function actionDefault() {
        return MainFactory::create('AdminLayoutHttpControllerResponse',
            new NonEmptyStringType($this->pageTitle),
            new ExistingFile(new NonEmptyStringType(DIR_FS_CATALOG
                . 'GXModules/Sms77/Sms77/Admin/Html/sms77_configuration.html')),
            MainFactory::create('KeyValueCollection',
                ['config' => $this->_readConfig(), 'errors' => $this->errors]),
            MainFactory::create('AssetCollection', []),
            $contentNavigation);
    }

    protected function _readConfig() {
        $config = [];
        foreach ($this->options as $key) $config[$key] = gm_get_conf($key);

        return $config;
    }

    protected function _validate(array $postData) {
        return 0 === count($this->errors);
    }

    protected function _saveConfig(array $postData) {
        foreach ($this->options as $key)
            gm_set_conf($key, empty($postData[$key]) ? 0 : $postData[$key]);
    }

    protected function _init() {
        $this->pageTitle = $this->languageTextManager->get_text('sms77_title');
        $this->contentView->set_template_dir(
            DIR_FS_CATALOG . 'GXModules/Sms77/Sms77/Admin/Html/');
    }
}
