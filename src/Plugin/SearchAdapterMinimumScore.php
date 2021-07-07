<?php

/**
 * Search Improvements Module
 *
 * @author    Peter McWilliams <pmcwilliams@augustash.com>
 * @copyright Copyright (c) 2021 August Ash (https://www.augustash.com)
 */

declare(strict_types=1);
namespace Augustash\BetterSearch\Plugin;

use Augustash\BetterSearch\Api\ConfigInterface;
use Magento\Elasticsearch\Elasticsearch5\SearchAdapter\Mapper;
use Magento\Framework\Search\RequestInterface;

/**
 * Add configured minimum score to search results.
 */
class SearchAdapterMinimumScore
{
    /**
     * @var \Augustash\BetterSearch\Api\ConfigInterface
     */
    protected $config;

    /**
     * Constructor.
     *
     * Initialize class dependencies.
     *
     * @param \Augustash\BetterSearch\Api\ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Around plugin.
     *
     * @param \Magento\Elasticsearch\Elasticsearch5\SearchAdapter\Mapper $subject
     * @param \callable $proceed
     * @param \Magento\Framework\Search\RequestInterface $request
     * @return array
     */
    public function aroundBuildQuery(
        Mapper $subject,
        callable $proceed,
        RequestInterface $request
    ): array {
        $searchQuery = $proceed($request);

        if ($request->getName() === 'quick_search_container') {
            $searchQuery['body']['min_score'] = $this->config->getMinimumScore();
        }

        return $searchQuery;
    }
}
