<?php

/**
 * Search Improvements Module
 *
 * @author    Peter McWilliams <pmcwilliams@augustash.com>
 * @copyright Copyright (c) 2021 August Ash (https://www.augustash.com)
 */

declare(strict_types=1);
namespace Augustash\BetterSearch\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Response\Http as Response;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Redirect to a product page when searching a SKU.
 */
class RedirectToCatalogProduct implements ObserverInterface
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    private $response;

    /**
     * Constructor.
     *
     * Initialize class dependencies.
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\App\Response\Http $response
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Response $response
    ) {
        $this->productRepository = $productRepository;
        $this->response = $response;
    }

    /**
     * Execute observer method.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $observer->getEvent()->getRequest();
        $queryString = $request->getQuery('q');

        try {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->productRepository->get($queryString);
        } catch (NoSuchEntityException $e) {
            $product = false;
        }

        if ($product !== false && $product->getProductUrl()) {
            $this->response->setRedirect($product->getProductUrl());
        }
    }
}
