<?php

namespace BohnMedia\ContaoPretixBundle;

use Contao\Config;
use Contao\CoreBundle\Framework\ContaoFramework;

class PretixApi
{
    private $endpoint;
    private $contaoFramework;
    private $apiToken;

    public function __construct(string $endpoint, ContaoFramework $contaoFramework)
    {
        $this->endpoint = $endpoint;
        $this->contaoFramework = $contaoFramework;
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
        // Initialize Contao framework
        if (!$this->contaoFramework->isInitialized()) {
            $this->contaoFramework->initialize();
        }

        return Config::get('pretixApiToken');
    }
}
