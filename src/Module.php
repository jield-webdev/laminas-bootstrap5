<?php

namespace LaminasBootstrap5;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class Module
 * @package LaminasBootstrap5
 */
final class Module implements ConfigProviderInterface
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
