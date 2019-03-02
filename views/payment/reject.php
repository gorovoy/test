<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Doctor */

$this->title = 'Reject payment';
$this->params['breadcrumbs'][] = ['label' => 'Payment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-create">

    <h1><?= Html::encode($this->title) ?> for <?= $patient->getPatientName(); ?> </h1>

</div>

<?php
if (\Yii::$app->session->hasFlash('RejectPay')) {
    echo "<div class=\"alert alert-danger\" role=\"alert\">";
    echo \Yii::$app->session->getFlash('RejectPay');
    echo "</div>";
}
//todo добавить dataPicker вместо текстового поля.
?>

<div class="interview-create">

    <div class="interview-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($paymentForm, 'date')->textInput() ?>

        <?= $form->field($paymentForm, 'denial_reason')->textInput() ?>

        <?= $form->field($paymentForm, 'patient_id')->hiddenInput(['value' => $paymentForm->patient_id])->label(false); ?>

        <div class="form-group">
            <?= Html::submitButton('Reject', ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
