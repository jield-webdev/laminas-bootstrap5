<?php

namespace LaminasBootstrap5;

use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\DependencyIndicatorInterface;
use Laminas\ServiceManager\ServiceManager;
use LaminasBootstrap5\Event\InjectJavascriptAndCss;

/**
 * Class Module
 * @package LaminasBootstrap5
 */
final class Module implements ConfigProviderInterface, BootstrapListenerInterface, DependencyIndicatorInterface
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e): void
    {
        $app = $e->getParam('application');
        /** @var ServiceManager $sm */
        $sm = $app->getServiceManager();

        $setTitle = $sm->get(InjectJavascriptAndCss::class);
        $setTitle->attach($app->getEventManager());
    }

    public function getModuleDependencies(): array
    {
        return ['AssetManager'];
    }
}
