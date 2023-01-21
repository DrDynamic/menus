<?php

namespace App\Http\Requests\Api\Menu;

use App\Http\Requests\ApiRequest;
use App\Services\Permissions;
use Illuminate\Foundation\Http\FormRequest;

class DestroyMenuRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->userCan(Permissions::DESTROY_MENU);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
