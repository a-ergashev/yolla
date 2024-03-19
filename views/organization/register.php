<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var app\models\Organization $model */

$form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'inn') ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password2')->passwordInput()->label('Confirm password') ?>
        </div>
    </div>
    <?= html::submitButton('Register', ['class'=> 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>