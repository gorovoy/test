<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Doctor;
?>
<h1>payment/index</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'patient',
            'format'=> 'raw',
            'value' => function($provider){
                return $provider->patient->getPatientName();
            }
        ],
        [
            'attribute' => 'status',
            'format'=> 'raw',
            'value' => function($provider){
                if ($provider->status == \app\models\Payment::CAPITATION_CONFIRMED)
                {
                    return '<span class="label label-success">CAPITATION_CONFIRMED</span>';
                } else if ($provider->status == \app\models\Payment::CAPITATION_REJECTED) {
                    return '<span class="label label-danger">CAPITATION_REJECTED</span>';
                } else {
                    return '<span class="label label-default">CAPITATION_NO_CONFIRMED</span>';
                }
            }
        ],
        'date',

        ['class' => 'yii\grid\ActionColumn', 'template' => '{view}{update}'],
    ],
]); ?>