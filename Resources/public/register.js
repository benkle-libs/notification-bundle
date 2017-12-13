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

let encode = (x) => btoa(String.fromCharCode.apply(null, new Uint8Array(x)));

navigator.serviceWorker.register('bundles/benklenotification/service-worker.js')
    .then(function (registration) {
        return registration.pushManager.getSubscription()
            .then(function (subscription) {
                if (subscription) {
                    return subscription;
                }
                return registration.pushManager.subscribe({userVisibleOnly: true});
            });
    }).then(function (subscription) {
    let rawKey = subscription.getKey ? subscription.getKey('p256dh') : '';
    let key = rawKey ? encode(rawKey) : '';
    let rawSecret = subscription.getKey ? subscription.getKey('auth') : '';
    let secret = rawSecret ? encode(rawSecret) : '';
    return fetch('/notifications/register', {
        method: 'post',
        credentials: 'include',
        headers: {
            'Content-type': 'application/json'
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint,
            key: key,
            secret: secret,
        }),
    });
});
