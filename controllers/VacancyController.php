<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Vacancy;

class VacancyController extends Controller
{
    public function actionIndex(){
        $query = Vacancy::find();
        $vacancies = $query->all();
        return $this->render('index', ['vacancies' => $vacancies]);
    }
}