<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Observe;

/**
 * RL\MainBundle\EventListener\LocaleListener
 *
 * @Service("rl_main.locale.listener")
 */
class LocaleListener
{
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * Constructor
     *
     * @param string $defaultLocale
     */
    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @Observe("kernel.request", priority = 17)
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }
}
