<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DumperServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dumper'; // This should match the binding in the service provider
    }
}
