<?php

namespace App\Http\Controllers\Api;

use App\Field;
use App\Http\Controllers\Controller;
use App\Http\Requests\FieldRequest;
use App\Http\Resources\FieldResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class FieldController extends Controller
{
    /**
     * FieldController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Field::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $fields = $request->user()
            ->fields();

        $paginate = filter_var(
            $request->query('paginate', true),
            FILTER_VALIDATE_BOOLEAN
        );

        if ($paginate) {
            $fields = $fields->paginate(min(
                100,
                $request->query('limit', 10)
            ));
        } else {
            $fields = $fields->get();
        }

        return FieldResource::collection(
            $fields
        )->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\FieldRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FieldRequest $request)
    {
        $field = $request->user()
            ->fields()
            ->create($request->validated());

        return FieldResource::make($field)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Field $field
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Field $field)
    {
        return FieldResource::make($field)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\FieldRequest $request
     * @param \App\Field                      $field
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FieldRequest $request, Field $field)
    {
        $field->update(Arr::only($request->validated(), 'name'));

        return FieldResource::make($field)
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Field $field
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Field $field)
    {
        $field->delete();

        return response()->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
