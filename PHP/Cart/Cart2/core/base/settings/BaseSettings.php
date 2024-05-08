<?php

namespace core\base\settings;

use core\base\controllers\Singleton;

trait BaseSettings
{
    use Singleton {
        instance as singletonInstance;
    }

    private Settings $baseSettings;

    static public function instance(): object
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
            self::singletonInstance()->baseSettings = Settings::instance();
            $unionSettings = self::$_instance->baseSettings->joinProperties(get_class(self::$_instance));
            self::$_instance->setSettings($unionSettings);
        }
        return self::$_instance;
    }

    protected function setSettings($properties): void
    {
        if (isset($properties)) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }
}