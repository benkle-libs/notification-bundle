<?php
/*
 * Copyright (c) 2018 Benjamin Kleiner
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


namespace Benkle\NotificationBundle\Util;


/**
 * Class Payload
 *
 * @package Benkle\NotificationBundle\Util
 */
class Payload implements \JsonSerializable
{
    /** @var Action[] */
    private $actions = [];

    /** @var string */
    private $badge;

    /** @var string */
    private $body;

    const DIR_AUTO = 'auto';
    const DIR_LTR  = 'ltr';
    const DIR_RTL  = 'rtl';

    /** @var string */
    private $dir = self::DIR_AUTO;

    /** @var string */
    private $icon;

    /** @var string */
    private $image;

    /** @var string */
    private $lang;

    /** @var bool */
    private $renotify = false;

    /** @var bool */
    private $requireInteraction = false;

    /** @var string */
    private $tag;

    /** @var int[] */
    private $vibrate = [];

    /** @var mixed */
    private $data;

    /** @var string */
    private $title;

    /**
     * Get the badge url.
     *
     * @return string
     */
    public function getBadge(): string
    {
        return $this->badge;
    }

    /**
     * Set the badge url.
     *
     * @param string $badge
     * @return $this
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
        return $this;
    }

    /*
     * Get the message body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set the message body.
     *
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get the text direction.
     *
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * Set the text direction.
     *
     * @param string $dir
     * @return $this
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
        return $this;
    }

    /**
     * Get the icon url.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Set the icon url.
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Get the image url.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the image url.
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get the BCP 47 language code.
     *
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * Set the BCP 47 language code.
     *
     * @param string $lang
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Get whether to renotify.
     *
     * @return bool
     */
    public function isRenotify(): bool
    {
        return $this->renotify;
    }

    /**
     * Set whether to renotify.
     *
     * @param bool $renotify
     * @return $this
     */
    public function setRenotify($renotify)
    {
        $this->renotify = $renotify;
        return $this;
    }

    /**
     * Get whether an action is required.
     *
     * @return bool
     */
    public function isRequireInteraction(): bool
    {
        return $this->requireInteraction;
    }

    /**
     * Set whether an action is required.
     *
     * @param bool $requireInteraction
     * @return $this
     */
    public function setRequireInteraction(bool $requireInteraction)
    {
        $this->requireInteraction = $requireInteraction;
        return $this;
    }

    /**
     * Get the tag.
     *
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Set the tag.
     *
     * @param string $tag
     * @return $this
     */
    public function setTag(string $tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Get the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the data.
     *
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Add an action.
     *
     * @param Action $action
     * @return Payload
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;
        return $this;
    }

    /**
     * Clear actions.
     *
     * @return $this
     */
    public function clearActions()
    {
        $this->actions = [];
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array_filter(
            [
                'actions'            => $this->actions,
                'badge'              => $this->badge,
                'body'               => $this->body,
                'dir'                => $this->dir,
                'icon'               => $this->icon,
                'image'              => $this->image,
                'lang'               => $this->lang,
                'renotify'           => $this->renotify,
                'requireInteraction' => $this->requireInteraction,
                'tag'                => $this->tag,
                'vibrate'            => $this->vibrate,
                'data'               => $this->data,
                'title'              => $this->title,
            ], function ($v) {
            return is_bool($v) || !empty($v);
        }
        );
    }
}
