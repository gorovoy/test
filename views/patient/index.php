<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Doctor;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Patients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="patient-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Patient', ['create'], ['class' => 'btn btn-success']) ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'Doctor',
                'filter' => '',
                'content' => function($data){
                    return Html::a($data->getPatientName(), ['patient/view', 'id' => $data->id]);
                }
            ],
            [
                'attribute' => 'Doctor',
                'filter' => '',
                'content' => function($data){
                    return $data->doctor->getDoctorName();
                }
            ],
            [
                'attribute' => 'capitation',
                'format'=> 'raw',
                'value' => function($dataProvider){
                    if ($dataProvider->capitation == \app\models\Payment::CAPITATION_CONFIRMED)
                    {
                        return '<span class="label label-success">CONFIRMED</span>';
                    } else if ($dataProvider->capitation == \app\models\Payment::CAPITATION_REJECTED) {
                        return '<span class="label label-danger">REJECTED</span>';
                    } else {
                        return '<span class="label label-default">NO_CONFIRMED</span>';
                    }
                }
            ],
            'capitation_start',
            'capitation_end',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}{update}'],
        ],
    ]); ?>
</div>
