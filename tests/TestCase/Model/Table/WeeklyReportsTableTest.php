<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WeeklyReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WeeklyReportsTable Test Case
 */
class WeeklyReportsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.weekly_reports',
        'app.projects',
        'app.members',
        'app.users',
        'app.works',
        'app.metrics',
        'app.requirements',
        'app.changes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('WeeklyReports') ? [] : ['className' => 'App\Model\Table\WeeklyReportsTable'];
        $this->WeeklyReports = TableRegistry::get('WeeklyReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WeeklyReports);

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
