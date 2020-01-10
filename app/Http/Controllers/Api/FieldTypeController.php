<?php

namespace App\Http\Controllers\Api;

use App\FieldType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class FieldTypeController extends Controller
{
    /**
     * FieldTypeController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(FieldType::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = array_map(function ($type) {
            return [
                'name' => $type,
                'display_name' => Str::title($type),
            ];
        }, FieldType::ALLOWED_TYPES);

        return response()->json(compact('data'), Response::HTTP_OK);
    }
}
