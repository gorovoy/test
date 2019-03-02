<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Doctor */

$this->title = 'Create payment';
$this->params['breadcrumbs'][] = ['label' => 'Payment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-create">

    <h1><?= Html::encode($this->title) ?> for <?= $patient->getPatientName(); ?> </h1>

</div>

<?php
    if (\Yii::$app->session->hasFlash('CreatePay')) {
        echo "<div class=\"alert alert-danger\" role=\"alert\">";
        echo \Yii::$app->session->getFlash('CreatePay');
        echo "</div>";
    }
?>


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($paymentForm, 'date')->widget(
    DatePicker::class, [
    'inline' => true,
    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
    'clientOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd',
    ]
]);?>


<?= $form->field($paymentForm, 'patient_id')->hiddenInput(['value' => $paymentForm->patient_id])->label(false); ?>

<div class="form-group">
    <?= Html::submitButton('Pay', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
