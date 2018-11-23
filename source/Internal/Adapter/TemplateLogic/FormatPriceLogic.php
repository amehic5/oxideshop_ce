<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class FormatPriceLogic
 *
 * @package OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic
 * @author  Jędrzej Skoczek
 */
class FormatPriceLogic
{

    /**
     * @param array $params
     *
     * @return string
     */
    public function formatPrice($params)
    {
        $output = '';
        $inputPrice = $params['price'];
        if (!is_null($inputPrice)) {
            $output = $this->calculatePrice($inputPrice, $params);
        }

        return $output;
    }

    /**
     * @param mixed $inputPrice
     * @param array $params
     *
     * @return string
     */
    private function calculatePrice($inputPrice, $params)
    {
        $config = Registry::getConfig();
        $price = ($inputPrice instanceof \OxidEsales\Eshop\Core\Price) ? $inputPrice->getPrice() : floatval($inputPrice);
        $currency = isset($params['currency']) ? $params['currency'] : $config->getActShopCurrencyObject();
        $output = '';

        if (is_numeric($price)) {
            $output = $this->getFormattedPrice($currency, $price);
        }

        return $output;
    }

    /**
     * @param object $currency active currency object
     * @param mixed  $price
     *
     * @return string
     */
    private function getFormattedPrice($currency, $price)
    {
        $output = '';
        $decimalSeparator = isset($currency->dec) ? $currency->dec : ',';
        $thousandsSeparator = isset($currency->thousand) ? $currency->thousand : '.';
        $currencySymbol = isset($currency->sign) ? $currency->sign : '';
        $currencySymbolLocation = isset($currency->side) ? $currency->side : '';
        $decimals = isset($currency->decimal) ? (int) $currency->decimal : 2;

        if ((float) $price > 0 || $currencySymbol) {
            $price = number_format($price, $decimals, $decimalSeparator, $thousandsSeparator);
            $output = (isset($currencySymbolLocation) && $currencySymbolLocation == 'Front') ? $currencySymbol . $price : $price . ' ' . $currencySymbol;
        }

        $output = trim($output);

        return $output;
    }
}
