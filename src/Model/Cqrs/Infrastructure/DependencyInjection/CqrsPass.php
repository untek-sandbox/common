<?php

namespace Untek\Model\Cqrs\Infrastructure\DependencyInjection;

use ReflectionMethod;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;

// todo: задокументировать декларацию обработчиков команд

class CqrsPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(CommandBusConfiguratorInterface::class);
        $taggedServices = $container->findTaggedServiceIds('cqrs.handler');
        foreach ($taggedServices as $handlerClass => $tags) {
            $commandClass = $this->getCommandClass($handlerClass);
            $definition->addMethodCall('define', [$commandClass, $handlerClass]);
        }
    }

    private function getCommandClass(string $handlerId): string
    {
        $ref = new ReflectionMethod($handlerId, '__invoke');
        return $ref->getParameters()[0]->getType()->getName();
    }
}
