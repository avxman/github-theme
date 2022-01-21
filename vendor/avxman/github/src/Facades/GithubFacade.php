<?php

namespace Avxman\Github\Facades;

use Avxman\Github\Providers\GithubServiceProvider;
use Illuminate\Support\Facades\Facade;

/**
 * Фасад github
 *
 * @see
 */
class GithubFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GithubServiceProvider::class;
    }

}
