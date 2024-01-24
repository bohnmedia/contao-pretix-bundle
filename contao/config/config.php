<?php

use Contao\ArrayUtil;

use BohnMedia\ContaoPretixBundle\Model\PretixOrganizerModel;
use BohnMedia\ContaoPretixBundle\Model\PretixEventModel;

ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 1, [
    'pretix' => [
        'pretix_organizer' => [
            'tables' => [PretixOrganizerModel::getTable()],
            'import' => [PretixEventModel::class, 'importByOrganizer'],
        ],
        'pretix_event' => [
            'tables' => [PretixEventModel::getTable()]
        ],
    ]
]);

$GLOBALS['TL_MODELS']['tl_pretix_organizer'] = PretixOrganizerModel::class;
$GLOBALS['TL_MODELS']['tl_pretix_event'] = PretixEventModel::class;