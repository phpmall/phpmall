<?php

declare(strict_types=1);

namespace app\manager\lbs\contract;

/**
 * Interface FactoryContract
 * @package app\manager\lbs\contract
 */
interface FactoryContract
{
    /**
     * Get a lbs driver instance by name.
     *
     * @param string|null $name
     * @return RepositoryContract
     */
    public function driver($name = null): RepositoryContract;
}
