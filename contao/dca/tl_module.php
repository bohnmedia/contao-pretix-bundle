<?php

use Contao\Backend;
use BohnMedia\ContaoPretixBundle\PretixApi;

$GLOBALS['TL_DCA']['tl_module']['palettes']['pretix_list'] = '{title_legend},name,type;{config_legend},pretixOrganizer';
$GLOBALS['TL_DCA']['tl_module']['palettes']['pretix_reader'] = '{title_legend},name,type';

$GLOBALS['TL_DCA']['tl_module']['fields']['pretixOrganizer'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true, 'mandatory' => true],
    'sql' => "varchar(255) NOT NULL default ''"
];
