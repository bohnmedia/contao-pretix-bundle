<?php

use BohnMedia\ContaoPretixBundle\Model\PretixOrganizerModel;
use Contao\DC_Table;
use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_pretix_event'] = [
    'config' => [
        'dataContainer' => DC_TABLE::class,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'slug' => 'unique',
                'organizer' => 'index'
            ]
        ]
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => ['dateFrom', 'name'],
            'flag' => DataContainer::SORT_DAY_DESC
        ],
        'label' => [
            'fields' => ['name'],
            'showColumns' => true
        ],
        'global_operations' => [
            'all' => [
                'href'              => 'act=select',
                'class'             => 'header_edit_all',
                'attributes'        => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ],
        ],
        'operations' => [
            'edit' => [
                'href'              => 'act=edit',
                'icon'              => 'edit.svg'
            ],
            'delete' => [
                'href'              => 'act=delete',
                'icon'              => 'delete.svg',
                'attributes'        => 'onclick="if (!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\')) return false; Backend.getScrollOffset();"'
            ],
            'show' => [
                'href'              => 'act=show',
                'icon'              => 'show.svg'
            ],
        ]
    ],
    'fields' => [
        'id' => [
            'sql'                   => "INT(10) unsigned NOT NULL auto_increment",
        ],
        'tstamp' => [
            'sql'                   => "INT(10) unsigned NOT NULL default '0'",
        ],
        'name' => [
            'inputType'             => 'text',
            'eval' => [
                'maxlength'         => 255,
                'tl_class'          => 'clr w50',
                'mandatory'         => true
            ],
            'sql'                   => "VARCHAR(255) NOT NULL default ''",
        ],
        'slug' => [
            'inputType'             => 'text',
            'eval' => [
                'maxlength'         => 255,
                'tl_class'          => 'w50',
                'mandatory'         => true,
                'unique'            => true
            ],
            'sql'                   => "VARCHAR(255) NOT NULL default ''",
        ],
        'organizer' => [
            'inputType'             => 'select',
            'options_callback'      => ['tl_pretix_event', 'getOrganizers'],
            'eval' => [
                'tl_class'          => 'w50 clr',
                'mandatory'         => true,
                'includeBlankOption' => true
            ],
            'sql'                   => "VARCHAR(255) NOT NULL default ''",
        ],
        'location' => [
            'inputType'             => 'textarea',
            'eval' => [
                'tl_class'          => 'w50',
            ],
            'sql'                   => "TEXT NULL",
        ],
        'dateFrom' => [
            'inputType'               => 'text',
            'sorting'                 => true,
            'flag'                    => DataContainer::SORT_DAY_DESC,
            'eval'                    => ['mandatory' => true, 'tl_class' => 'w50 wizard', 'rgxp' => 'datim', 'datepicker' => true],
            'sql'                     => "varchar(10) NOT NULL default ''"
        ],
        'dateTo' => [
            'inputType'               => 'text',
            'sorting'                 => true,
            'eval'                    => ['tl_class' => 'w50 wizard', 'rgxp' => 'datim', 'datepicker' => true],
            'sql'                     => "varchar(10) NOT NULL default ''"
        ],
    ],
    'palettes' => [
        'default'                   => '{title_event},name,slug,organizer,location;{title_date},dateFrom,dateTo'
    ]
];

class tl_pretix_event
{

    public function getOrganizers(DataContainer $dc)
    {
        $arrOrganizers = [];
        $objOrganizers = PretixOrganizerModel::findAll();
        while ($objOrganizers->next()) {
            $arrOrganizers[$objOrganizers->slug] = $objOrganizers->name;
        }
        return $arrOrganizers;
    }
}