<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lessons-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'homework')->textInput(['maxlength' => true]) ?>

    <?php if($model->file) { ?>
        <a href="/uploads/lessons/<?= $model->id . '.' . pathinfo($model->file, PATHINFO_EXTENSION) ?>" download="<?= $model->file; ?>">Скачать задание</a>
    <?php } ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'date')->widget(
        dosamigos\datepicker\DatePicker::className(), [
            'inline' => true, 
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
