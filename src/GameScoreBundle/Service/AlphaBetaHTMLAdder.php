<?php
/**
 * Created by PhpStorm.
 * User: talend
 * Date: 14/07/2017
 * Time: 14:18
 */

namespace GameScoreBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Container;

class AlphaBetaHTMLAdder
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function addAlphaBeta(Response $response, $remainingDays)
    {
        $mode = $this->container->getParameter('alphabetamode');
        $content = $response->getContent();
        $html = '<div class="alphaBetaBar">' . ucfirst($mode) . ' J-' . (int)$remainingDays . ' !</div>';
        $content = str_replace(
            '<body>',
            '<body> ' . $html,
            $content
        );
        $response->setContent($content);
        return $response;
    }
}