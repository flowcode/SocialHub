<?php

namespace Flowcode\SocialHubBundle\Bundle\Event;

use Flowcode\DashboardBundle\Event\ListPluginsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;


class ListPluginsSubscriber implements EventSubscriberInterface
{
    protected $router;
    protected $translator;

    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ListPluginsEvent::NAME => array('handler', 0),
        );
    }


    public function handler(ListPluginsEvent $event)
    {
        $plugins = $event->getPluginDescriptors();

        /* add default */
        $plugins[] = array(
            "name" => "SocialHub",
            "image" => null,
            "version" => "v1.0",
            "settings" => $this->router->generate('admin_socialnetwork'),
            "description" => "Social integrations.",
            "website" => null,
            "authors" => array(
                array(
                    "name" => "Flowcode",
                    "email" => "info@flowcode.com.ar",
                    "website" => "http://flowcode.com.ar",
                ),
            ),
        );

        $event->setPluginDescriptors($plugins);

    }
}