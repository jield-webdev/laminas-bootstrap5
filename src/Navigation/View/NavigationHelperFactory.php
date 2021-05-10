<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\Navigation\View;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use LaminasBootstrap5\View\Helper\Navigation;

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
