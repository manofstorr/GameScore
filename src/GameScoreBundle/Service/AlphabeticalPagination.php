<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 28/07/2017
 * Time: 16:36
 */

namespace GameScoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class AlphabeticalPagination
{
    protected $em;
    private $container;

    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getAlphabeticalPagination($entityname)
    {
        $alphapageArray = array();
        switch ($entityname) {
            case 'game' :
                $em = $this->em->getRepository('GameScoreBundle:Game');
                $items = $em->getAllGames();
                foreach ($items as $item) {
                    $index = strtolower(substr($item->getName(), 0, 1));
                    if (!in_array($index, $alphapageArray)) {
                        $alphapageArray[] = $index;
                    }
                }
                break;
            case 'player' :
                $em = $this->em->getRepository('GameScoreBundle:Player');
                $items  = $em->findBy(array(), array('firstname' => 'ASC'));
                foreach ($items as $item) {
                    $index = strtolower(substr($item->getFirstname(), 0, 1));
                    if (!in_array($index, $alphapageArray)) {
                        $alphapageArray[] = $index;
                    }
                }
                break;
            case 'author' :
                $em = $this->em->getRepository('GameScoreBundle:Author');
                $items  = $em->findBy(array(), array('lastname' => 'ASC'));
                foreach ($items as $item) {
                    $index = strtolower(substr($item->getLastname(), 0, 1));
                    if (!in_array($index, $alphapageArray)) {
                        $alphapageArray[] = $index;
                    }
                }
                break;
        }
        return $alphapageArray;
    }
}

