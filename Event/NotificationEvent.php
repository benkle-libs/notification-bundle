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


namespace Benkle\NotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class NotificationEvent
 * @package Benkle\NotificationBundle\Event
 */
class NotificationEvent extends Event
{
    const NAME = 'benkle.notification.event';

    const URGENCY_HIGH     = 'high';
    const URGENCY_NORMAL   = 'normal';
    const URGENCY_LOW      = 'low';
    const URGENCY_VERY_LOW = 'very-low';

    /** @var  UserInterface */
    private $user;

    /** @var  mixed */
    private $payload;

    /** @var  string */
    private $urgency = self::URGENCY_NORMAL;

    /** @var int */
    private $ttl = null;

    /** @var string */
    private $topic = null;

    /** @var bool */
    private $sendable = true;

    /**
     * Get the user associated with this notification.
     *
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * Get the sendability of the notification.
     *
     * @return bool
     */
    public function isSendable(): bool
    {
        return $this->sendable;
    }

    /**
     * Set the sendability of the notification.
     * You can use this to make your application less verbose to the user
     * while still being able to log and debug.
     *
     * @param bool $sendable
     * @return $this
     */
    public function setSendable(bool $sendable)
    {
        $this->sendable = $sendable;
        return $this;
    }

    /**
     * Get the notification's payload.
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set the notification's payload.
     * Best to use something that can be serialized to JSON.
     *
     * @param mixed $payload
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Get the notification's urgency.
     *
     * @return string
     */
    public function getUrgency(): string
    {
        return $this->urgency;
    }

    /**
     * Set the notification's urgency.
     *
     * @param string $urgency
     * @return $this
     */
    public function setUrgency(string $urgency)
    {
        $this->urgency = $urgency;
        return $this;
    }

    /**
     * Set the notification's time to live.
     *
     * @return int
     */
    public function getTTL(): int
    {
        return $this->ttl;
    }

    /**
     * Set the notification's time to live.
     *
     * @param int $ttl
     * @return $this
     */
    public function setTTL(int $ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    /**
     * Get the notification's topic.
     *
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * Set the notification's topic.
     *
     * @param string $topic
     * @return $this
     */
    public function setTopic(string $topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * Get the notification options for use with web-push.
     *
     * @return array
     */
    public function getOptions(): array
    {
        $result = [
            'TTL'     => $this->ttl,
            'urgency' => $this->urgency,
            'topic'   => $this->topic,
        ];
        return array_filter($result);
    }

    /**
     * NotificationEvent constructor.
     *
     * @param UserInterface $user
     * @param mixed $payload
     */
    public function __construct(UserInterface $user, $payload = [])
    {
        $this->user = $user;
        $this->payload = $payload;
    }

    /**
     * Dispatch event.
     *
     * @param EventDispatcherInterface $dispatcher
     * @return Event
     */
    public function dispatchTo(EventDispatcherInterface $dispatcher): Event
    {
        return $dispatcher->dispatch(self::NAME, $this);
    }
}
