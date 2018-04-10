<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Classrooms */

$this->title = 'Create Classrooms';
$this->params['breadcrumbs'][] = ['label' => 'Classrooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classrooms-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
