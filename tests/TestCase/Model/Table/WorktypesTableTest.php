<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WorktypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WorktypesTable Test Case
 */
class WorktypesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.worktypes',
        'app.workinghours',
        'app.members',
        'app.users',
        'app.projects',
        'app.metrics',
        'app.metrictypes',
        'app.weeklyreports',
        'app.weeklyhours'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Worktypes') ? [] : ['className' => 'App\Model\Table\WorktypesTable'];
        $this->Worktypes = TableRegistry::get('Worktypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Worktypes);

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
