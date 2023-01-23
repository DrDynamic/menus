<?php

namespace App\Http\Requests\Api\Menu;

use App\Http\Requests\ApiRequest;
use App\Services\Permissions;

class UpdateManuRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->userCan(Permissions::UPDATE_MENU);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'                       => 'sometimes|string|max:32',
            'image_url'                  => 'sometimes|string|max:255',
            'ingredients'                => 'sometimes|array',
            'ingredients.*.id'           => 'required|numeric|exists:ingredients,id',
            'ingredients.*.name'         => 'sometimes|string|max:32',
            'ingredients.*.pivot.amount' => 'sometimes|numeric',
            'ingredients.*.pivot.unit'   => 'sometimes|string|max:16'
        ];
    }
}
