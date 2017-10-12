<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 12/10/2017
 * Time: 15:47
 */

namespace GameScoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class HomePageLoadEvent extends Event
{
    protected $date;
    protected $user;

    public function __construct($user)
    {
        $this->date = new \DateTime('now');
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return (string) $this->date;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

}
