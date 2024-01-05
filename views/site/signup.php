<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'signup-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]) ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'fullname') ?>
            <?= $form->field($model, 'password') ?>
            

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-1">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>