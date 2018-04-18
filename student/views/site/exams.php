<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результаты экзаменов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exams-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'course_id',
                'label' => 'Курс',
                'format'    => 'raw',
                'value'     => function ($model) {
                    return $model->getCourse()->one()->name;
                },
            ],
            'mark',
        ],
    ]); ?>
</div>
