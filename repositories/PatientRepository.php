<?php

namespace app\repositories;

use app\models\Patient;

class PatientRepository
{
    /**
     * @param $id
     * @return Patient
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$patient = Patient::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $patient;
    }

    public function add(Patient $patient)
    {
        if (! $patient->doctor_id) {
            throw new \InvalidArgumentException('doctor_id no selected');
        }
        $patient->insert(false);
    }

    public function save(Patient $patient)
    {
        if ($patient->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $patient->update(false);
    }
} 