<?php
namespace app\services;

use app\models\Patient;
use app\repositories\PatientRepository;
use app\services\Logger;

class PatientService
{
    private $logger;
    private $patientRepository;

    public function __construct(PatientRepository $patientRepository, Logger $logger)
    {
        $this->patientRepository = $patientRepository;
        $this->logger = $logger;
    }

    public function createPatient($firstName, $lastName, $doctor_id)
    {
        $patient = Patient::create($firstName, $lastName, $doctor_id);
        $this->patientRepository->add($patient);

        $this->logger->log('Patient ' . $patient->id . ' is created');
        return $patient;
    }

    public function updatePatient($id, $firstName, $lastName)
    {
        $patient =  $this->patientRepository->find($id);
        $patient->last_name = $lastName;
        $patient->first_name = $firstName;
        $this->patientRepository->save($patient);

        $this->logger->log('Patient ' . $patient->id . ' is updated');
        return $patient;
    }
}