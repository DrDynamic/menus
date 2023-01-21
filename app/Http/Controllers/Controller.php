<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\ResponseFactory;
use PharIo\Manifest\Application;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $responses;

    protected function responseForPermissions(array $permissions, callable $response)
    {
        $this->responses[] = [
            'permissions' => $permissions,
            'content'     => $response(),
        ];
    }

    protected function responseForSomePermissions(array $permissions, callable $response)
    {
        $responseDefinition = [
            'somePermissions' => $permissions,
            'content'         => $response(),
            'status'          => 200,
            'headers'         => []
        ];

        if ($responseDefinition['content'] == null) {
            $this->responses[] = [
                'somePermissions' => $permissions,
                'content'         => ["message" => "No Permission"],
                'status'          => 403,
                'headers'         => []
            ];

        } else {
            $this->responses[] = $responseDefinition;
        }
    }

    protected function makeResponse()
    {
        /** @var ApiRequest $request */
        $request      = request();
        $responseData = [];
        foreach ($this->responses as $respons) {
            if ($request->userCan($respons['permissions'])
                && $request->userCanSome($respons['somePermissions'])) {
                $responseData = array_merge($responseData, $respons);
            }
        }
        if ($responseData['content'] instanceof Application
            || $responseData['content'] instanceof ResponseFactory
            || $responseData['content'] instanceof Response) {
            return $responseData['content'];
        }
        return response($responseData['content'], $responseData['status'], $responseData['header']);
    }
}
