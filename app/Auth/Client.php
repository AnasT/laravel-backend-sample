<?php

namespace App\Auth;

use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    /**
     * Determine if the client should skip the authorization prompt.
     *
     * @return bool
     */
    public function skipsAuthorization()
    {
        $firstPartyClients = array_filter(array_map(
            'trim',
            explode(
                ',',
                env('FIRST_PARTY_OAUTH_CLIENTS_IDS', '')
            )
        ));

        return in_array((string) $this->id, $firstPartyClients);
    }
}
