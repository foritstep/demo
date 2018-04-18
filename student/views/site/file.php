<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['action' => ['upload', 'id' => $model->id], 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field(new \app\models\UploadForm(), 'file')->fileInput()->label(false) ?>
    <a onclick='this.parentNode.submit(); return false;'><i class="glyphicon glyphicon-upload"></i></a>
<?php ActiveForm::end(); ?>
<?php if($model->h_file) { 
    $form = ActiveForm::begin(['action' => ['delete', 'id' => $model->id], 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <a href="/uploads/homeworks/<?= $student_id . '-' . $model->h_id . '.' . pathinfo($model->h_file, PATHINFO_EXTENSION) ?>" download="<?= $model->h_file ?>"><i class="glyphicon glyphicon-download"></i></a>
    <a onclick='this.parentNode.submit(); return false;'><i class="glyphicon glyphicon-remove"></i></a>
<?php
ActiveForm::end();
} ?>