<?php

/**
 * Search Improvements Module
 *
 * @author    Peter McWilliams <pmcwilliams@augustash.com>
 * @copyright Copyright (c) 2021 August Ash (https://www.augustash.com)
 */

declare(strict_types=1);
namespace Augustash\BetterSearch\Model;

use Augustash\BetterSearch\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

/**
 * Configuration class.
 */
class Config implements ConfigInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor.
     *
     * Initialize class dependencies.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    public function isDebugScores(
        $scope = ScopeInterface::SCOPE_STORES,
        $scopeCode = null
    ): bool {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_SCORE_DEBUG_ENABLED,
            $scope,
            $scopeCode
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getMinimumScore(
        $scope = ScopeInterface::SCOPE_STORES,
        $scopeCode = null
    ): ?float {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_SCORE_MINIMUM,
            $scope,
            $scopeCode
        );

        return ($value !== null) ? (float)$value : 1.0;
    }
}
