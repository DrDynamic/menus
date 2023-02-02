<?php

namespace App\Http\Requests\Api\Ingredient;

use App\Http\Requests\ApiRequest;
use App\Services\Permissions;
use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->userCan(Permissions::STORE_INGREDIENT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:32'
        ];
    }
}
