<?php

namespace LaravelSuperBan\SuperBan\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelSuperBan\SuperBan\SuperBan
 */
class SuperBan extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \LaravelSuperBan\SuperBan\SuperBan::class;
    }
}
