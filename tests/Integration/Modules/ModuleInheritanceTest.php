<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 * @version   OXID eShop CE
 */
namespace OxidEsales\EshopCommunity\Tests\Integration\Modules;

use OxidEsales\EshopCommunity\Core\FileCache;
use OxidEsales\EshopCommunityTestModule\Vendor1\ModuleInheritance16\MyClass;
use OxidEsales\EshopCommunityTestModule\Vendor1\namespaced_from_ns\MyClass as namespaced_from_ns;
use OxidEsales\EshopCommunityTestModule\Vendor1\namespaced_from_virtual\MyClass as namespaced_from_virtual;
use OxidEsales\EshopCommunity\Tests\Integration\Modules\TestDataInheritance\modules\Vendor2\ModuleInheritance24\MyClass as ModuleInheritance24MyClass;
use OxidEsales\EshopCommunity\Tests\Integration\Modules\TestDataInheritance\modules\Vendor2\ModuleInheritance27\MyClass as ModuleInheritance27MyClass;

/**
 * Test, that the inheritance of modules and the shop works as expected.
 *
 * See also OxidEsales\EshopCommunity\Tests\Integration\Modules\BCModuleInheritanceTest
 *
 * Below, there are listed all possible combinations which are possible. You have to read the tables as follows:
 * E.g. Test Case 1.1 is: A "plain module class" "extends via PHP" a "Plain shop class"
 *
 *
 * 1. Simple extending shop classes in modules
 * +-------------------------------+--------------------+-------------------------+---------------------------------+
 * |        extends via PHP        | plain module class | namespaced module class | virtual namespaced module class |
 * +-------------------------------+--------------------+-------------------------+---------------------------------+
 * | Plain shop class              |                1.1 |                     1.6 | not planned                     |
 * | Namespaced shop class         |                1.2 |                     1.7 | not planned                     |
 * | Virtual namespaced shop class |                1.5 |                    1.10 | not planned                     |
 * +-------------------------------+--------------------+-------------------------+---------------------------------+
 *
 *
 *
 * 2. Simple extending module classes from other modules
 * +--------------------------------------------------------------+--------------------+-------------------------+
 * |                       extends via PHP                        | plain module class | namespaced module class |
 * +--------------------------------------------------------------+--------------------+-------------------------+
 * | plain module class which extends an other class              |                2.1 |                     2.3 |
 * | namespaced module class which extends an other class         |                2.2 |                     2.4 |
 * | plain module class which chain extends a shop class          |                2.5 |                     2.7 |
 * | namespaced module class which does not extend an other class |                2.6 |                     2.8 |
 * +--------------------------------------------------------------+--------------------+-------------------------+
 *
 * Together with "2. Simple extending module classes from other modules" we implemented some other test cases.
 * These test cases should be already covered by the test cases in table 1 and 3.
 * If you remove these unnecessary test cases, there should be only 4 test cases left:
 * +--------------------------+--------------------+-------------------------+
 * |     extends via PHP      | plain module class | namespaced module class |
 * +--------------------------+--------------------+-------------------------+
 * | plain module class       |                    |                         |
 * | namespaced module class  |                    |                         |
 * +--------------------------+--------------------+-------------------------+
 *
 *
 *
 *  3. Chain extending shop classes in modules
 * +-------------------------------+--------------------+-------------------------+
 * |       extends via chain       | plain module class | namespaced module class |
 * +-------------------------------+--------------------+-------------------------+
 * | Plain shop class              | 3.1                | no need                 |
 * | Namespaced shop class         | no need            | 3.5                     |
 * | Virtual namespaced shop class | 3.3                | 3.6                     |
 * +-------------------------------+--------------------+-------------------------+
 *
 *
 *
 * 4. Chain extending module classes from other modules
 * +-------------------------+--------------------+-------------------------+
 * |    extends via chain    | plain module class | namespaced module class |
 * +-------------------------+--------------------+-------------------------+
 * | plain module class      |                4.1 |                     4.3 |
 * | namespaced module class |                4.2 |                     4.4 |
 * +-------------------------+--------------------+-------------------------+
 *
 * @group module
 */
class ModuleInheritanceTest extends BaseModuleInheritanceTestCase
{
    /**
     * This test covers the PHP inheritance between one module class and one shop class.
     *
     * The module class extends the PHP class directly like '<module class> extends <shop class>'.
     * In this case the parent class of the module class must be the shop class as instantiated with oxNew.
     *
     * @dataProvider dataProviderTestModuleInheritanceTestPhpInheritance
     *
     * @param array  $moduleToActivate The module we want to activate.
     * @param string $moduleClassName  The module class we want to instantiate.
     * @param array  $shopClassNames   The shop class from which the module class should inherit.
     */
    public function testModuleInheritanceTestPhpInheritance($moduleToActivate, $moduleClassName, $shopClassNames)
    {
         parent::testModuleInheritanceTestPhpInheritance($moduleToActivate, $moduleClassName, $shopClassNames);
    }

    /**
     * This test covers PHP inheritance between module classes.
     *
     * The tested module class extends the other module class directly like '<module anotherclass> extends <module class>'
     * or '<moduleA class> extends <moduleB class>'
     * In this case the parent class of the module class must be the parent module class as instantiated with oxNew
     *
     * @dataProvider dataProviderTestMultiModuleInheritanceTestPhpInheritance
     *
     * @param array  $modulesToActivate The modules we want to activate.
     * @param string $moduleClassName   The module class we want to instantiate.
     * @param array  $shopClassNames    The shop class from which the module class should inherit.
     */
    public function testMultiModuleInheritanceTestPhpInheritance($modulesToActivate, $moduleClassName, $shopClassNames)
    {
        parent::testModuleInheritanceTestPhpInheritance($modulesToActivate, $moduleClassName, $shopClassNames);
    }

    /**
     * DataProvider for the testModuleInheritanceTestPhpInheritance method.
     *
     * @return array The different test cases we execute.
     */
    public function dataProviderTestModuleInheritanceTestPhpInheritance()
    {
        return [
            'case_1_6'  => [
                // Test case 1.6 namespaced module extends plain shop class
               'moduleToActivate' => ['Vendor1/ModuleInheritance16'],
               'moduleClassName'  => \OxidEsales\EshopCommunityTestModule\Vendor1\ModuleInheritance16\MyClass::class,
               'shopClassNames'    => [\OxidEsales\EshopCommunity\Application\Model\Article::class, 'oxArticle']
            ],
            'case_1_7'  => [
                // Test case 1.7 namespaced module extends namespaced eShop Community class
                'moduleToActivate' => ['Vendor1/namespaced_from_ns'],
                'moduleClassName'  => namespaced_from_ns::class,
                'shopClassNames'   => [\OxidEsales\EshopCommunity\Application\Model\Article::class]
            ],
            'case_1_10' => [
                // Test case 1.10 namespaced module extends eShop virtual class
                'moduleToActivate' => ['Vendor1/namespaced_from_virtual'],
                'moduleClassName'  => namespaced_from_virtual::class,
                'shopClassNames'   => [\OxidEsales\Eshop\Application\Model\Article::class]
            ],
            'case_3_5' => [
                // Test case 3.5 namespaced module class chain extends namespaced OXID eShop Community class
                'moduleToActivate' => ['Vendor1/ModuleChainExtension35'],
                'moduleClassName'  => \OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension35\MyClass35::class,
                'shopClassNames'   => [\OxidEsales\EshopCommunity\Application\Model\Article::class],
            ],
            'case_3_6' => [
                // Test case 3.6 namespaced module class chain extends virtual OXID eShop class
                'moduleToActivate' => ['Vendor1/ModuleChainExtension36'],
                'moduleClassName'  => \OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension36\MyClass36::class,
                'shopClassNames'   => [\OxidEsales\Eshop\Application\Model\Article::class],
            ],
        ];
    }

    /**
     * DataProvider for the testMultiModuleInheritanceTestPhpInheritance method.
     *
     * @return array The different test cases we execute.
     */
    public function dataProviderTestMultiModuleInheritanceTestPhpInheritance()
    {
        return [
            'case_2_2' => [
                // Test case 2.2 plain module class extends an other modules extended namespaced module class
                'modulesToActivate' => ['Vendor1/namespaced_from_ns', 'module_inheritance_2_2_a'],
                'moduleClassName' => 'vendor_2_module_2_myclass',
                'shopClassNames' => [\OxidEsales\EshopCommunity\Application\Model\Article::class]
            ],
            'case_2_3' => [
                // Test case 2.3 namespaced module class extends an other modules extended plain module class
                'modulesToActivate' => ['module_inheritance_2_3_a', 'Vendor2/ModuleInheritance23b'],
                'moduleClassName' => \OxidEsales\EshopCommunityTestModule\Vendor2\ModuleInheritance23b\MyClass::class,
                'shopClassName' => [
                    \OxidEsales\EshopCommunity\Application\Model\Article::class,
                    'vendor_1_module_1_myclass2',
                    'vendor_1_module_1_anotherclass'
                ]
            ],
            'case_2_4' => [
                // Test case 2.4 namespaced module class extends an other modules extended namespaced module class
                'modulesToActivate' => ['Vendor1/namespaced_from_ns', 'Vendor2/ModuleInheritance24'],
                'moduleClassName' => ModuleInheritance24MyClass::class,
                'shopClassNames' => [namespaced_from_ns::class, \OxidEsales\EshopCommunity\Application\Model\Article::class]
            ],
            'case_2_6' => [
                // Test case 2.6 plain module_2 extends namespaced module_1
                'modulesToActivate' => ['Vendor1/namespaced_from_ns', 'module_inheritance_2_6'],
                'moduleClassName'   => 'vendor_2_module_6_myclass',
                'shopClassNames'    => [namespaced_from_ns::class]
            ],
            'case_2_7' => [
                // Test case 2.7 namespaced module_2 extends plain module_1
                'modulesToActivate' => ['Vendor2/ModuleInheritance27', 'bc_module_inheritance_1_1'],
                'moduleClassName'   => ModuleInheritance27MyClass::class,
                'shopClassNames'    => ['vendor_1_module_1_onemoreclass']
            ],
            'case_2_8' => [
                // Test case 2.8 namespaced module_2 extends namespaced module_1
                'modulesToActivate' => ['Vendor1/ModuleInheritance28a', 'Vendor2/ModuleInheritance28b'],
                'moduleClassName'   => \OxidEsales\EshopCommunityTestModule\Vendor2\ModuleInheritance28b\MyClass::class,
                'shopClassNames'    => [\OxidEsales\EshopCommunityTestModule\Vendor1\ModuleInheritance28a\MyClass::class]
            ],
            'case_4_1' => [
                // Test case 4.1 plain module_2 chain extends plain module_1
                'modulesToActivate' => ['module_chain_extension_4_1_a', 'module_chain_extension_4_1_b'],
                'moduleClassName'   => 'vendor_1_module_4_1_b_myclass',
                'shopClassNames'    => ['vendor_1_module_4_1_a_myclass']
            ],
            'case_4_2' => [
                // Test case 4.2 plain module_2 chain extends namespaced module_1
                'modulesToActivate' => ['Vendor1/ModuleChainExtension42', 'module_chain_extension_4_2'],
                'moduleClassName'   => 'module_chain_extension_4_2_myclass',
                'shopClassNames'    => [\OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension42\MyClass42::class]
            ],
            'case_4_3' => [
                // Test case 4.3 namespaced module class chain extends plain module class
                'moduleToActivate' => ['bc_module_inheritance_4_3', 'Vendor2/ModuleChainExtension43'],
                'moduleClassName'  => \OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension43\MyClass43::class,
                'shopClassNames'   => ['vendor_1_module_4_3_myclass']
            ],
            'case_4_4' => [
                // Test case 4.4 namespaced module class chain extends other namespaced module class
                'moduleToActivate' => ['Vendor1/ModuleChainExtension44', 'Vendor2/ModuleChainExtension44'],
                'moduleClassName'  => \OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension44\MyClass44::class,
                'shopClassNames'   => [\OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension44\MyClass44::class],
            ],
        ];
    }

    /**
     * Test, that the chain is build correct, after we ordered the module extensions over the admin controller.
     *
     * @param array $storedModuleChain The module chain we want to store over the admin controller.
     *
     * @dataProvider dataProviderTestChainAfterAdminControllerSave
     */
    public function testChainAfterAdminControllerSave($storedModuleChain)
    {
        $this->markTestSkippedUntil('2017-03-31', 'The most test cases did not work, cause the chain building seems buggy for mixed bc and namespaced classes (the story ESDEV-4251 is blocking this subtask)');

        $activatedModules = [
            'Vendor1/ModuleChainExtension37a',
            'Vendor2/ModuleChainExtension37b',
            'Vendor3/ModuleChainExtension37c',
        ];
        $this->environment->prepare($activatedModules);

        $this->callAdminSaveModuleOrder($storedModuleChain);

        // We clear the file cache here, cause officially we say, that you should empty the temporary directory after reordering.
        FileCache::clearCache();

        // check, if the inheritance chain is built as expected
        $moduleObject = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);

        $actualChain = $this->buildInheritanceChainHead($moduleObject);
        $expectedChain = [$storedModuleChain[2], $storedModuleChain[1], $storedModuleChain[0], 'OxidEsales\EshopCommunity\Application\Model\Article'];

        $this->assertEquals($expectedChain, $actualChain, 'The inheritance chain is not formed as expected!');
    }

    /**
     * Get the head of the inheritance chain of the given object. With head here are ment the first four elements.
     *
     * @param object $moduleObject The object for which we want to build the inheritance chain head (first four elements).
     *
     * @return array The first four elements of the inheritance chain.
     */
    protected function buildInheritanceChainHead($moduleObject)
    {
        $moduleObjectClass = get_class($moduleObject);
        $classParents = array_keys(class_parents($moduleObject));

        return [$moduleObjectClass, $classParents[0], $classParents[1], $classParents[2]];
    }

    /**
     * Call the admin controller with a given module extension order.
     *
     * @param array $storedModuleChain The ordered module extensions we want to send to the controller.
     */
    protected function callAdminSaveModuleOrder($storedModuleChain)
    {
        $this->setAdminMode(true);
        $this->setModuleChainAsRequestParameter($storedModuleChain);

        $oView = oxNew(\OxidEsales\EshopCommunity\Application\Controller\Admin\ModuleSortList::class);
        $oView->save();
    }

    /**
     * Set the module order as a request parameter.
     *
     * @param array $storedModuleChain The ordered module extensions we want to send to the controller.
     */
    protected function setModuleChainAsRequestParameter($storedModuleChain)
    {
        $modulesSendToController = ["OxidEsales---Eshop---Application---Model---Article" => $storedModuleChain];
        $json = json_encode($modulesSendToController);

        $this->setRequestParameter("aModules", $json);
    }

    /**
     * Data provider for the method testChainAfterAdminControllerSave.
     *
     * @return array The test cases for the method testChainAfterAdminControllerSave.
     */
    public function dataProviderTestChainAfterAdminControllerSave()
    {
        return [
            'case_1' => [
                'storedModuleChain' => [
                    'OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension37a\MyClass37a',
                    'OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension37b\MyClass37b',
                    'OxidEsales\EshopCommunityTestModule\Vendor3\ModuleChainExtension37c\MyClass37c',
                ]
            ],
            'case_2' => [
                'storedModuleChain' => [
                    'OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension37a\MyClass37a',
                    'OxidEsales\EshopCommunityTestModule\Vendor3\ModuleChainExtension37c\MyClass37c',
                    'OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension37b\MyClass37b',
                ]
            ],
            'case_3' => [
                'storedModuleChain' => [
                    'OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension37b\MyClass37b',
                    'OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension37a\MyClass37a',
                    'OxidEsales\EshopCommunityTestModule\Vendor3\ModuleChainExtension37c\MyClass37c',
                ]
            ],
            'case_4' => [
                'storedModuleChain' => [
                    'OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension37b\MyClass37b',
                    'OxidEsales\EshopCommunityTestModule\Vendor3\ModuleChainExtension37c\MyClass37c',
                    'OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension37a\MyClass37a',
                ]
            ],
            'case_5' => [
                'storedModuleChain' => [
                    'OxidEsales\EshopCommunityTestModule\Vendor3\ModuleChainExtension37c\MyClass37c',
                    'OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension37a\MyClass37a',
                    'OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension37b\MyClass37b',
                ]
            ],
            'case_6' => [
                'storedModuleChain' => [
                    'OxidEsales\EshopCommunityTestModule\Vendor3\ModuleChainExtension37c\MyClass37c',
                    'OxidEsales\EshopCommunityTestModule\Vendor2\ModuleChainExtension37b\MyClass37b',
                    'OxidEsales\EshopCommunityTestModule\Vendor1\ModuleChainExtension37a\MyClass37a',
                ]
            ],
        ];
    }
}