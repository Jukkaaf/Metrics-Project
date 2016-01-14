<?php
namespace App\Model\Table;

use App\Model\Entity\Worktype;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class WorktypesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('worktypes');
        $this->displayField('description');
        $this->primaryKey('id');

        $this->hasMany('Workinghours', [
            'foreignKey' => 'worktype_id'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        return $validator;
    }
}
