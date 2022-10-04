<?php

namespace Erp360\Core\Helpers;

use Respect\Validation\Validator;

trait BaseValidation {

    private static $validationInstance = null;

    public static function getValidationInstance()
    {
        if (self::$validationInstance == null) {
            self::$validationInstance = Validator::class;
        }

        return self::$validationInstance;
    }

}