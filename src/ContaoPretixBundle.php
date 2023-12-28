<?php

declare(strict_types=1);

namespace BohnMedia\ContaoPretixBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoPretixBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
