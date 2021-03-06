<?php
/*
 * Copyright (c) 2017 Benjamin Kleiner
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Benkle\NotificationBundle\DependencyInjection;

use Benkle\NotificationBundle\Event\NotificationEvent;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('benkle_notification');

        $rootNode
            ->children()
            ->arrayNode('subscriptions')
            ->children()
            ->scalarNode('provider');

        $this->buildVapidConfig($rootNode->children());
        $this->buildDefaultConfig($rootNode->children());

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     */
    private function buildVapidConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->arrayNode('vapid')
            ->children()
            ->scalarNode('subject')
            ->isRequired()
            ->end()
            ->scalarNode('publicKey')
            ->defaultNull()
            ->end()
            ->scalarNode('privateKey')
            ->defaultNull()
            ->end()
            ->scalarNode('pemFile')
            ->defaultNull()
            ->end()
            ->scalarNode('pem')
            ->defaultNull()
            ->end()
            ->end();
    }

    /**
     * @param NodeBuilder $nodeBuilder
     */
    private function buildDefaultConfig(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder
            ->arrayNode('defaults')
            ->children()
            ->scalarNode('ttl')
            ->defaultValue(4 * 7 * 24 * 3600)
            ->end()
            ->scalarNode('urgency')
            ->defaultValue(NotificationEvent::URGENCY_NORMAL)
            ->end()
            ->scalarNode('topic')
            ->defaultNull()
            ->end()
            ->scalarNode('batchSize')
            ->defaultValue(1000)
            ->end()
            ->end();
    }
}
