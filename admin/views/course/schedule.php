<?php

use app\models\Classrooms;
use brussens\bootstrap\select\Widget as Select;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
function table_column($x, $y, $model, $form) { ?>
    <th>
        <select data-width="100%" class="selectpicker" data-live-search="true" name="arr[<?= $x ?>][<?= $y ?>]" onchange="test_schedule()">
            <option value="0">--------------</option>
            <?php
                foreach(Classrooms::find()->all() as $i) { ?>
                    <option <?= $model->schedule[$x][$y] == $i->id ? 'selected' : '' ?>
                        value="<?= $i->id ?>"><?= $i->name ?></option>
                <?php }
            ?>
        </select>
    </th>
<?php } ?>

<div class="courses-form">
    <h2>Расписание</h2>
    <div id="alert" class="alert-danger alert fade in" style="display: none;">Пересечение занятий</div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <div style="display: none;">
        <?= $form->field($model, 'group_id')->widget(Select::className(), [
                'options' => ['data-live-search' => 'true'],
                'items' => ['0' => 0],
            ]);
        ?>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>№</th>
                <th>Начало</th>
                <th>Конец</th><?php
                    foreach(["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"] as $i) echo "<th>$i</th>";
            ?></tr>
        </thead>
        <tbody><?php
            for($j = 0; $j < 16; $j++) {
                ?><tr><th><?= $j + 1 ?></th>
                <th><?= $j + 7 ?>:00</th>
                <th><?= $j + 7 ?>:45</th><?php
                    for($i = 0; $i < 7; $i++) {
                        echo table_column($j, $i, $model, $form);
                    }
                ?></tr><?php
            }
        ?></tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
