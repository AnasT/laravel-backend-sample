<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class SubscriberController extends Controller
{
    /**
     * SubscriberController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Subscriber::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return SubscriberResource::collection(
            $request->user()
                ->subscribers()
                ->paginate(min(
                    100,
                    $request->query('limit', 10)
                ))
        )->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\SubscriberRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SubscriberRequest $request)
    {
        $validated = $request->validated();

        $subscriber = $request->user()
            ->subscribers()
            ->create(Arr::only($validated, ['name', 'email']));

        $fields = array_map(function ($field) {
            return [
                'field_id' => $field['id'],
                'value' => $field['value'] ?? null,
            ];
        }, Arr::get($validated, 'fields', []));

        $subscriber->fields()->createMany($fields);

        return SubscriberResource::make($subscriber)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Subscriber $subscriber
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Subscriber $subscriber)
    {
        return SubscriberResource::make($subscriber)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\SubscriberRequest $request
     * @param \App\Subscriber                    $subscriber
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SubscriberRequest $request, Subscriber $subscriber)
    {
        $validated = $request->validated();

        $subscriber->update(Arr::only($validated, ['name', 'email']));

        foreach (Arr::get($validated, 'fields', []) as $field) {
            $subscriber->fields()->updateOrCreate(
                ['field_id' => $field['id']],
                ['value' => $field['value'] ?? null]
            );
        }

        return SubscriberResource::make($subscriber)
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Subscriber $subscriber
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return response()->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
