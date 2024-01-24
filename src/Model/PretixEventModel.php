<?php

namespace BohnMedia\ContaoPretixBundle\Model;

use Contao\Model;
use Contao\DC_Table;
use Contao\System;
use BohnMedia\ContaoPretixBundle\Model\PretixOrganizerModel;

class PretixEventModel extends Model
{
    protected static $strTable = 'tl_pretix_event';

    private static function getFirst($obj)
    {
        $vars = get_object_vars($obj);
        return reset($vars);
    }

    public function importByOrganizer(DC_TABLE $dc)
    {
        $pretixApi = System::getContainer()->get('BohnMedia\ContaoPretixBundle\PretixApi');

        $objOrganizer = PretixOrganizerModel::findByPk($dc->id);

        // Get all events for the organization
        $events = $pretixApi->request('organizers/' . $objOrganizer->slug . '/events', ['is_future' => true]);

        foreach ($events->results as $eventData) {
            self::update($eventData, $objOrganizer->slug);
        }

        $dc->redirect('contao?do=pretix_organizer');
    }

    public static function update($eventData, $organizer)
    {
        // Update multiple events if event data is an array
        if (is_array($eventData)) {
            foreach ($eventData as $event) {
                self::updateEvent($event, $organizer);
            }
            return;
        }

        // Get object for event
        $eventObject = self::findBy(['organizer=?', 'slug=?'], [$organizer, $eventData->slug]);

        // Create new event if no event exists
        if ($eventObject === null) {
            $eventObject = new self();
        }

        // Update event data
        $eventObject->tstamp = time();
        $eventObject->organizer = $organizer;
        $eventObject->slug = $eventData->slug;
        $eventObject->name = self::getFirst($eventData->name);
        $eventObject->dateFrom = $eventData->date_from ? strtotime($eventData->date_from) : '';
        $eventObject->dateTo = $eventData->date_to ? strtotime($eventData->date_to) : '';
        $eventObject->location = self::getFirst($eventData->location);

        // Save event
        $eventObject->save();
    }
}
