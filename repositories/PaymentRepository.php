<?php

namespace app\repositories;

use app\models\Payment;

class PaymentRepository
{
    /**
     * @param $id
     * @return Payment
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$payment = Payment::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $payment;
    }

    public function findByDate($date, $patient_id, $status)
    {
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        return Payment::find()->where('patient_id = ' . $patient_id . ' AND YEAR(date) = ' . $year . ' AND MONTH(date) = ' . $month )->one();
    }

    public function findPayedByPatientId($patient_id)
    {
        return Payment::find()->where('patient_id = ' . $patient_id . ' AND status = ' . Payment::CAPITATION_CONFIRMED )->one();
    }

    public function findRejectedByPatientId($patient_id)
    {
        return Payment::find()->where('patient_id = ' . $patient_id . ' AND status = ' . Payment::CAPITATION_REJECTED )->one();
    }

    public function add(Payment $payment)
    {
        if (! $payment->patient_id) {
            throw new \InvalidArgumentException('patient_id no selected');
        }
        $payment->insert(false);
    }

    public function save(Payment $payment)
    {
        if ($payment->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $payment->update(false);
    }
} 