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

    <p>
        <?= Html::a('Поставить оценку', ['create', 'id' => $course_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'course_id',
            'student_id',
            'mark',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
