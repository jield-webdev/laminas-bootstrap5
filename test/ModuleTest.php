<?php

declare(strict_types=1);

namespace LaminasBootstrap5Test;

use Jield\Search\Module;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Testing\Util\AbstractServiceTest;

class ModuleTest extends AbstractServiceTest
{
    public function testCanFindConfiguration(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        self::assertArrayHasKey('service_manager', $config);
        self::assertArrayHasKey(ConfigAbstractFactory::class, $config);
    }

    public function testInstantiationOfConfigAbstractFactories(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        $abstractFactories = $config[ConfigAbstractFactory::class] ?? [];

        foreach ($abstractFactories as $service => $dependencies) {
            $instantiatedDependencies = [];
            foreach ($dependencies as $dependency) {
                $instantiatedDependencies[]
                    = $this->getMockBuilder($dependency)->disableOriginalConstructor()->getMock();
            }

            $instance = new $service(...$instantiatedDependencies);

            self::assertInstanceOf($service, $instance);
        }
    }
}
