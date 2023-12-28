<?php

namespace BohnMedia\ContaoPretixBundle;

use Contao\Config;

class PretixApi
{
    private $endpoint;
    private $apiToken;

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->apiToken = $this->getApiToken();
    }

    public function __get($name)
    {
        switch ($name) {
            case 'endpoint':
            case 'apiToken':
                return $this->{$name};
            default:
                return null;
        }
    }

    public function request($name, $parameters = [])
    {
        $url = $this->endpoint . '/' . $name . '/?' . http_build_query($parameters);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . $this->apiToken,
            'Accept: application/json',
        ]);
        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $statusCode === 200 ? json_decode($response) : null;
    }

    private function getApiToken(): string
    {
        return Config::get('pretixApiToken');
    }
}
