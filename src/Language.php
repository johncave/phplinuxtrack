<?php

namespace johncave\PhpLinuxTrack;

class Language
{
    private $sLanguage = 'en';
    private $aItems = [];

    public function __construct()
    {
        $this->setLanguage();
    }

    public function setLanguage($sLanguageCode = 'en')
    {
        $this->sLanguage = $sLanguageCode;

        $sLanguageJsonFile = __DIR__ . '/../resources/languages/' . $this->sLanguage . '.json';

        if (file_exists($sLanguageJsonFile)) {
            $sLanguageJsonContents = file_get_contents($sLanguageJsonFile);
            $this->aItems = json_decode($sLanguageJsonContents, true);

            return true;
        }

        return false;
    }

    public function item($sItemName)
    {
        if (isset($this->aItems[$sItemName])) {
            return $this->aItems[$sItemName];
        }

        return false;
    }
}