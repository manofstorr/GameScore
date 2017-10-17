<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 12/10/2017
 * Time: 11:10
 */

namespace GameScoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatsController extends Controller
{

    public function indexAction()
    {
        // get playing trend per month
        $dateFrom   = '2016-01-01 00:00:00';
        $dateNow     = new \DateTime();
        $dateTo     = $dateNow->format('Y-m-d H:i:s');

        $playService = $this->container->get('play_service');
        $trend = $playService->getPlayingTrend($dateFrom, $dateTo);

        return $this->render(
            'GameScoreBundle:Stats:index.html.twig',
            ['trend' => $trend]
        );
    }

}
