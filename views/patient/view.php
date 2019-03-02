<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Payment;

/* @var $this yii\web\View */
/* @var $model app\models\Patient */

$this->title = $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="patient-view">

    <h1>Patient: <?= Html::encode($model->getPatientName()) ?></h1>

    <p>
        <?= Html::a('Pay', ['payment/pay', 'patient_id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Reject', ['payment/reject', 'patient_id' => $model->id], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'doctor',
                'format'=> 'raw',
                'value' => function($model){
                    $url = Url::to(['doctor/view', 'id' => $model->doctor_id]);
                    return Html::a($model->doctor->getDoctorName(), $url, ['title' => 'view']);
                }
            ],
            [
                'attribute' => 'capitation',
                'format'=> 'raw',
                'value' => function($model){
                    if ($model->capitation == Payment::CAPITATION_CONFIRMED)
                    {
                        return '<span class="label label-success">CONFIRMED</span>';
                    } else if ($model->capitation == Payment::CAPITATION_REJECTED) {
                        return '<span class="label label-danger">REJECTED</span>';
                    } else {
                        return '<span class="label label-default">NO_CONFIRMED</span>';
                    }
                }
            ],
            [
                'attribute' => 'capitation_start',
                'format'=> 'raw',
                'value' => function($provider){
                    $year = date('y', strtotime($provider->capitation_start));
                    $month = date('M', strtotime($provider->capitation_start));

                    if ($provider->capitation != '') {
                        return $month . " " . $year;
                    } else {
                        return '';
                    }

                }
            ],
            [
                'attribute' => 'capitation_end',
                'format'=> 'raw',
                'value' => function($provider){
                    $year = date('y', strtotime($provider->capitation_end));
                    $month = date('M', strtotime($provider->capitation_end));

                    if ($provider->capitation != Payment::CAPITATION_CONFIRMED) {
                        return $month . " " . $year;
                    } else {
                        return '';
                    }

                }
            ],
        ],
    ]) ?>
    <h2>Payments</h2>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'date',
                'format'=> 'raw',
                'value' => function($provider){
                    $year = date('y', strtotime($provider->date));
                    $month = date('M', strtotime($provider->date));
                    return $month . " " . $year;
                }
            ],
            [
                'attribute' => 'Status',
                'format'=> 'raw',
                'value' => function($provider){
                    if ($provider->status == Payment::CAPITATION_CONFIRMED) {
                        return '<span class="label label-success">Pay</span>';
                    } else {
                        return '<span class="label label-danger">Reject</span>';
                    }
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}  ',
                'buttons' => [
                    'view'   => function ($url, $model) {
                        $url = Url::to(['payment/view', 'id' => $model->id]);
                        return Html::a('<button type="button" class="btn btn-success">Посмотреть</button>', $url, ['title' => 'view']);
                    },
                ],
            ],
        ],
    ]) ?>
</div>
