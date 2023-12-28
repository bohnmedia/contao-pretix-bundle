<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('pretix_legend', 'chmod_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('pretixApiToken', 'pretix_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings')
;

$GLOBALS['TL_DCA']['tl_settings']['fields']['pretixApiToken'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['maxlength' => 64, 'tl_class' => 'w50', 'rgxp' => 'custom', 'customRgxp' => '/^[a-z0-9]*$/'],
    'sql' => "varchar(64) NOT NULL default ''",
];
