<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Field;
use App\FieldType;
use App\Http\Resources\FieldResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class FieldControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();
        $user->fields()
            ->createMany(array_merge(
                factory(Field::class, 13)
                    ->make()
                    ->toArray(),
                [
                    [
                        'name' => 'Exists',
                        'type' => FieldType::STRING,
                    ],
                ]
            ));
        Passport::actingAs($user, ['*']);
    }

    /**
     * @test
     * @return void
     */
    public function indexShouldReturnPaginatedResults()
    {
        $response = $this->getJson('/api/fields');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(FieldResource::collection(Field::paginate(10))
            ->response()
            ->getData(true));
    }

    /**
     * @test
     * @return void
     */
    public function indexShouldReturnAllFieldsWhenPaginationIsDisabled()
    {
        $response = $this->getJson('/api/fields?paginate=false');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(FieldResource::collection(Field::all())
            ->response()
            ->getData(true));
    }

    /**
     * @test
     * @return void
     */
    public function indexShouldCapLimitValue()
    {
        $response = $this->getJson('/api/fields?limit=10000');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(FieldResource::collection(Field::paginate(100))
            ->response()
            ->getData(true));
    }


    /**
     * @test
     * @dataProvider invalidCreateDataProvider
     *
     * @param $data
     *
     * @return void
     */
    public function storeShouldFailWhenInvalidDataIsProvided($data)
    {
        $response = $this->postJson('/api/fields', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function storeShouldCreateANewField()
    {
        $response = $this->postJson('/api/fields', [
            'name' => 'New Field',
            'type' => FieldType::STRING,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(FieldResource::make(Field::latest('id')
            ->first())
            ->response()
            ->getData(true));
    }

    /**
     * @test
     * @dataProvider invalidUpdateDataProvider
     *
     * @param $data
     *
     * @return void
     */
    public function updateShouldFailWhenInvalidDataIsProvided($data)
    {
        $response = $this->putJson('/api/fields/1', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function updateShouldNotAlterFieldType()
    {
        $previousValue = Field::find(1);
        $response = $this->putJson('/api/fields/1', [
            'name' => $previousValue->name,
            'type' => 'any',
        ]);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertEquals($previousValue->type, Field::find(1)->type);
    }

    /**
     * @test
     * @return void
     */
    public function deleteShouldRemoveTheField()
    {
        $response = $this->deleteJson('/api/fields/1');
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull(Field::find(1));
    }

    /**
     * @return array
     */
    public function invalidCreateDataProvider()
    {
        return [
            [
                [],
            ],
            [
                [
                    'name' => 'Test',
                    'type' => 'unknown',
                ],
            ],
            [
                [
                    'name' => 'Exists',
                    'type' => FieldType::BOOLEAN,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function invalidUpdateDataProvider()
    {
        return [
            [
                [],
            ],
            [
                [
                    'name' => 'Exists',
                ],
            ],
        ];
    }
}
