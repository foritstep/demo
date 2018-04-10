<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <h2>Редактировать</h2>
    <a href="<?= Url::toRoute('classroom/index') ?>" class="btn btn-primary active" role="button">Аудитории</a>
    <a href="<?= Url::toRoute('course/index') ?>" class="btn btn-primary active" role="button">Курсы</a>
    <a href="<?= Url::toRoute('group/index') ?>" class="btn btn-primary active" role="button">Классы</a>
    <a href="<?= Url::toRoute('student/index') ?>" class="btn btn-primary active" role="button">Студенты</a>
    <a href="<?= Url::toRoute('teacher/index') ?>" class="btn btn-primary active" role="button">Преподаватели</a>
</div>
