<?php

namespace Heystack\Reports\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ModifierAdd implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('report_modifier.add') as $id => $attrs) {
            $definition = $container->getDefinition($id);
            foreach ($attrs as $attr) {
                if (isset($attr['modifier']) && $container->hasDefinition($attr['modifier'])) {
                    $definition->addMethodCall(
                        'addReportModifier',
                        [
                            new Reference($attr['modifier'])
                        ]
                    );
                }
            }
        }
    }
} 