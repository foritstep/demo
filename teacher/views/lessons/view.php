<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */

$this->title = 'Информация об уроке';
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lessons-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'course_id',
                'value' => function ($model) {
                    return $model->getCourse()->one()->name;
                },
            ],
            'theme',
            'homework',
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function ($model) {
                    return $this->render('file', ['model' => $model]);
                },
            ],
            'date',
        ],
    ]) ?>

</div>
