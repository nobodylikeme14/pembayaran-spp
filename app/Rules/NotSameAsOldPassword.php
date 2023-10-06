<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Hash;

class NotSameAsOldPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $oldPassword;

    public function __construct($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !Hash::check($value, $this->oldPassword);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password baru harus berbeda dengan password lama.';
    }
}
