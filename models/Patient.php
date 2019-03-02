<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "patient".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $doctor_id
 * @property int $capitation
 * @property string $capitation_start
 * @property string $capitation_end
 *
 * @property Doctor $doctor
 */
class Patient extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'patient';
    }

    public static function create($firstName, $lastName, $doctor_id)
    {
        $patient = new Patient();
        $patient->first_name = $firstName;
        $patient->last_name = $lastName;
        $patient->doctor_id = $doctor_id;
        return $patient;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(Doctor::class, ['id' => 'doctor_id']);
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['patient_id' => 'id']);
    }

    public function getPatientName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
