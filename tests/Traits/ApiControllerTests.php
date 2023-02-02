<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

trait ApiControllerTests
{
    public function testIndex(string $url, Factory $modelFactory, array $with = [], array $jsonPaths = [])
    {
        $modelFactory->count(25)->create();
        $entityClass = $modelFactory->modelName();

        /** @var Collection $entities */
        $entities = $entityClass::with($with)->get();

        $response = $this->json(
            'GET',
            $url
        )
            ->assertOk();

        foreach ($jsonPaths as $path) {
            $response->assertJsonPath("*.$path", $entities->pluck($path)->toArray());
        }

        foreach ($response->json() as $returnedEntity) {
            $this->assertDatabaseHas($entityClass::TABLE, $returnedEntity);
        }

        return $response;
    }

    public function testUnauthenticated(string $method, string $url)
    {
        $this->json($method, $url)
            ->assertUnauthorized();
    }

    public function testForbidden(string $method, string $url)
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json($method, $url)
            ->assertForbidden();
    }

    public function testStore(string $url, array $requiredData, array $optionalData = [], callable $validateFunc = null)
    {
        if ($validateFunc == null) {
            $validateFunc = [$this, 'assertJsonPaths'];
        }
        $data = array_merge($requiredData, $optionalData);

        // can store all data
        $response = $this->json('POST', $url, $data)
            ->assertCreated();

        $validateFunc($response, $data);

        // required data is really required
        $data = $requiredData;
        foreach ($requiredData as $key => $value) {
            unset($data[$key]);

            $this->json('POST', $url, $data)
                ->assertUnprocessable();
        }

        // optional data is really optional
        $data = $requiredData;
        foreach ($optionalData as $key => $value) {
            $data[$key] = $value;

            $response = $this->json('POST', $url, $data)
                ->assertCreated();

            $validateFunc($response, $data);
        }
    }

    public function testShow(string $url, array $data)
    {
        $response = $this->json('GET', $url)
            ->assertOk()
            ->assertJson($data);

        $this->assertJsonPaths($response, $data);
    }

    public function testUpdate(string $url, array $data)
    {
        $response = $this->json('PUT', $url, $data)
            ->assertOk();
        $this->assertJsonPaths($response, $data);
    }

    protected function assertJsonPaths(TestResponse $response, array $data)
    {
        foreach (Arr::dot($data) as $path => $value) {
            $response->assertJsonPath($path, $value);
        }
    }
}
