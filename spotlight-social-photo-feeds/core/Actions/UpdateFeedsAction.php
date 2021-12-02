<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Actions;

use RebelCode\Spotlight\Instagram\Engine\Importer;
use RebelCode\Spotlight\Instagram\Feeds\FeedManager;

class UpdateFeedsAction
{
    /** @var Importer */
    protected $importer;

    /** @var FeedManager */
    protected $feedManager;

    /** Constructor */
    public function __construct(Importer $importer, FeedManager $feedManager)
    {
        $this->importer = $importer;
        $this->feedManager = $feedManager;
    }

    public function __invoke()
    {
        $feeds = $this->feedManager->query();
        $sources = [];

        foreach ($feeds as $feed) {
            foreach ($feed->sources as $source) {
                $sources[(string) $source] = $source;
            }
        }

        $this->importer->updateSources(array_values($sources));
    }
}
