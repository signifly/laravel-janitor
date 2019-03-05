<?php

namespace Signifly\Janitor\Contracts;

interface Factory
{
    /**
     * Get an auth proxy implementation.
     *
     * @param  string  $driver
     * @return \Signifly\Janitor\Contracts\Proxy
     */
    public function driver($driver = null);
}
