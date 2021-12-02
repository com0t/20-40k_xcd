<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Engine\Aggregator;

use RebelCode\Iris\Aggregator\ItemProcessor;
use RebelCode\Iris\Data\Feed;
use RebelCode\Iris\Store\Query;
use RebelCode\Spotlight\Instagram\Engine\Data\Feed\StoryFeed;
use RebelCode\Spotlight\Instagram\Engine\Data\Item\MediaItem;
use RebelCode\Spotlight\Instagram\Utils\Functions;

class FeedPostFilterProcessor implements ItemProcessor
{
    public function process(array &$items, Feed $feed, Query $query): void
    {
        if ($feed->get('mediaType') !== StoryFeed::MEDIA_TYPE) {
            $items = array_filter($items, Functions::not([MediaItem::class, 'isStory']));
        }
    }
}
