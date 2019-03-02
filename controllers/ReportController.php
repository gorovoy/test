<?php
namespace app\controllers;

use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\Patient;
use app\models\Payment;
use yii\db\Query;
use kartik\mpdf\Pdf;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ReportController extends \yii\web\Controller
{
    // New patients
    public function actionNew()
    {
        $provider = new ActiveDataProvider([
            'query' => Patient::find()
                ->select(['COUNT(payment.status) AS status_count', 'patient.id', 'first_name', 'last_name'])
                ->leftJoin('payment', 'patient.id = payment.patient_id')
                ->groupBy(['first_name'])
                ->having(['status_count' => 1])
                ->andWhere(['=','patient.capitation', Payment::CAPITATION_CONFIRMED])
                ->orderBy('patient_id')]);

        return $this->render('new', [
            'provider' => $provider
        ]);
    }

    // Lost patients
    public function actionLost()
    {
        $provider = new ActiveDataProvider([
            'query' => Patient::find()
                ->select(['patient.id', 'first_name', 'last_name'])
                ->leftJoin('payment', 'patient.id = payment.patient_id')
                ->groupBy(['first_name'])
                ->andWhere(['=','patient.capitation', Payment::CAPITATION_REJECTED])
                ->orderBy('patient_id')]);

        return $this->render('lost', [
            'provider' => $provider
        ]);
    }

    /**
     * Email for one patients
     * @param $id - patient id
     * @return .pdf
     * @throws NotFoundHttpException
     */
    public function actionEmailForPatient($id)
    {
        $patient = Patient::findOne($id);

        if (!$patient) throw new NotFoundHttpException("Patient not found");

        $mail_teamplate = ($patient->capitation == Payment::CAPITATION_CONFIRMED) ? 'mailForNewPatient' : 'mailForLostPatient';
        $content = self::createEmailContentForPatient($patient, $mail_teamplate);

        return self::createPdf($content);
    }

    /**
     * Email for patients group
     * @param $capitation - payment status of patient, Confirmed or Reject
     * @return .pdf
     */
    public function actionEmailForPatients($capitation)
    {
        $patients = ($capitation == Payment::CAPITATION_CONFIRMED) ? self::getNewPatients() : self::getLostPatients();
        $mail_teamplate = ($capitation == Payment::CAPITATION_CONFIRMED) ? 'mailForNewPatient' : 'mailForLostPatient';
        $content = self::createEmailContentForPatient($patients, $mail_teamplate);

        return self::createPdf($content);
    }

    /**
     * Create email content for one or group of patients
     * @param $patients
     * @param $mail_teamplate
     * @return string
     */
    protected function createEmailContentForPatient($patients, $mail_teamplate)
    {
        $patients = isset ($patients[0]) ? $patients : array($patients);
        $content = '';
        $patients_quantity = count($patients);
        $counter = 0;

        foreach ($patients as $patient) {

            $content .= $this->renderPartial($mail_teamplate, [
                'name' => $patient->getPatientName()
            ]);

            $counter++;

            if ($counter < $patients_quantity) {
                $content .= '<pagebreak />';
            }
        }

        return $content;
    }

    protected function createPdf($content)
    {
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        ]);

        $pdf->marginTop = 10;
        $pdf->marginLeft = 10;
        $pdf->marginRight = 10;
        $pdf->marginBottom = 10;

        return $pdf->render();
    }

    private function getNewPatients()
    {
        return Patient::find()
            ->select(['COUNT(payment.status) AS status_count', 'first_name', 'last_name'])
            ->leftJoin('payment', 'patient.id = payment.patient_id')
            ->groupBy(['first_name'])
            ->having(['status_count' => 1])
            ->andWhere(['=','patient.capitation', Payment::CAPITATION_CONFIRMED])
            ->orderBy('patient_id')
            ->all();
    }

    private function getLostPatients()
    {
        return Patient::find()
            ->select(['first_name', 'last_name'])
            ->leftJoin('payment', 'patient.id = payment.patient_id')
            ->groupBy(['first_name'])
            ->andWhere(['=','patient.capitation', Payment::CAPITATION_REJECTED])
            ->orderBy('patient_id')
            ->all();
    }
}
