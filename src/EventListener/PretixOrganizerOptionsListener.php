<?php

namespace BohnMedia\ContaoPretixBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use BohnMedia\ContaoPretixBundle\PretixApi;

#[AsCallback(table: 'tl_module', target: 'fields.pretixOrganizer.options')]
class PretixOrganizerOptionsListener
{
    private $pretixApi;

    public function __construct(PretixApi $pretixApi)
    {
        $this->pretixApi = $pretixApi;
    }

    public function __invoke(DataContainer $dc): array
    {
        $arrOptions = [];
        $arrOrganizers = $this->pretixApi->request('organizers');
        if ($arrOrganizers !== null) {
            foreach ($arrOrganizers->results as $organizer) {
                $arrOptions[$organizer->slug] = $organizer->name;
            }
        }
        return $arrOptions;
    }
}
