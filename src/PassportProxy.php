<?php

namespace Signifly\Janitor;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Signifly\Janitor\Exceptions\InvalidClientCredentialsException;
use Signifly\Janitor\Exceptions\InvalidCredentialsException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PassportProxy extends AbstractProxy
{
    /**
     * Attempt to log the user in by username and password.
     *
     * @param  string $username
     * @param  mixed $password
     * @return array
     */
    public function attemptLogin($username, $password): array
    {
        $credentials = [
            $this->getUsernameField() => $username,
            'password' => $password,
        ];

        event(new Attempting($this->getGuard(), $credentials, false));

        $user = $this->getUserProvider()
            ->retrieveByCredentials($credentials);

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

    /**
     * Attempt refreshing the token.
     *
     * @param  string|null $refreshToken
     * @return array
     */
    public function attemptRefresh($refreshToken = null): array
    {
        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     * Attempt to log the user out.
     *
     * @return void
     */
    public function attemptLogout(): void
    {
        $user = Auth::user();

        $accessToken = $this->getAccessToken();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true,
            ]);

        if (! $accessToken->revoke()) {
            throw new HttpException(409, 'Could not revoke access token.');
        }

        event(new Logout($this->getGuard(), $user));
    }

    /**
     * Proxy request to passport.
     *
     * @param  string $grantType
     * @param  array  $data
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return array
     */
    public function proxy($grantType, array $data = [], $user = null): array
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
    protected function getClientCredentials(): array
    {
        $clientModel = $this->config['client_model'] ?? Passport::clientModel();

        $client = $clientModel::where('password_client', true)
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
