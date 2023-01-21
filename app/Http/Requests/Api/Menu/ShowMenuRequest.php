<?php

namespace App\Http\Requests\Api\Menu;

use App\Http\Requests\ApiRequest;
use App\Services\Permissions;

class ShowMenuRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->userCan(Permissions::INDEX_OWN_MENUS)) {
            return $this->menu->created_by_user_id == $this->user()->id;
        }
        return $this->userCanSome(Permissions::INDEX_ALL_MENUS, Permissions::INDEX_OWN_MENUS);
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
