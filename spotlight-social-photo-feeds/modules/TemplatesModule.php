<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Modules;

use Dhii\Services\Factories\Constructor;
use Dhii\Services\Factories\StringService;
use Dhii\Services\Factories\Value;
use Dhii\Services\Factory;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheException;
use RebelCode\Psr7\Uri;
use RebelCode\Spotlight\Instagram\Feeds\Templates\FeedTemplatesProvider;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\WordPress\Http\WpClient;
use wpdb;
use WpOop\TransientCache\CachePool;

class TemplatesModule extends Module
{
    public function run(ContainerInterface $c)
    {
        add_action('spotlight/instagram/rest_api/clear_cache', function () use ($c) {
            /** @var $cache CachePool */
            $cache = $c->get('cache');
            try {
                $cache->clear();
            } catch (CacheException $e) {
                // Fail silently
            }
        });
    }

    public function getFactories()
    {
        return [
            // Client
            'client' => new Factory(['client/base_url', 'client/options'], function ($url, $options) {
                return WpClient::createDefault(new Uri($url), $options);
            }),
            'client/base_url' => new StringService('{0}/templates', ['@saas/server/base_url']),
            'client/options' => new Value(['timeout' => 10]),
            // Cache
            'cache/key' => new Value('templates.remote'),
            'cache' => new Factory(['@wp/db',], function (wpdb $wpdb) {
                return new CachePool($wpdb, 'sli_templates', uniqid('sli_templates'), 3600);
            }),
            // Provider
            'provider' => new Constructor(FeedTemplatesProvider::class, ['client', 'cache/key', 'cache']),
        ];
    }

    public function getExtensions()
    {
        return [];
    }
}
