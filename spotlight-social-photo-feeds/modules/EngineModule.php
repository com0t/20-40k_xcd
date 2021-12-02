<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Modules;

use Dhii\Services\Factories\Constructor;
use Dhii\Services\Factories\ServiceList;
use Dhii\Services\Factories\Value;
use Dhii\Services\Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use RebelCode\Iris\Aggregator;
use RebelCode\Iris\Converter;
use RebelCode\Iris\Engine;
use RebelCode\Iris\Fetcher;
use RebelCode\Iris\Fetcher\Catalog;
use RebelCode\Spotlight\Instagram\Engine\Aggregator\CustomMediaPreProcessor;
use RebelCode\Spotlight\Instagram\Engine\Aggregator\FeedPostFilterProcessor;
use RebelCode\Spotlight\Instagram\Engine\Aggregator\IgAggregationStrategy;
use RebelCode\Spotlight\Instagram\Engine\Aggregator\SortProcessor;
use RebelCode\Spotlight\Instagram\Engine\Converter\IgConversionStrategy;
use RebelCode\Spotlight\Instagram\Engine\Data\Source\UserSource;
use RebelCode\Spotlight\Instagram\Engine\Fetcher\AccountPostsCatalog;
use RebelCode\Spotlight\Instagram\Engine\Fetcher\IgFetchStrategy;
use RebelCode\Spotlight\Instagram\Engine\Fetcher\NullCatalog;
use RebelCode\Spotlight\Instagram\Engine\IgPostStore;
use RebelCode\Spotlight\Instagram\Engine\Store\ThumbnailRecipe;
use RebelCode\Spotlight\Instagram\Engine\Store\ThumbnailStore;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\Spotlight\Instagram\Wp\PostType;

class EngineModule extends Module
{
    public function run(ContainerInterface $c)
    {
    }

    public function getFactories()
    {
        return [
            //==========================================================================
            // ENGINE
            //==========================================================================

            'instance' => new Constructor(Engine::class, [
                'fetcher',
                'converter',
                'aggregator',
                'store',
            ]),

            //==========================================================================
            // FETCHER
            //==========================================================================

            'fetcher' => new Constructor(Fetcher::class, [
                'fetcher/strategy',
            ]),

            'fetcher/strategy' => new Constructor(IgFetchStrategy::class, [
                'fetcher/strategy/catalog_map',
            ]),

            'fetcher/strategy/catalog_map' => new Factory(
                ['fetcher/catalog/account'],
                function (Catalog $account) {
                    return [
                        UserSource::TYPE_PERSONAL => $account,
                        UserSource::TYPE_BUSINESS => $account,
                    ];
                }
            ),

            'fetcher/catalog/account' => new Factory(
                ['@ig/client', '@accounts/cpt', 'fetcher/catalog/stories'],
                function (ClientInterface $client, PostType $accounts, ?Catalog $storyCatalog) {
                    return new AccountPostsCatalog($client, $accounts, $storyCatalog);
                }
            ),

            'fetcher/catalog/stories' => new Value(null),

            'fetcher/catalog/fallback' => new Constructor(NullCatalog::class),

            //==========================================================================
            // CONVERTER
            //==========================================================================

            'converter' => new Constructor(Converter::class, [
                'store',
                'converter/strategy',
            ]),

            'converter/strategy' => new Constructor(IgConversionStrategy::class),

            //==========================================================================
            // AGGREGATOR
            //==========================================================================

            'aggregator' => new Constructor(Aggregator::class, [
                'store',
                'aggregator/strategy',
            ]),

            'aggregator/strategy' => new Constructor(IgAggregationStrategy::class, [
                'aggregator/pre_processors',
                'aggregator/post_processors',
            ]),

            'aggregator/pre_processors' => new ServiceList([
                'aggregator/processors/custom_media',
                'aggregator/processors/sorter',
                'aggregator/processors/feed_post_filter',
            ]),

            'aggregator/post_processors' => new ServiceList([]),

            'aggregator/processors/custom_media' => new Constructor(CustomMediaPreProcessor::class),
            'aggregator/processors/feed_post_filter' => new Constructor(FeedPostFilterProcessor::class),
            'aggregator/processors/sorter' => new Constructor(SortProcessor::class),

            //==========================================================================
            // STORE
            //==========================================================================

            'store' => new Constructor(IgPostStore::class, [
                '@media/cpt/slug',
                'store/thumbnails',
            ]),

            'store/thumbnails' => new Constructor(ThumbnailStore::class, [
                'store/thumbnails/directory',
                'store/thumbnails/recipes',
            ]),

            'store/thumbnails/directory' => new Value('spotlight-insta'),

            'store/thumbnails/recipes' => new Value([
                ThumbnailStore::SIZE_SMALL => new ThumbnailRecipe(400, 80),
                ThumbnailStore::SIZE_MEDIUM => new ThumbnailRecipe(600, 90),
            ]),
        ];
    }
}
