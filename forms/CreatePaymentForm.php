<?php

namespace app\forms;

use yii\base\Model;

class CreatePaymentForm extends Model
{
    public $patient_id;
    public $date;

    public function __construct($patient_id, $config = [])
    {
        $this->patient_id = $patient_id;
        parent::__construct($config);
    }

    public function init()
    {
        $this->date = date('Y-m-d');

    }

    public function rules()
    {
        return [
            [['patient_id', 'date'], 'required'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['patient_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'patient_id' => 'Пациент',
        ];
    }
}