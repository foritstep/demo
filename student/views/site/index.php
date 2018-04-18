<?php

use yii\grid\GridView;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
        <h2>Задания</h2>

        <p>
            <?= yii\helpers\Html::a('Результаты экзаменов', ['exam'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'theme',
                'homework',
                'file',
                'date',
                [
                    'attribute' => 'mark',
                    'label' => 'Оценка'
                ],
                [
                    'attribute' => 'h_file',
                    'label' => 'Файл',
                    'format'    => 'raw',
                    'value'     => function ($model) use($student_id) {
                        return $this->render('file', ['model' => $model, 'student_id' => $student_id]);
                    },
                ],
                [
                    'attribute' => 'h_date',
                    'label' => 'Дата загрузки',
                ]
            ],
        ]); ?>
    </div>
</div>
