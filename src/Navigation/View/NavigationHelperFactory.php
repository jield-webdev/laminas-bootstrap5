<?php

namespace LaminasBootstrap5\Navigation\View;

use Laminas\ServiceManager\Factory\FactoryInterface;
use LaminasBootstrap5\View\Helper\Navigation;
use Psr\Container\ContainerInterface;

/**
 * Class NavigationHelperFactory
 * @package LaminasBootstrap5\Navigation\View
 */
final class NavigationHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Navigation
    {
        $helper = new Navigation();
        $helper->setServiceLocator($container);

        return $helper;
    }
}
