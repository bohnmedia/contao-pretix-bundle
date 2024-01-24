<?php

namespace BohnMedia\ContaoPretixBundle\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BohnMedia\ContaoPretixBundle\PretixApi;

#[Route('/pretix/api/{path}', name: PretixApiRoute::class, requirements: ['path' => '[\w\-\/]+'])]
class PretixApiRoute
{
    private $pretixApi;

    public function __construct(PretixApi $pretixApi)
    {
        $this->pretixApi = $pretixApi;
    }

    public function __invoke(Request $request, string $path): Response
    {
        $response = $this->pretixApi->request($path, $request->query->all());

        return new Response(json_encode($response) ?: "{}", Response::HTTP_OK, ['content-type' => 'application/json']);
    }
}