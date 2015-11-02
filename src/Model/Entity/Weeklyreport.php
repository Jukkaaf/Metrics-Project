<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Weeklyreport Entity.
 *
 * @property int $id
 * @property int $project_id
 * @property \App\Model\Entity\Project $project
 * @property string $title
 * @property int week
 * @property string $reglink
 * @property string $problems
 * @property string $meetings
 * @property string $additional
 * @property \Cake\I18n\Time $created_on
 * @property \Cake\I18n\Time $updated_on
 */
class Weeklyreport extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
