<?php

namespace App\Http\Requests\Lawyer;

use Dingo\Api\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }
}
