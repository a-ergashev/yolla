<?php
namespace app\models;

use app\models\Skill;
use yii\db\ActiveRecord;

class Vacancy extends ActiveRecord{
    public function getSkills(){
        return $this->hasMany(Skill::class, ['id' => 'skill_id'])
        ->viaTable('vacancy_skill', ['vacancy_id' => 'id']);
    }
    public function getUsers(){
        return $this->hasMany(User::class, ['id' => 'user_id'])
        ->viaTable('user_vacancy', ['user_id' => 'id']);
    }
    public function getOrganization(){
        return $this->hasOne(Organization::class, ['id' => 'organization_id']);
    }
}