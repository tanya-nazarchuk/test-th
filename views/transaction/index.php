<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Transaction;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transaction-index">

    <h1 class="pull-left"><?= Html::encode($this->title) ?></h1>

    <p class="pull-right">
        <?= Html::a('Transfer Money', ['transaction/create'], ['class' => 'btn btn-success btn-option']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => [
            'id',
            [
                'attribute' => 'receiver_id',
                'label' => 'Receiver',
                'filter' => $users,
                'value' => 'receiver.username',
            ],
            [
                'attribute' => 'sender_id',
                'label' => 'Sender',
                'filter' => $users,
                'value' => 'sender.username',
            ],
            [
                'attribute' => 'amount',
                'value' => function ($data) {
                    return ($data->type == Transaction::TYPE_RECEIVING ? '+' : '-') . $data->amount;
                },
            ],
            [
                'label' => 'Type',
                'format' => 'html',
                'value' => function ($data) {
                    $icon = 'arrow-down';
                    $color = 'danger';
                    if ($data->type == Transaction::TYPE_RECEIVING) {
                        $icon = 'arrow-up';
                        $color = 'success';
                    }
                    return '<span class="glyphicon glyphicon-' . $icon . ' text-' . $color . '"></span>';
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
            ],
        ],
    ]); ?>
</div>
