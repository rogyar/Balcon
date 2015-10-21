<?php

namespace App\Events;

class RouteResolverRegisteredAfter extends Event
{
    /** @var  \App\Core\Balcon */
    protected $balcon;

    /**
     * @param \App\Core\Balcon $balcon
     */
    public function __construct($balcon)
    {
        $this->app = $balcon;
    }

    /**
     * @return \App\Core\Balcon
     */
    public function getContext()
    {
        return $this->balcon;
    }
}
