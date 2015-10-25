<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MetrictypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MetrictypesTable Test Case
 */
class MetrictypesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.metrictypes',
        'app.metrics',
        'app.projects',
        'app.members',
        'app.users',
        'app.workinghours',
        'app.requirements',
        'app.weeklyreports'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Metrictypes') ? [] : ['className' => 'App\Model\Table\MetrictypesTable'];
        $this->Metrictypes = TableRegistry::get('Metrictypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Metrictypes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
