<?php

use app\models\Groups;
use app\models\Teachers;
use brussens\bootstrap\select\Widget as Select;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courses-form">

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

    <?= $form->field($model, 'teacher_id')->widget(Select::className(), [
            'options' => ['data-live-search' => 'true'],
            'items' => (function() {
                $acc = [];
                foreach(Teachers::find()->all() as $i) { $acc[$i->id] = $i->name; }
                return $acc;
            })(),
        ]);
    ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'begin')->widget(dosamigos\datepicker\DatePicker::className(), [
        'inline' => true,
        'language' => 'ru',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
