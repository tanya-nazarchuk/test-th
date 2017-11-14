<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1 class="pull-left"><?= Html::encode($this->title) ?></h1>

    <p class="pull-right">
        <?php if (Yii::$app->user->isGuest): ?>
            <?= Html::a('Login', ['user/login'], ['class' => 'btn btn-success btn-option']) ?>
        <?php else: ?>
            <?= Html::a('Transfer Money', ['transaction/create'], ['class' => 'btn btn-success btn-option']) ?>
        <?php endif; ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => [
            'id',
            'username',
            'balance',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'login' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-log-in"></span>', $url, [
                            'title' => 'Login under user'
                        ]);
                    },
                    'transaction' => function ($url, $model) {
                        $icon = '<span class="glyphicon glyphicon-send"></span>';
                        if (Yii::$app->user->id == $model->id) {
                            return $icon;
                        }
                        return Html::a($icon, ['transaction/create', 'id' => $model->id], [
                            'title' => 'Transfer money'
                        ]);
                    },
                ],
                'template' => !Yii::$app->user->isGuest ? '{transaction} ' : '{login}'
            ],
        ],
    ]); ?>
</div>