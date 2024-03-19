<?php

namespace app\controllers;
use app\models\Organization;
use yii;
use yii\web\Controller;

class OrganizationController extends Controller
{
    public function actionRegistration()
    {
        $model = new Organization();
        $model->scenario = Organization::SCENARIO_REGISTER;
        
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->save();
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
}