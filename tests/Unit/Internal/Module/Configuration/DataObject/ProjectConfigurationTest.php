<?php
declare(strict_types = 1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Module\Configuration\DataObject;

use DomainException;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ProjectConfiguration;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\EnvironmentConfiguration;
use PHPUnit\Framework\TestCase;

class ProjectConfigurationTest extends TestCase
{
    /**
     * @var ProjectConfiguration
     */
    private $projectConfiguration;

    protected function setUp()
    {
        parent::setUp();
        $this->projectConfiguration = new ProjectConfiguration();
    }

    public function testGetNamesOfEnvironmentConfigurations()
    {
        $environmentConfiguration = new EnvironmentConfiguration();
        $this->projectConfiguration->addEnvironmentConfiguration('Testing', $environmentConfiguration);
        $this->projectConfiguration->addEnvironmentConfiguration('Production', $environmentConfiguration);

        $this->assertEquals(
            ['Testing', 'Production'],
            $this->projectConfiguration->getNamesOfEnvironmentConfigurations()
        );
    }

    public function testDeleteEnvironment()
    {
        $environmentConfiguration = new EnvironmentConfiguration();
        $this->projectConfiguration->addEnvironmentConfiguration('Testing', $environmentConfiguration);
        $this->projectConfiguration->addEnvironmentConfiguration('Production', $environmentConfiguration);
        $this->projectConfiguration->deleteEnvironmentConfiguration('Testing');

        $this->assertEquals(['Production'], $this->projectConfiguration->getNamesOfEnvironmentConfigurations());
    }

    public function testDeleteEnvironmentThrowsExceptionIfEnvironmentDoesNotExist()
    {
        $this->expectException(DomainException::class);
        $this->projectConfiguration->deleteEnvironmentConfiguration('Testing');
    }

    public function testGetEnvironmentConfiguration()
    {
        $environmentConfiguration = new EnvironmentConfiguration();
        $this->projectConfiguration->addEnvironmentConfiguration('Testing', $environmentConfiguration);

        $this->assertSame(
            $environmentConfiguration,
            $this->projectConfiguration->getEnvironmentConfiguration('Testing')
        );
    }

    public function testGetEnvironmentConfigurations()
    {
        $environmentConfiguration = new EnvironmentConfiguration();
        $this->projectConfiguration->addEnvironmentConfiguration('Testing', $environmentConfiguration);
        $this->projectConfiguration->addEnvironmentConfiguration('Once more', $environmentConfiguration);

        $this->assertSame(
            [
                'Testing'   => $environmentConfiguration,
                'Once more' => $environmentConfiguration,
            ],
            $this->projectConfiguration->getEnvironmentConfigurations()
        );
    }

    public function testGetEnvironmentConfigurationThrowsExceptionIfEnvironmentDoesNotExist()
    {
        $this->expectException(DomainException::class);
        $this->projectConfiguration->getEnvironmentConfiguration('Testing');
    }
}
