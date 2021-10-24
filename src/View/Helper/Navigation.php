<?php

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

    protected array $defaultPluginManagerHelpers
        = [
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
