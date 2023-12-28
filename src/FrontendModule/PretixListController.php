<?php

namespace BohnMedia\ContaoPretixBundle\FrontendModule;

use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\ModuleModel;
use Contao\Template;
use Contao\FrontendTemplate;
use Contao\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BohnMedia\ContaoPretixBundle\PretixApi;

#[AsFrontendModule(category: 'pretix')]
class PretixListController extends AbstractFrontendModuleController
{
    private $pretixApi;

    public function __construct(PretixApi $pretixApi)
    {
        $this->pretixApi = $pretixApi;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $events = [];

        $nextEvents = $this->pretixApi->request(
            'organizers/' . $model->pretixOrganizer . '/events',
            ['is_future' => 'true', 'ordering' => 'date_from']
        );

        if ($nextEvents !== null) {
            foreach ($nextEvents->results as $event) {
                $eventTemplate = new FrontendTemplate('pretix_event');
                $eventTemplate->class = 'pretix_event';

                foreach (get_object_vars($event) as $key => $value) {
                    $eventTemplate->$key = $value;

                    switch ($key) {
                        case 'name':
                        case 'location':
                            $eventTemplate->{$key . '_local'} = $value->{$GLOBALS['objPage']->language};
                            break;

                        case 'date_from':
                        case 'date_to':
                            $time = strtotime($value);
                            foreach (['date', 'time', 'datim'] as $format) {
                                $eventTemplate->{$key . '_' . $format} = date(Config::get($format . 'Format'), $time);
                            }
                            break;
                    }
                }
                $events[] = $eventTemplate->parse();
            }
        }

        $template->events = implode("", $events);

        return $template->getResponse();
    }
}
