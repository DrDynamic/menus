<?php

namespace App\Http\Requests\Api\Menu;

use App\Http\Requests\ApiRequest;
use App\Services\Permissions;

class IndexMenuRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        return $this->userCan(
            Permissions::INDEX_OWN_MENUS,
            Permissions::INDEX_ALL_MENUS);
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
