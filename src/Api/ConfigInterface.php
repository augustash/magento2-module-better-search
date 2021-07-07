<?php

/**
 * Search Improvements Module
 *
 * @author    Peter McWilliams <pmcwilliams@augustash.com>
 * @copyright Copyright (c) 2021 August Ash (https://www.augustash.com)
 */

declare(strict_types=1);
namespace Augustash\BetterSearch\Api;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * Service interface responsible for exposing configuration options.
 * @api
 */
interface ConfigInterface
{
    /**
     * Configuration constants.
     */
    const XML_PATH_SCORE_DEBUG_ENABLED = 'catalog/search/debug_scores';
    const XML_PATH_SCORE_MINIMUM = 'catalog/search/minimum_score';

    /**
     * Retrieves a logger object.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface;

    /**
     * Retrieves the module's debug enabled status.
     *
     * @param string $scope
     * @param int|string|\Magento\Store\Model\Store $scopeCode
     * @return bool
     */
    public function isDebugScores(
        $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $scopeCode = null
    ): bool;

    /**
     * Retrieves the module's minimum score.
     *
     * @param string $scope
     * @param int|string|\Magento\Store\Model\Store $scopeCode
     * @return null|float
     */
    public function getMinimumScore(
        $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $scopeCode = null
    ): ?float;
}
