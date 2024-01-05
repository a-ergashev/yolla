<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Users extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID
     */
    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['$token' => $token]);
    }
    /**
     * @return int|string current user ID 
     */
    public function getId(){
        return $this->id;
    }
    /**
     * @return string|null current user auth key
     */
    public function getAuthKey(){
        return $this->auth_key;
    }
    /**
     * @param string $authKey
     * @return bool|null
     */
    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    public function rules(){
        return [
            [['email', 'fullname', 'password'], 'required'],
            ['email', 'email']
        ];
    }
    
    /**
     * Finds user by email
     * 
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        $query = static::find();
        $user = $query->where(['email' => $email])
            ->one();

        return $user === false ? null : new static($user);
    }

    /**
     * Validates the password
     * @return bool
     */
    public function validatePassword($password){
        if($password == null || $this->password == null) return false;
        return Yii::$app->getSecurity()
            ->validatePassword($password, $this->password);
    }
    /**
     * Gets the skills user has
     */
    public function getSkills(){
        return $this->hasMany(Skill::class, ['id' => 'skill_id'])
            ->viaTable('user_skill', ['user_id' => 'id']);
    }
    public function getVacancies(){
        $this->hasMany(Vacancy::class, ['id' => 'vacancy_id'])
        ->viaTable('user_vacancy', ['user_id' => 'id']);
    }
}
