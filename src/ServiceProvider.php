<?php
/**
 * This file is part of lanxr/ip-locating.
 *
 * (c) lanxr <lxr4437@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Lanxr\Locating;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(IPLocating::class, function () {
            return new IPLocating(config('services.locating.key'));
        });

        $this->app->alias(IPLocating::class, 'ipLocating');
    }

    public function provides()
    {
        return [IPLocating::class, 'ipLocating'];
    }
}