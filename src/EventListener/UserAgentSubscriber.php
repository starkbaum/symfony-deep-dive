<?php


namespace App\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UserAgentSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        //dd($event);
        //$event->setResponse(new Response('new response in UserAgentSubscriber@onKernelRequest'));
        //$this->logger->info('I\'m logging super early on the request');

        $request = $event->getRequest();

        /*
        $request->attributes->set('_controller', function ($slug = null) {

            //dd($slug);

            return new Response('i just took over the controller!');
        });
        */

        $userAgent = $request->headers->get('User-Agent');
        $this->logger->info(sprintf('The User-Agent is "%s"', $userAgent));
        //$isMac = stripos($userAgent, 'Mac') !== false;
        //$request->attributes->set('isMac', $isMac);

        $request->attributes->set('_isMac', $this->isMac($request));
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isMac(Request $request): bool
    {
        //dump($request);

        if ($request->query->has('mac')) {
            return $request->query->getBoolean('mac');
        }

        $userAgent = $request->headers->get('User-Agent');

        return stripos($userAgent, 'Mac') !== false;
    }
}