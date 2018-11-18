<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Costs Model
 *
 * @method \App\Model\Entity\Cost get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cost newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cost[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cost|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cost[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cost findOrCreate($search, callable $callback = null, $options = [])
 */
class ScheduleTable extends Table
{
/*
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
   /* 
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('seminar');
        $this->setDisplayField('seminarId');
        $this->setPrimaryKey('seminarId');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
     /*
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('seminarId')
            ->allowEmpty('seminarId', 'create');

        $validator
            ->date('ideaId')
            ->requirePresence('costdate', 'create')
            ->notEmpty('costdate');

        $validator
            ->scalar('usedetail')
            ->requirePresence('usedetail', 'create')
            ->notEmpty('usedetail');

        $validator
            ->integer('value')
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        return $validator;
    }*/
}
