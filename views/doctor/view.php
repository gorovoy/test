<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;
use app\models\Payment;


/* @var $this yii\web\View */
/* @var $model app\models\Doctor */

$this->title = $model->getDoctorName();
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="doctor-view">

    <h1>Doctor: <?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
            'method' => 'post',
    ]); ?>

    <?php
     echo $form->field($monthSelectorForm, 'date')->widget(
        DatePicker::class, [
        'inline' => true,
        'value' => date('M-Y', strtotime('+2 days')),
        'template' => '<div class="well well-sm" style="background-color: #fff; width:225px; ">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'startView'=>'year',
            'minViewMode'=>'months',
            'format' => 'M-yyyy',
            'todayHighlight' => true
        ],
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Select', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <h2>Patients</h2>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'Patient',
                'format'=> 'raw',
                'value' => function($provider){
                    $url = Url::to(['patient/view', 'id' => $provider['patient_id']]);
                    return Html::a( $provider['first_name'] . " " . $provider['last_name'] , $url, ['title' => 'view']);
                }
            ],
            [
                'attribute' => 'Period',
                'format'=> 'raw',
                'value' => function($provider) use ($monthSelectorForm) {
                    return $monthSelectorForm->date;
                }
            ],
            [
                'attribute' => 'Status',
                'format'=> 'raw',
                'value' => function($provider){

                    if ($provider['status'] == Payment::CAPITATION_CONFIRMED)
                    {
                        return '<span class="label label-success">Paid</span>';
                    } else if ($provider['capitation'] == Payment::CAPITATION_REJECTED) {
                        return '<span class="label label-danger">Reject</span> : ' . $provider['denial_reason'];
                    } else {
                        return '<span class="label label-default">CAPITATION_NO_CONFIRMED</span>';
                    }
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{pay} {reject}',
                'buttons' => [
                    'pay'   => function ($url, $model) {
                        $url = Url::to(['payment/pay', 'patient_id' => $model['patient_id']]);

                        if ($model['status'] == Payment::CAPITATION_CONFIRMED)
                        return Html::a('<button type="button" class="btn btn-success">Оплатить</button>', $url, ['title' => 'view']);
                    },
                    'reject'   => function ($url, $model) {
                        $url = Url::to(['payment/reject', 'patient_id' => $model['patient_id']]);
                        if ($model['status'] == Payment::CAPITATION_CONFIRMED)
                        return Html::a('<button type="button" class="btn btn-danger">Отказ</button>', $url, ['title' => 'view']);
                    },

                ],
            ],
        ],
    ]) ?>

</div>
