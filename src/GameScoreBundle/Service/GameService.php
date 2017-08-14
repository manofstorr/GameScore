<?php

/**
 * Created by PhpStorm.
 * User: talend
 * Date: 13/07/2017
 * Time: 17:52
 */

namespace GameScoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;

class GameService
{
    protected $em;

    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getTotalNumberOfGames() {

        return count(
            $this
                ->em
                ->getRepository('GameScoreBundle:Game')
                ->findAll()
        );
    }
}