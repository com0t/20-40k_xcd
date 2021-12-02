<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Modules;

use Dhii\Services\Factories\Constructor;
use Psr\Container\ContainerInterface;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\Spotlight\Instagram\Server;

class ServerModule extends Module
{
    public function run(ContainerInterface $c)
    {
    }

    public function getFactories()
    {
        return [
            'instance' => new Constructor(Server::class, ['@engine/instance', '@updater/importer', '@feeds/manager']),
        ];
    }

    public function getExtensions()
    {
        return [];
    }
}
