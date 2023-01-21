<?php

namespace App\Http\Requests;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{

    /** @var UserRepository */
    private mixed $userRepo;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [],
                                array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->userRepo = app(UserRepository::class);
    }

    public function userCan(string ...$permissions)
    {
        return $this->userRepo->userCan($this->user(), ...$permissions);
    }

    public function userCanSome(...$permissions) {
        return $this->userRepo->userCanSome($this->user(), ...$permissions);
    }
}
