<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 12/10/2017
 * Time: 15:11
 */

namespace GameScoreBundle\Listener;

// use GameScoreBundle\Service\TheServiceToUse;
// use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/*
 * The goal of this class is to be a sample for personal listener implementation.
 * In use :
 * - Personal event
 *      Event names are declared in src/GameScoreBundle/Event/GameScoreEventName.php
 * - Event triggering
 *      here event will be triggered on src/GameScoreBundle/Controller/GameScoreController.php:indexAction
 * - Listener (this)
 *      Has to be declared has a service
 *      And use another service to do some stuff
 *
 * here we gonna write in a file the date when homepage is loaded
 */

use GameScoreBundle\Event\HomePageLoadEvent;
use GameScoreBundle\Service\LoadPageLogWriter;

class LoadPageListener
{
    protected $loadPageLogWriter;

    public function __construct(LoadPageLogWriter $loadPageLogWriter)
    {
        $this->loadPageLogWriter = $loadPageLogWriter;
    }

    public function onLoadProcess(HomePageLoadEvent $event)
    {
        $this->loadPageLogWriter->test();
    }

}