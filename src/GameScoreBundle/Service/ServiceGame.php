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

class ServiceGame
{
    protected $em;
    private $container;

    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function getTotalNumberOfGames() {

        return count($this->em->getRepository('GameScoreBundle:Game')->findAll());
        /*
        $qb = $this->em->createQueryBuilder();

        $qb->select('u')
            ->from('mybundleBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');

        return $qb->getQuery()->getResult();
        */
    }
}