<?php
/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace LaminasBootstrap5\View\Helper;

use Laminas\View\Helper\Navigation as LaminasNavigation;
use LaminasBootstrap5\View\Helper;

/**
 * Class Navigation
 * @package LaminasBootstrap5\View\Helper
 */
class Navigation extends LaminasNavigation
{
    protected $defaultProxy = 'lbs5menu';

    protected $defaultPluginManagerHelpers
        = [
            'zf3b4menu'    => Helper\Navigation\Menu::class, //Legacy
            'zf3b4submenu'    => Helper\Navigation\Menu::class, //legacy

            'lbs5menu'    => Helper\Navigation\Menu::class,
            'lbs5submenu' => Helper\Navigation\SubMenu::class,
        ];

    public function getPluginManager(): LaminasNavigation\PluginManager
    {
        $pm = parent::getPluginManager();
        foreach ($this->defaultPluginManagerHelpers as $name => $invokableClass) {
            $pm->setAllowOverride(true);
            $pm->setInvokableClass($name, $invokableClass);
        }

        return $pm;
    }
}