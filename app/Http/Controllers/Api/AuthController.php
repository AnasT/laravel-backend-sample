<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevokeTokenRequest;
use Exception;
use Illuminate\Support\Arr;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Parser;

class AuthController extends Controller
{
    /**
     * @param \App\Http\Requests\RevokeTokenRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function revoke(RevokeTokenRequest $request)
    {
        try {
            $tokenId = (new Parser())->parse(Arr::get($request->validated(), 'token'))
                ->getClaim('jti');

            $accessToken = $request->user()->tokens()->where('id', $tokenId)
                ->where('revoked', false)
                ->firstOrFail();

            $accessToken->revoke();

            $refreshToken = Passport::refreshTokenModel()::where('access_token_id', $tokenId)
                ->where('revoked', false)
                ->firstOrFail();

            $refreshToken->revoke();
        } catch (Exception $exception) {
            logger($exception->getMessage());
        }

        return response(null);
    }
}
