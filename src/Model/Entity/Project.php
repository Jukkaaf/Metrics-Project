<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Project Entity.
 *
 * @property int $id
 * @property string $project_name
 * @property \Cake\I18n\Time $created_on
 * @property \Cake\I18n\Time $updated_on
 * @property \Cake\I18n\Time $finished_date
 * @property string $status
 * @property string $description
 * @property bool $is_public
 * @property int $importance
 * @property \App\Model\Entity\Member[] $members
 * @property \App\Model\Entity\Metric[] $metrics
 * @property \App\Model\Entity\Requirement[] $requirements
 * @property \App\Model\Entity\Weeklyreport[] $weeklyreports
 */
class Project extends Entity
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
