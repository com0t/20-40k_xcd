<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Modules;

use Dhii\Services\Extension;
use Dhii\Services\Factories\Constructor;
use Dhii\Services\Factories\ServiceList;
use Dhii\Services\Factories\Value;
use Dhii\Services\Factory;
use Psr\Container\ContainerInterface;
use RebelCode\Spotlight\Instagram\Actions\UpdateAccountsAction;
use RebelCode\Spotlight\Instagram\Actions\UpdateFeedsAction;
use RebelCode\Spotlight\Instagram\Config\ConfigEntry;
use RebelCode\Spotlight\Instagram\Config\WpOption;
use RebelCode\Spotlight\Instagram\Di\ArrayExtension;
use RebelCode\Spotlight\Instagram\Di\ConfigService;
use RebelCode\Spotlight\Instagram\Engine\Importer;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\Spotlight\Instagram\Wp\CronJob;

class UpdateCronModule extends Module
{
    /** The config key for the update interval setting. */
    const CONFIG_UPDATE_INTERVAL = 'importerInterval';

    public function run(ContainerInterface $c)
    {
        add_action('init', function () {
            // Unschedule old crons
            wp_unschedule_hook('spotlight/instagram/import_media');
            wp_unschedule_hook('spotlight/instagram/import');
        });

        // Register the batch handler
        // We don't use the WpModule's API for this because the cron is not auto-scheduled, but scheduled on-demand
        add_action($c->get('batch/cron/hook'), $c->get('batch/cron/handler'));
    }

    public function getFactories()
    {
        return [
            //==========================================================================
            // CRON JOB
            //==========================================================================

            'importer' => new Constructor(Importer::class, [
                '@engine/instance',
                'batch/size',
                'batch/delay',
                'batch/cron/hook',
            ]),

            //==========================================================================
            // BATCH IMPORTING
            //==========================================================================

            'batch/size' => new Value(50),
            'batch/delay' => new Value(5),
            'batch/cron/hook' => new Value('spotlight/instagram/import_batch'),
            'batch/cron/handler' => new Factory(['importer'], function (Importer $importer) {
                return [$importer, 'importBatch'];
            }),

            //==========================================================================
            // MAIN UPDATE CRON
            //==========================================================================

            'main/hook' => new Value('spotlight/instagram/update'),
            'main/args' => new Value([]),
            'main/repeat' => new ConfigService('@config/set', static::CONFIG_UPDATE_INTERVAL),

            // The cron handler for updating account info
            'main/handlers/accounts' => new Constructor(UpdateAccountsAction::class, [
                '@ig/api/client',
                '@accounts/cpt',
            ]),

            // The cron handler for fetching media for feeds
            'main/handlers/feeds' => new Constructor(UpdateFeedsAction::class, ['importer', '@feeds/manager']),

            // The list of handlers for the cron
            'main/handlers' => new ServiceList([
                'main/handlers/accounts',
                'main/handlers/feeds',
            ]),

            // The cron job instance
            'main/job' => new Constructor(CronJob::class, [
                'main/hook',
                'main/args',
                'main/repeat',
                'main/handlers',
            ]),

            //==========================================================================
            // CONFIG ENTRIES
            //==========================================================================

            // The config entry that stores the cron's repetition interval
            'config/interval' => new Value(new WpOption('sli_importer_interval', 'hourly')),
        ];
    }

    public function getExtensions(): array
    {
        return [
            // Register the cron job
            'wp/cron_jobs' => new ArrayExtension([
                'main/job',
            ]),
            // Register the config entries
            'config/entries' => new ArrayExtension([
                static::CONFIG_UPDATE_INTERVAL => 'config/interval',
            ]),
            // Override the API cache with the value of the import cron interval option
            'ig/cache/ttl' => new Extension(['config/interval'], function ($ttl, ConfigEntry $interval) {
                return $interval->getValue();
            }),
        ];
    }
}
