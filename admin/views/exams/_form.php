<?php

use app\models\Students;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use brussens\bootstrap\select\Widget as Select;

/* @var $this yii\web\View */
/* @var $model app\models\Exams */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exams-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_id')->widget(Select::className(), [
            'options' => ['data-live-search' => 'true', 'disabled' => true],
            'items' => [
                $model->course_id => $model->getCourse()->one()->name,
            ],
        ]);
    ?>

    <?= $form->field($model, 'student_id')->widget(Select::className(), [
            'options' => ['data-live-search' => 'true'],
            'items' => (function() use($model) {
                $acc = [];
                foreach(Students::find()->where(['group_id' => $model->getCourse()->one()->group_id])->all() as $i) { $acc[$i->id] = $i->name; }
                return $acc;
            })(),
        ]);
    ?>

    <?= $form->field($model, 'mark')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
