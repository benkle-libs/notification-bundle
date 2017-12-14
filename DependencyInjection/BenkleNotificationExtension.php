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

use Minishlink\WebPush\VAPID;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BenkleNotificationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $providerServiceName = $config['subscriptions']['provider'];
        if ($providerServiceName) {
            $providerServiceName = ltrim($providerServiceName, '@');
            $providerService = new Reference($providerServiceName);
            $defaultListener = $container->getDefinition('benkle.notifications.listeners.default');
            $defaultListener->addMethodCall('setSubscriptionProvider', [$providerService]);
            $container->setAlias('benkle.notifications.subscriptions', $providerServiceName);
        }

        $vapid = $config['vapid'] ?? [];
        if ($vapid) {
            $vapid = VAPID::validate($vapid);
            $vapid['publicKey'] = base64_encode($vapid['publicKey']);
            $vapid['privateKey'] = base64_encode($vapid['privateKey']);
            $container->setParameter('benkle.notifications.publicKey', $vapid['publicKey']);
        }

        $defaults = $config['defaults'] ?? [];
        if (isset($defaults['ttl'])) {
            $defaults['TTL'] = $defaults['ttl'];
            unset($defaults['ttl']);
        }
        $container
            ->getDefinition('benkle.notifications.pushfactory')
            ->setArgument(0, $vapid)
            ->setArgument(1, $defaults);
    }
}
