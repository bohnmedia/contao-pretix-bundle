<?php

declare(strict_types=1);

namespace BohnMedia\ContaoPretixBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use BohnMedia\ContaoPretixBundle\ContaoPretixBundle;

final class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoPretixBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
