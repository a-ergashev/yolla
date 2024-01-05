<?php
namespace app\models;

use yii\db\ActiveRecord;

class Organization extends ActiveRecord
{
    public function getVacancies(){
        $this->hasMany(Vacancy::class, ['organization_id' => 'id']);
    }
}