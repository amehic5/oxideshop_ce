<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Core;

/**
 * Class is responsible for openSSL functionality availability checking.
 *
 * @deprecated since v6.4.0 (2019-01-31); This class will be removed completely.
 */
class OpenSSLFunctionalityChecker
{
    /**
     * Checks if openssl_random_pseudo_bytes function is available.
     *
     * @return bool
     */
    public function isOpenSslRandomBytesGeneratorAvailable()
    {
        return function_exists('openssl_random_pseudo_bytes');
    }
}
