<?php

namespace Serengiy\SendPulse;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

abstract class SendPulseAbstract
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const TOKEN_TYPE_BEARER = 'Bearer';

    /**
     * @var string
     */
    private string $apiUrl = 'https://api.sendpulse.com';

    /**
     * @var string
     */
    private string $userId;

    /**
     * @var string
     */
    private string $secret;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        if(!config('send-pulse.user_id'))
            throw new \Exception('Client ID is not set');

        if(!config('send-pulse.secret'))
            throw new \Exception('Client secret is not set');

        $this->userId = config('send-pulse.user_id');
        $this->secret = config('send-pulse.secret');
    }

    /**
     * @throws \Exception
     */
    protected function sendRequest(string $path, string $method = self::METHOD_GET, array $data = [], bool $useToken = true): array
    {
        $url = $this->apiUrl . '/' . $path;

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($useToken) {
            $headers['Authorization'] = self::TOKEN_TYPE_BEARER . ' ' . $this->getToken();
        }

        $response = Http::withHeaders($headers)
            ->timeout(300)
            ->withOptions([
                'verify' => false,
            ])
            ->$method($url, $data);

        if ($response->failed()) {
            $errorMessage = $response->body();
            throw new \Exception(
                'Request ' . $method . ' ' . $url . ' failed! Status: ' . $response->status() . '. Error: ' . $errorMessage,
                $response->status()
            );
        }
        return $response->json();
    }

    /**
     * Send GET request
     * @param string $path
     * @param array $data
     * @param bool $useToken
     * @return array
     * @throws \Exception
     */
    public function get(string $path, array $data = [], bool $useToken = true): array
    {
        return $this->sendRequest($path, self::METHOD_GET, $data, $useToken);
    }

    /**
     * Send POST request
     * @param string $path
     * @param array $data
     * @param bool $useToken
     * @return array
     * @throws \Exception
     */
    public function post(string $path, array $data = [], bool $useToken = true): array
    {
        return $this->sendRequest($path, self::METHOD_POST, $data, $useToken);
    }


    /**
     * @throws \Exception
     */
    private function getToken():string
    {
        if(!Cache::has('send_pulse_access_token')) {
            $tokenResponse = $this->sendRequest('oauth/access_token', self::METHOD_POST, [
                'grant_type' => 'client_credentials',
                'client_id' => $this->userId,
                'client_secret' => $this->secret,
            ], false);
            Cache::put('send_pulse_access_token', $tokenResponse['access_token'],3600) ;
            return $tokenResponse['access_token'];
        }
        return Cache::get('send_pulse_access_token');
    }
}
