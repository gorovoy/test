<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $status
 * @property string $denial_reason
 * @property string $date
 *
 * @property Payment $patient
 */
class Payment extends \yii\db\ActiveRecord
{
    const CAPITATION_CONFIRMED = 1;
    const CAPITATION_REJECTED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    public static function create($date, $patient_id)
    {
        $patient = new Payment();
        $patient->date = $date;
        $patient->status = self::CAPITATION_CONFIRMED;
        $patient->patient_id = $patient_id;
        return $patient;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patient_id' => 'Patient ID',
            'status' => 'Status',
            'denial_reason' => 'Denial Reason',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }

}
