<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уроки ' . $course->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lessons-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if($dataProvider->getTotalCount() < $course->quantity) { ?>
    <p>
        <?= Html::a('Добавить урок', ['create', 'id' => $course->id, 'date' => date('Y-m-d', time())], ['class' => 'btn btn-success']) ?>
    </p>
    <?php } ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'theme',
            'homework',
            'date',
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function ($model) {
                    return $this->render('file', ['model' => $model]);
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
