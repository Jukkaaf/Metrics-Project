<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WeeklyhoursFixture
 *
 */
class WeeklyhoursFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'weeklyreport_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'member_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'duration' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        '_indexes' => [
            'weeklyreport_key' => ['type' => 'index', 'columns' => ['weeklyreport_id'], 'length' => []],
            'member_key' => ['type' => 'index', 'columns' => ['member_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'weeklyhours_ibfk_1' => ['type' => 'foreign', 'columns' => ['weeklyreport_id'], 'references' => ['weeklyreports', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'weeklyhours_ibfk_2' => ['type' => 'foreign', 'columns' => ['member_id'], 'references' => ['members', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'weeklyreport_id' => 1,
            'member_id' => 1,
            'duration' => 1
        ],
    ];
}
