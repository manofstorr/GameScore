<?php
/**
 * Created by PhpStorm.
 * User: talend
 * Date: 14/07/2017
 * Time: 14:33
 */

namespace GameScoreBundle\Listener;

use GameScoreBundle\Service\AlphaBetaHTMLAdder;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class AlphaBetaListener
{
    protected $betaHTML;
    protected $endDate;

    public function __construct(AlphaBetaHTMLAdder $AlphaBetaHTML, $endDate)
    {
        $this->alphaBetaHTML = $AlphaBetaHTML;
        $this->endDate = new \Datetime($endDate);
    }

    public function processBeta(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $remainingDays = $this->endDate->diff(new \Datetime())->days;

        // Si la date est dépassée, on ne fait rien
        if ($remainingDays <= 0) {
            return;
        }

        // On utilise notre BetaHRML
        $response = $this->alphaBetaHTML->addAlphaBeta($event->getResponse(), $remainingDays);

        // On met à jour la réponse avec la nouvelle valeur
        $event->setResponse($response);
    }
}