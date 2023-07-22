<?php

namespace Illuminate\Support\Facades;

use Illuminate\Process\Factory;
use Illuminate\Routing\UrlProtocol;

/**
 * @method static array accepted()
 * @method static void accept(...$protocol)
 * @method static void forget(...$protocol)
 *
 * @see UrlProtocol
 */
class Protocol extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'url.protocol';
    }
}
