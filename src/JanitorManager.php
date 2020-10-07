<?php

namespace Signifly\Janitor;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use Signifly\Janitor\Contracts\Factory;

class JanitorManager extends Manager implements Factory
{
    /**
     * Create an instance of the specified driver.
     *
     * @return \Signifly\Janitor\AbstractProxy
     */
    protected function createJwtDriver()
    {
        $config = $this->container['config']['janitor.drivers.jwt'];

        return new JWTProxy($config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Signifly\Janitor\AbstractProxy
     */
    protected function createPassportDriver()
    {
        $config = $this->container['config']['janitor.drivers.passport'];

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
