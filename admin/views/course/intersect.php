<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пересечение занятий';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <table class="table">
        <thead>
            <tr>
                <th>Курс</th>
                <th>День</th>
                <th>Занятие</th>
                <th>Аудитория</th>
            </tr>
        </thead>
        <tbody><?php
            foreach($data as $i) { ?>
                <tr>
                    <th><?= Html::a(
                        $i['course'],
                        ['schedule', 'id' => $i['course_id']]
                    ); ?></th>
                    <th><?= $i["date"] ?></th>
                    <th><?= $i['number'] + 7 ?>:00 - <?= $i['number'] + 7 ?>:45</th>
                    <th><?= Html::a(
                        $i['classroom'],
                        ['classroom/view', 'id' => $i['classroom']]
                    ); ?></th>
            </tr>
            <?php }
        ?></tbody>
    </table>
</div>
