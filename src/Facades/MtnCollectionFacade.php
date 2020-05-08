<?php

namespace PatricPoba\MtnMomo\Facades;

use Illuminate\Support\Facades\Facade;


class MtnCollectionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mtn-momo-collection';
    }
}
