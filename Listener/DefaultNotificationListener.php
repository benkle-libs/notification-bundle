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


namespace Benkle\NotificationBundle\Listener;


use Benkle\NotificationBundle\Event\NotificationEvent;
use Benkle\NotificationBundle\Service\PushFactory;
use Benkle\NotificationBundle\Service\SubscriptionProviderInterface;

/**
 * Class DefaultNotificationListener
 * @package Benkle\NotificationBundle\Listener
 */
class DefaultNotificationListener extends AbstractNotificationListener
{
    /** @var SubscriptionProviderInterface */
    private $subscriptionProvider;

    /** @var PushFactory */
    private $pushFactory;

    /**
     * DefaultNotificationListener constructor.
     *
     * @param PushFactory $pushFactory
     */
    public function __construct(PushFactory $pushFactory)
    {
        $this->pushFactory = $pushFactory;
    }

    /**
     * Set the subscription provider.
     *
     * @param SubscriptionProviderInterface $subscriptionProvider
     * @return $this
     */
    public function setSubscriptionProvider(SubscriptionProviderInterface $subscriptionProvider)
    {
        $this->subscriptionProvider = $subscriptionProvider;
        return $this;
    }

    /**
     * Handle a notification event.
     *
     * @param NotificationEvent $event
     * @throws \ErrorException
     */
    public function handleNotificationEvent(NotificationEvent $event)
    {
        if (isset($this->subscriptionProvider) && $event->isSendable()) {
            $webPush = $this->pushFactory->getPush();
            foreach ($this->subscriptionProvider->getSubscriptionForUser($event->getUser()) as $subscription) {
                $webPush->sendNotification(
                    $subscription->getEndpoint(),
                    json_encode($event->getPayload()),
                    $subscription->getKey(),
                    $subscription->getSecret(),
                    false,
                    $event->getOptions()
                );
            }
            $webPush->flush();
        }
    }

    public static function getPriority(): int
    {
        return -1024;
    }


}
