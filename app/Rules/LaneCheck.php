<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class LaneCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $siteFrom = Request::get('site_id_from');
        $siteTo = Request::get('site_id_to');

        $lane = \App\Lane::where('site_id_from', $siteFrom)
            ->where('site_id_to', $siteTo)
            ->first();

        if ($lane) {
            return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No lane found please manage your lane first.';
    }
}
