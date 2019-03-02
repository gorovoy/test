<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Patient */

$this->title = 'Update Patient: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="patient-update">

    <h1><?= Html::encode($this->title) ?></h1>


        <div>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'first_name')->textInput(['value' => $model->first_name]) ?>

            <?= $form->field($model, 'last_name')->textInput(['value' => $model->last_name]) ?>

            <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false); ?>

            <div class="form-group">
                <?= Html::submitButton('Pay', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

</div>
