<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Домашние задания';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lessons-homeworks">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $homeworks,
        'columns' => [
            [
                'label' => 'Студент',
                'attribute' => 'student_id',
                'value' => function ($model) {
                    return $model->getStudent()->one()->name;
                },
            ],
            [
                'attribute' => 'file',
                'label' => 'Файл',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a href="/uploads/homeworks/' . $model->lesson_id . '-' . $model->student_id . '.' . pathinfo($model->file, PATHINFO_EXTENSION) . '" download="' . $model->file  . '"><i class="glyphicon glyphicon-download"></i></a>';
                },
            ],
            [
                'attribute' => 'mark',
                'label' => 'Оценка',
                'format' => 'raw',
                'value' => function ($model) {
                    return $this->render('mark', ['model' => $model]);
                }
            ],
            [
                'attribute' => 'date',
                'label' => 'Дата',
            ]
        ],
    ]); ?>
</div>
