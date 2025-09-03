<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Captcha implements Rule
{
    public function passes($attribute, $value)
    {
        $answer = session('captcha_answer');
        if ($answer === null) {
            return false;
        }
        $isValid = ((string) $value === (string) $answer);
        if ($isValid) {
            session()->forget('captcha_answer');
        }
        return $isValid;
    }

    public function message()
    {
        return 'The captcha is incorrect.';
    }
}

