<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WorkinghoursTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WorkinghoursTable Test Case
 */
class WorkinghoursTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.workinghours',
        'app.members',
        'app.users',
        'app.projects',
        'app.metrics',
        'app.metrictypes',
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
        $config = TableRegistry::exists('Workinghours') ? [] : ['className' => 'App\Model\Table\WorkinghoursTable'];
        $this->Workinghours = TableRegistry::get('Workinghours', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Workinghours);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
