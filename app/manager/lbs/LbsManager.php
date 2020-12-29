<?php

declare (strict_types=1);

namespace app\manager\lbs;

use app\manager\lbs\contract\FactoryContract;
use app\manager\lbs\contract\LbsContract;
use app\manager\lbs\contract\RepositoryContract;
use app\manager\lbs\driver\Tencent;
use InvalidArgumentException;
use think\App;

/**
 * Class LbsManager
 * @package app\manager\lbs
 */
class LbsManager implements FactoryContract
{
    /**
     * The application instance.
     *
     * @var App
     */
    protected $app;

    /**
     * The array of resolved cache stores.
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Create a new lbs manager instance.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Get a lbs driver instance.
     *
     * @param string|null $name
     * @return RepositoryContract
     */
    public function driver($name = null): RepositoryContract
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->drivers[$name] = $this->get($name);
    }

    /**
     * Attempt to get the store from the local cache.
     *
     * @param string $name
     * @return RepositoryContract
     */
    protected function get(string $name): RepositoryContract
    {
        return $this->drivers[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given store.
     *
     * @param string $name
     * @return RepositoryContract
     *
     * @throws InvalidArgumentException
     */
    protected function resolve(string $name): RepositoryContract
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("lbs store [{$name}] is not defined.");
        }

        $driverMethod = 'create' . ucfirst($config['driver']) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
        }
    }

    /**
     * Create an instance of the Tencent lbs driver.
     *
     * @param array $config
     * @return Repository
     */
    protected function createTencentDriver(array $config): Repository
    {
        return $this->repository(new Tencent($config));
    }

    /**
     * Create a new lbs repository with the given implementation.
     *
     * @param LbsContract $store
     * @return Repository
     */
    public function repository(LbsContract $store): Repository
    {
        return new Repository($store);
    }

    /**
     * Get the lbs configuration.
     *
     * @param string $name
     * @return array
     */
    protected function getConfig(string $name): array
    {
        return $this->app->config->get("services.lbs.{$name}");
    }

    /**
     * Get the default lbs driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->app->config->get('services.lbs.default');
    }

    /**
     * Set the default lbs driver name.
     *
     * @param string $name
     * @return void
     */
    public function setDefaultDriver(string $name)
    {
        $this->app->config->set(['lbs.default' => $name], 'services');
    }

    /**
     * Unset the given driver instances.
     *
     * @param array|string|null $name
     * @return $this
     */
    public function forgetDriver($name = null): LbsManager
    {
        $name = $name ?? $this->getDefaultDriver();

        foreach ((array)$name as $cacheName) {
            if (isset($this->drivers[$cacheName])) {
                unset($this->drivers[$cacheName]);
            }
        }

        return $this;
    }

    /**
     * Disconnect the given driver and remove from local.
     *
     * @param string|null $name
     * @return void
     */
    public function purge($name = null)
    {
        $name = $name ?? $this->getDefaultDriver();

        unset($this->drivers[$name]);
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}
