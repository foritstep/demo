<?php

use app\models\Groups;
use brussens\bootstrap\select\Widget as Select;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Students */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="students-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group_id')->widget(Select::className(), [
            'options' => ['data-live-search' => 'true'],
            'items' => (function() {
                $acc = [];
                foreach(Groups::find()->all() as $i) { $acc[$i->id] = $i->name; }
                return $acc;
            })(),
        ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
