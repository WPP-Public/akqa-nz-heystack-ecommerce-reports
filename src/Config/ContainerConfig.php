<?php

namespace Heystack\Reports\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Reports\Config
 */
class ContainerConfig implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('reports');

        $rootNode
            ->children()
            ->end();

        return $treeBuilder;
    }
}
