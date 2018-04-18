<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Exams */

$this->title = 'Поставить оценку';
$this->params['breadcrumbs'][] = ['label' => 'Exams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exams-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
