<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */

$this->title = $model->id . " " . $model->patient->getPatientName(); ;
$this->params['breadcrumbs'][] = ['label' => 'Payment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="doctor-view">

    <h1>Payments of <?= $model->patient->getPatientName(); ?></h1>

    <p>
        <?php /* echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
            'denial_reason',
            [
                'attribute' => 'patient',
                'format'=> 'raw',
                'value' => function($provider){
                    $url = Url::to(['patient/view', 'id' => $provider->patient_id]);
                    return Html::a($provider->patient->getPatientName(), $url, ['title' => 'view']);
                }
            ],
            'date',
        ],
    ]) ?>
    <?php


    ?>

</div>
