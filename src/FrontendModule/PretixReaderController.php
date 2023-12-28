<?php

namespace BohnMedia\ContaoPretixBundle\FrontendModule;

use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BohnMedia\ContaoPretixBundle\PretixApi;

#[AsFrontendModule(category: 'pretix')]
class PretixReaderController extends AbstractFrontendModuleController
{
    private $pretixApi;

    public function __construct(PretixApi $pretixApi)
    {
        $this->pretixApi = $pretixApi;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        return $template->getResponse();
    }
}