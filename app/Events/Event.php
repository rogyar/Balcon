<?php

namespace App\Events;

class Event
{
    /** @var  \App\Core\Balcon */
    protected $balcon;

    /**
     * @param \App\Core\Balcon $balcon
     */
    public function __construct($balcon)
    {
        $this->balcon = $balcon;
    }

    /**
     * @return \App\Core\Balcon
     */
    public function getContext()
    {
        return $this->balcon;
    }
}
