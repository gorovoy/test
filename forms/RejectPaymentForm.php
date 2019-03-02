<?php

namespace app\forms;

use yii\base\Model;

class RejectPaymentForm extends Model
{
    public $patient_id;
    public $denial_reason;
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
            [['patient_id', 'date','denial_reason'], 'required'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['denial_reason'], 'string', 'max' => 255],
            [['patient_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'denial_reason' => 'Причина отказа',
        ];
    }
}