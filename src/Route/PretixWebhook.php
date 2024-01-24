<?php

namespace BohnMedia\ContaoPretixBundle\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Monolog\ContaoContext;
use Psr\Log\LoggerInterface;
use BohnMedia\ContaoPretixBundle\PretixApi;
use BohnMedia\ContaoPretixBundle\Model\PretixOrganizerModel;
use BohnMedia\ContaoPretixBundle\Model\PretixEventModel;

#[Route('/pretix/webhook', name: PretixWebhook::class, methods: ['POST'])]
class PretixWebhook
{
    private ContaoFramework $framework;
    private PretixApi $pretixApi;
    private LoggerInterface $logger;

    public function __construct(ContaoFramework $framework, PretixApi $pretixApi, LoggerInterface $logger)
    {
        $this->framework = $framework;
        $this->pretixApi = $pretixApi;
        $this->logger = $logger;
    }

    private function eventChanged(string $organizer, string $event)
    {
        // Check if autom import is enabled
        $organizerObject = PretixOrganizerModel::findBySlug($organizer);
        if ($organizerObject === null || !$organizerObject->autoImport) {
            return;
        }

        // Get event data from pretix
        $eventData = $this->pretixApi->request('organizers/' . $organizer . '/events/' . $event);

        // Log error if event data is null
        if ($eventData === null) {
            $this->logger->error(
                'Pretix-Event "' . $event . '" from organizer "' . $organizer . '" not found',
                ['contao' => new ContaoContext(__METHOD__, ContaoContext::GENERAL)]
            );

            return;
        }

        // Update event data
        PretixEventModel::update($eventData, $organizer);
    }

    public function __invoke(Request $request): Response
    {
        $content = json_decode($request->getContent());

        // Check if the request is valid
        if ($content === null) {
            $this->logger->error(
                'Pretix-Webhook called with invalid JSON "' . $request->getContent() . '"',
                ['contao' => new ContaoContext(__METHOD__, ContaoContext::ACCESS)]
            );

            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        $action = $content->action ?? "";
        $event = $content->event ?? "";
        $organizer = $content->organizer ?? "";

        $this->logger->info(
            'Pretix-Webhook called with action "' . $action . '" for event "' . $event . '" from organizer "' . $organizer . '"',
            ['contao' => new ContaoContext(__METHOD__, ContaoContext::ACCESS)]
        );

        switch ($content->action) {
            case 'pretix.event.changed':
                $this->eventChanged($organizer, $event);
                break;
        }

        return new Response('');
    }
}