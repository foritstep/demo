<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Курсы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Courses', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'group_id',
                'value' => function ($model) {
                    return $model->getGroup()->one()->name;
                },
            ],
            'name',
            [
                'attribute' => 'teacher_id',
                'value' => function ($model) {
                    return $model->getTeacher()->one()->name;
                },
            ],
            'quantity',
            'begin',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
