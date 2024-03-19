<?php
namespace app\models;

use yii\db\ActiveRecord;

class Organization extends ActiveRecord
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public $password2;
    public function scenarios(){
        return [
            self::SCENARIO_LOGIN => ['inn', 'password'],
            self::SCENARIO_REGISTER => ['inn', 'name', 'password', 'password2'],
        ];
    }

    public function rules()
    {
        return [
            ['inn', 'validateInn'],
            ['inn', 'string', 'length' => 10],
            [['inn', 'name', 'password'], 'required'],
            ['password', 'compare', 'compareAttribute' => 'password2', 'on' => 'register', 'message' => 'Passwords should match']
        ];
    }

    public function validateInn($attribute, $param, $validator){
        if(!is_numeric($this->$attribute)){
            $this->addError($attribute, 'The INN should be numeric');
        }
    }

    public function getVacancies(){
        $this->hasMany(Vacancy::class, ['organization_id' => 'id']);
    }
}