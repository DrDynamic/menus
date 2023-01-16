<?php

namespace App\Http\Requests;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    protected function userCan(string ...$permissions)
    {
        /** @var UserRepository $userRepo */
        $userRepo = app(UserRepository::class);
        return $userRepo->userCan($this->user(), ...$permissions);
    }
}
