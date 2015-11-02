<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WeeklyhoursTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WeeklyhoursTable Test Case
 */
class WeeklyhoursTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.weeklyhours',
        'app.weeklyreports',
        'app.projects',
        'app.members',
        'app.users',
        'app.workinghours',
        'app.metrics',
        'app.metrictypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Weeklyhours') ? [] : ['className' => 'App\Model\Table\WeeklyhoursTable'];
        $this->Weeklyhours = TableRegistry::get('Weeklyhours', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Weeklyhours);

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
