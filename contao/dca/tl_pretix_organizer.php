<?php

use Contao\DC_Table;
use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_pretix_organizer'] = [
    'config' => [
        'dataContainer' => DC_TABLE::class,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'slug' => 'unique',
                'autoImport' => 'index'
            ]
        ]
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => ['name'],
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC
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
            'import' => [
                'href'              => 'key=import',
                'icon'              => '/system/themes/flexible/icons/pasteafter.svg'
            ],
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
        'autoImport' => [
            'inputType'             => 'checkbox',
            'eval' => [
                'tl_class'          => 'w50',
                'submitOnChange'    => true
            ],
            'sql'                   => "char(1) NOT NULL default ''",
        ],
        'slug' => [
            'inputType'             => 'text',
            'eval' => [
                'maxlength'         => 255,
                'tl_class'          => 'w50 clr',
                'mandatory'         => true,
                'unique'            => true
            ],
            'sql'                   => "VARCHAR(255) NOT NULL default ''",
        ]
    ],
    'palettes' => [
        '__selector__'              => ['autoImport'],
        'default'                   => '{title_organizer},name;{title_import},autoImport'
    ],
    'subpalettes' => [
        'autoImport'                => 'slug'
    ],
];
