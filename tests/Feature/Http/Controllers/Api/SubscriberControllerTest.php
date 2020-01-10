<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Resources\SubscriberResource;
use App\Subscriber;
use App\SubscriberState;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();
        $user->subscribers()
            ->createMany(array_merge(
                factory(Subscriber::class, 13)
                    ->make()
                    ->toArray(),
                [
                    [
                        'name' => 'Exists',
                        'email' => 'exists@test.com',
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
        $response = $this->getJson('/api/subscribers');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(SubscriberResource::collection(Subscriber::paginate(10))
            ->response()
            ->getData(true));
    }

    /**
     * @test
     * @return void
     */
    public function indexShouldCapLimitValue()
    {
        $response = $this->getJson('/api/subscribers?limit=10000');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(SubscriberResource::collection(Subscriber::paginate(100))
            ->response()
            ->getData(true));
    }


    /**
     * @test
     * @dataProvider invalidDataProvider
     *
     * @param $data
     *
     * @return void
     */
    public function storeShouldFailWhenInvalidDataIsProvided($data)
    {
        $response = $this->postJson('/api/subscribers', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function storeShouldCreateANewSubscriber()
    {
        $response = $this->postJson('/api/subscribers', [
            'name' => 'New Subscriber',
            'email' => 'new@test.com',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(SubscriberResource::make(Subscriber::latest('id')
            ->first())
            ->response()
            ->getData(true));
    }

    /**
     * @test
     * @dataProvider invalidDataProvider
     *
     * @param $data
     *
     * @return void
     */
    public function updateShouldFailWhenInvalidDataIsProvided($data)
    {
        $response = $this->putJson('/api/subscribers/1', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function updateShouldIgnoreUniqueRuleForUpdatedSubscriber()
    {
        $previousValue = Subscriber::find(1);
        $response = $this->putJson('/api/subscribers/1', [
            'name' => $previousValue->name,
            'email' => $previousValue->email,
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
    }

    /**
     * @test
     * @return void
     */
    public function updateShouldNotAlterSubscriberState()
    {
        $previousValue = Subscriber::find(1);

        $response = $this->putJson('/api/subscribers/1', [
            'name' => $previousValue->name,
            'email' => $previousValue->email,
            'state' => SubscriberState::UNSUBSCRIBED,
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertEquals($previousValue->state, Subscriber::find(1)->state);
    }

    /**
     * @test
     * @return void
     */
    public function deleteShouldRemoveTheSubscriber()
    {
        $response = $this->deleteJson('/api/subscribers/1');
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull(Subscriber::find(1));
    }

    /**
     * @return array
     */
    public function invalidDataProvider()
    {
        return [
            [
                [],
            ],
            [
                [
                    'name' => '',
                    'email' => 'valid@test.com',
                ],
            ],
            [
                [
                    'name' => 'Test',
                    'email' => 'invalid',
                ],
            ],
            [
                [
                    'name' => 'Test',
                    'email' => 'exists@test.com',
                ],
            ],
        ];
    }
}
