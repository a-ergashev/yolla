<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerCssFile("@web/css/profile.css");
$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
$form = ActiveForm::begin([
    'id' => 'skill-form',
    'fieldConfig' => [
        'template' => "{input}\n{error}"    
    ]
]);
?>
<div class="site-profile">

    <h1><?= Html::encode(Yii::$app->user->identity->fullname) ?></h1>
    <p><?= Html::encode(Yii::$app->user->identity->email) ?></p>

    <div class="skills">
        <?php foreach($user_skills as $skill): ?>
            <span><?= $skill->name ?></span>
        <?php endforeach ?>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <?php echo $form->field($model, 'name')->dropDownList(array_combine($skills, $skills), ['prompt' => 'Select...']) ?>
        
            <?= Html::submitButton('ADD', ['class' => 'btn btn-primary']); ?>
            <?php ActiveForm::end(); ?>
            
            <?php $form = ActiveForm::begin(['action' => ['site/logout']]); ?>
            <?= Html::submitButton('LOGOUT', ['class'=> 'logout']); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
