<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['action' => ['mark', 'student' => $model->student_id, 'lesson' => $model->lesson_id]]); ?>
    <?= $form->field($model, 'mark')->textInput(['maxlength' => true])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>