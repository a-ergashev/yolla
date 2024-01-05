<?php
namespace app\models;

use yii\db\ActiveRecord;

class Skill extends ActiveRecord{
    public function rules(){
        return [
            ['name', 'required']
        ];
    }
    public function getVacancies(){
        return $this->hasMany(Vacancy::class, ['id' => 'vacancy_id'])
        ->viaTable('vacancy_skill', ['skill_id', 'id']);
    }
    
    public function getUsers(){
        return $this->hasMany(User::class, ['id' => 'user_id'])
        ->viaTable('user_skill', ['skill_id' => 'id']);
    }
}