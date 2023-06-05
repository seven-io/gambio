<?php

class SevenModuleCenterModule extends AbstractModuleCenterModule {
    protected function _init() {
        $this->description = $this->languageTextManager->get_text('seven_description');
        $this->sortOrder = 99999;
        $this->title = $this->languageTextManager->get_text('seven_title');
    }
}
