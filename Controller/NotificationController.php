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


namespace Benkle\NotificationBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NotificationController
 * @package Benkle\NotificationBundle\Controller
 */
class NotificationController extends Controller
{
    public function registerAction(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $endpoint = $body['endpoint'] ?? false;
        $key = $body['key'] ?? false;
        $secret = $body['secret'] ?? false;
        if ($endpoint && $secret && $key) {
            try {
                $subscriptionProvider = $this->get('benkle.notifications.subscriptions');
                $subscription = $subscriptionProvider->create();
                $subscription
                    ->setEndpoint($endpoint)
                    ->setKey($key)
                    ->setSecret($secret);
                $subscriptionProvider->persist($subscription);
                return $this->json(['success' => true]);
            } catch (\Throwable $e) {
                return $this->json(
                    [
                        'success' => false,
                        'error'   => $e->getMessage(),
                        'code'    => $e->getCode(),
                    ], 500 + $e->getCode() % 100
                );
            }
        } else {
            $missing = compact($endpoint, $secret, $key);
            $missing = array_filter(
                $missing, function ($x) {
                return !$x;
            }
            );
            return $this->json(
                [
                    'success' => false,
                    'error'   => 'missing ' . implode(', ', $missing),
                    'code'    => 555,
                ], 555
            );
        }
    }
}
