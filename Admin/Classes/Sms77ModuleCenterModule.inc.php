<?php

class Sms77ModuleCenterModule extends AbstractModuleCenterModule {
    protected function _init() {
        $this->description = $this->languageTextManager->get_text('sms77_description');
        $this->sortOrder = 99999;
        $this->title = $this->languageTextManager->get_text('sms77_title');
    }
}
