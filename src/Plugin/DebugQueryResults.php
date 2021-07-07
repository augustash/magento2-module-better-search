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
use Magento\Elasticsearch\SearchAdapter\ResponseFactory;

/**
 * Debug Elasticsearch query relevance.
 */
class DebugQueryResults
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
     * Before plugin.
     *
     * @param \Magento\Elasticsearch\SearchAdapter\ResponseFactory $subject
     * @param array $result
     * @return array|null
     */
    public function beforeCreate(ResponseFactory $subject, array $result): ?array
    {
        if (!\is_array($result) || empty($result)) {
            return null;
        }

        if ($this->config->isDebugScores()) {
            $scores=[];

            foreach ($result['documents'] as $rawDocument) {
                $this->config->getLogger()->debug('ELASTIC_SEARCH_QUERY_RESULTS', $rawDocument);
                \array_push($scores, $rawDocument['_score']);
            }

            if (\count($result['documents']) > 0) {
                $debug = [
                    'max_score' => \max($scores),
                    'min_relevance_score' => $this->config->getMinimumScore(),
                    'min_score' => \min($scores),
                    'results' => \count($result['documents']),
                    'scores' => $scores,
                ];

                $this->config->getLogger()->debug('ELASTIC_SEARCH_QUERY_RESULTS', $debug);
            }
        }

        return null;
    }
}
