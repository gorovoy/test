<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Patient */

$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="patient-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>New patients</h2>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'Patient',
                'filter' => '',
                'content' => function ($provider) {
                    return Html::a($provider->getPatientName(), ['patient/view', 'id' => $provider->id]);
                }
            ],
            [
                'attribute' => 'Action',
                'format' => 'raw',
                'value' => function ($provider) {
                    return Html::a('<span class="glyphicon glyphicon-save" aria-hidden="true"></span> Create emails', ['email-for-patient', 'id' =>  $provider->id], ['class' => 'btn btn-primary', 'target' => '_blank']);
                }
            ],
        ],
    ]) ?>
</div>
<?= Html::a('Create emails for all patients', ['email-for-patients', 'capitation' =>  \app\models\Payment::CAPITATION_CONFIRMED], ['class' => 'btn btn-success', 'target' => '_blank']);?>

