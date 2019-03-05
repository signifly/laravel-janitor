<?php

namespace Signifly\Janitor;

use InvalidArgumentException;
use Illuminate\Support\Manager;

class JanitorManager extends Manager
{
    /**
     * Create an instance of the specified driver.
     *
     * @return \Signifly\Janitor\AbstractProxy
     */
    protected function createJwtDriver()
    {
        $config = $this->app['config']['janitor.drivers.passport'];

        return new JWTProxy($config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Signifly\Janitor\AbstractProxy
     */
    protected function createPassportDriver()
    {
        $config = $this->app['config']['janitor.drivers.passport'];

        return new PassportProxy($config);
    }

    /**
     * Get the default driver name.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Janitor driver was specified.');
    }
}
