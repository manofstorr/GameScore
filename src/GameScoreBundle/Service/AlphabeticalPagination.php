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
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function getAlphabeticalPagination($entityname)
    {
        $alphapageArray = array();
        switch ($entityname) {
            case 'game' :
                $em = $this->em->getRepository('GameScoreBundle:Game');
                $collection = $em->getAllGames();
                foreach ($collection as $item) {
                    $index = strtolower(substr($item->getName(), 0, 1));
                    if (!in_array($index, $alphapageArray)) {
                        $alphapageArray[] = $index;
                    }
                }
                break;
            case 'player' :
                break;
        }
        return $alphapageArray;
    }
}