<?php

namespace App\Services;

class PhoneValidator
{
    public static function validate($attribute, $value, $parameters, $validator)
    {
        $pattern = "/^7[4,9][0-9]{9}$/";

        return preg_match($pattern, $value);
    }
}
