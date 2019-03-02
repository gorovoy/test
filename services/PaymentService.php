<?php
namespace app\services;

use app\models\Payment;
use app\repositories\PaymentRepository;
use app\models\Patient;
use app\repositories\PatientRepository;
use app\services\Logger;

class PaymentService
{
    private $logger;
    private $transactionManager;
    private $paymentRepository;
    private $patientRepository;

    public function __construct(PaymentRepository $paymentRepository,
                                PatientRepository $patientRepository,
                                TransactionManager $transactionManager,
                                Logger $logger
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->patientRepository = $patientRepository;
        $this->transactionManager = $transactionManager;
        $this->logger = $logger;
    }

    //todo  Проверить что дата платежа не раньше даты начала свойста CaptationStart
    public function createPayment($date, $patient_id)
    {
        $this->isNotPayedByDate($date, $patient_id);
        $this->isNotRejected($patient_id);
        $patient = $this->patientRepository->find($patient_id);
        $transaction = $this->transactionManager->begin();
        try {

            if ($patient->capitation != Payment::CAPITATION_CONFIRMED) {
                $patient->capitation = Payment::CAPITATION_CONFIRMED;
                $patient->capitation_start = $date;
                $this->patientRepository->save($patient);
            }
            $payment = Payment::create($date, $patient_id);
            $this->paymentRepository->add($payment);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->logger->log('Payment (Pay)' . $payment->id . ' is created');

        return $payment;
    }

    //todo Проверить что дата отказа не раньше даты начала войста CaptationStart
    public function rejectPayment($date, $denial_reason, $patient_id)
    {
        $this->isNotRejected($patient_id);
        $this->isPayed($patient_id);

        $patient = $this->patientRepository->find($patient_id);
        $transaction = $this->transactionManager->begin();
        try {
            $patient->capitation = Payment::CAPITATION_REJECTED;
            $patient->capitation_end = $date;
            $this->patientRepository->save($patient);

            $payment = Payment::create($date, $patient_id);
            $payment->status = Payment::CAPITATION_REJECTED;
            $payment->denial_reason = $denial_reason;
            $this->paymentRepository->add($payment);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->logger->log('Payment (Reject)' . $payment->id . ' is created');

        return $payment;
    }

    /**
     * @param $date
     * @param $patient_id
     */
    private function isNotPayedByDate($date, $patient_id)
    {
        if ($this->paymentRepository->findByDate($date, $patient_id, Payment::CAPITATION_CONFIRMED)) {
            throw new \InvalidArgumentException('Only one pay operation per month is allowed.');
        }
    }

    private function isPayed($patient_id)
    {
        if (! $this->paymentRepository->findPayedByPatientId($patient_id)) {
            throw new \InvalidArgumentException('Patient have not any paid');
        }
    }

    /**
     * @param $patient_id
     */
    private function isNotRejected($patient_id)
    {
        if ($this->paymentRepository->findRejectedByPatientId($patient_id)) {
            throw new \InvalidArgumentException('Patient is rejected.');
        }
    }

}