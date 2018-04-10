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

    <?php
        foreach($data as $group) { ?>
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
                    foreach($group as $i) { ?>
                        <tr>
                            <th><?= Html::a(
                                $i->getCourse()->one()->name,
                                ['schedule', 'id' => $i->getCourse()->one()->id]
                            ); ?></th>
                            <th><?= ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"][$i->day] ?></th>
                            <th><?= $i->number + 7 ?>:00 - <?= $i->number + 7 ?>:45</th>
                            <th><?= Html::a(
                                $i->getClassroom()->one()->name,
                                ['classroom/view', 'id' => $i->getClassroom()->one()->id]
                            ); ?></th>
                    </tr>
                    <?php }
                ?></tbody>
            </table>
        <?php }
    ?>
</div>
