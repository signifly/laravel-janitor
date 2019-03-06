<?php

namespace Signifly\Janitor;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Signifly\Janitor\Exceptions\InvalidCredentialsException;
use Signifly\Janitor\Exceptions\InvalidClientCredentialsException;

class PassportProxy extends AbstractProxy
{
    public function attemptLogin($username, $password)
    {
        $credentials = [
            $this->getUsernameField() => $username,
            'password' => $password,
        ];

        event(new Attempting($this->getGuard(), $credentials, false));

        $user = $this->getUserInstance()
            ->where($this->getUsernameField(), $username)
            ->first();

        if (is_null($user)) {
            event(new Failed($this->getGuard(), $user, $credentials));
            throw InvalidCredentialsException::forUsername($username);
        }

        $response = $this->proxy('password', [
            'username' => $username,
            'password' => $password,
        ], $user);

        event(new Authenticated($this->getGuard(), $user));
        event(new Login($this->getGuard(), $user, false));

        return $response;
    }

    public function attemptRefresh($refreshToken = null)
    {
        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);
    }

    public function attemptLogout()
    {
        $user = Auth::user();

        $accessToken = $this->getAccessToken();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true,
            ]);

        $accessToken->revoke();

        event(new Logout($this->getGuard(), $user));
    }

    public function proxy($grantType, array $data = [], $user = null)
    {
        $data = array_merge($data, $this->getClientCredentials(), [
            'grant_type' => $grantType,
        ]);

        $client = new HttpClient(['http_errors' => false]);
        $response = $client->post($this->config['oauth_token_url'], [
            'form_params' => $data,
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            event(new Failed($this->getGuard(), $user, $data));
            throw InvalidCredentialsException::withDefaultMessage();
        }

        $data = json_decode((string) $response->getBody(), true);

        return Arr::only($data, [
            'access_token',
            'expires_in',
            'refresh_token',
            'token_type',
        ]);
    }

    /**
     * Get access token.
     *
     * @return object
     */
    protected function getAccessToken()
    {
        return Auth::user()->token();
    }

    /**
     * Get the client credentials.
     *
     * @return array
     */
    protected function getClientCredentials()
    {
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->where('revoked', false)
            ->first();

        if (! $client) {
            throw InvalidClientCredentialsException::withDefaultMessage();
        }

        return [
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'scope'         => '',
        ];
    }
}
