<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2017 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Bundle\JoseFramework\DependencyInjection;

use Jose\Bundle\JoseFramework\DependencyInjection\Source\SourceInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @var SourceInterface[]
     */
    private $serviceSources;

    /**
     * @var string
     */
    private $alias;

    /**
     * Configuration constructor.
     *
     * @param string            $alias
     * @param SourceInterface[] $serviceSources
     */
    public function __construct(string $alias, array $serviceSources)
    {
        $this->alias = $alias;
        $this->serviceSources = $serviceSources;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->alias);

        foreach ($this->serviceSources as $serviceSource) {
            $serviceSource->getNodeDefinition($rootNode);
        }

        $rootNode
            ->children()
                ->booleanNode('use_default_json_converter')
                    ->defaultTrue()
                    ->info('Convert used to encode and decode JSON objects (JWT payloads, keys, key sets...). If set to false, a service that implements JsonConverterInterface must be set.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}