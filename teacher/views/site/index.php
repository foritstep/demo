<?php

use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">
        <h2>Текущие курсы</h2>
        
        <div style="overflow:hidden; float:left; padding-right: 3em">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Аудитория</th>
                        <th scope="col">Курс</th>
                        <th scope="col">Время</th>
                        <th scope="col">Группа</th>
                        <th scope="col"></th>                    
                    </tr>
                </thead>
                <tbody id="description-wrapper">
                </tbody>
            </table>
        </div>
        <div style="display: none">
            <?= dosamigos\datepicker\DatePicker::widget([
                'name' => 'Test',
                'value' => '02-16-2012',
                'template' => '{addon}{input}',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
            ]); ?>
        </div>
    </div>
</div>
