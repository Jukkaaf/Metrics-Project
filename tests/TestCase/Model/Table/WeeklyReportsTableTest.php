<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WeeklyreportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WeeklyreportsTable Test Case
 */
class WeeklyreportsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.weeklyreports',
        'app.projects',
        'app.members',
        'app.users',
        'app.workinghours',
        'app.metrics',
        'app.metrictypes',
        'app.requirements'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Weeklyreports') ? [] : ['className' => 'App\Model\Table\WeeklyreportsTable'];
        $this->Weeklyreports = TableRegistry::get('Weeklyreports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Weeklyreports);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
