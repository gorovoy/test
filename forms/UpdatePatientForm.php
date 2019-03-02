<?php

namespace app\forms;

use app\models\Patient;
use yii\base\Model;

class UpdatePatientForm extends Model
{
    public $id;
    public $first_name;
    public $last_name;
    private $patient;

    public function __construct(Patient $patient, $config = [])
    {
        $this->patient = $patient;
        parent::__construct($config);
    }

    public function init()
    {
        $this->id = $this->patient->id;
        $this->first_name = $this->patient->first_name;
        $this->last_name = $this->patient->last_name;
    }

    public function rules()
    {
        return [
            [['id', 'first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
        ];
    }
}