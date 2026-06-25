<?php

declare(strict_types=1);

namespace App\Plugins\Hello;

use Juling\Foundation\Plugins\PluginsContract;

class HelloPlugins implements PluginsContract
{
    public function getInfo(): array
    {
        return [];
    }

    public function install(): bool
    {
        return true;
    }

    public function uninstall(): bool
    {
        return true;
    }

    public function upgrade(): bool
    {
        return true;
    }

    public function enable(): bool
    {
        return true;
    }

    public function disable(): bool
    {
        return true;
    }

    public function getConfig(): array
    {
        return [];
    }

    public function setConfig(array $config): bool
    {
        return true;
    }
}
