<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-form col-lg-7 well">

    <?php $form = ActiveForm::begin(['id' => 'create-form']); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Transfer', ['class' => 'btn btn-success', 'name' => 'create-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


