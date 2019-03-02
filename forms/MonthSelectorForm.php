<?php

namespace app\forms;

use yii\base\Model;

class MonthSelectorForm extends Model
{
    public $date;

    public function __construct( $config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        $this->date = empty($this->date) ? date('M-Y', strtotime('+2 days')): $this->date;
    }

    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date'], 'date', 'format' => 'php:M-Y'],
        ];
    }

    public function getMonth()
    {
        return date("Y-M", strtotime($this->date));
    }

    public function getBeginDate()
    {
        return date("Y-m-d", strtotime($this->date));
    }

    public function getEndDate()
    {
        return date("Y-m-t", strtotime($this->date));
    }

    public function attributeLabels()
    {
        return [
            'date' => 'Период: ' . self::getMonth(),
        ];
    }
}