<?php

namespace app\forms;

use yii\base\Model;

class CreatePatientForm extends Model
{
    public $first_name;
    public $last_name;
    public $doctor_id;

    public function init()
    {
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'doctor_id'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['doctor_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'doctor_id' => 'Doctor',
        ];
    }
}