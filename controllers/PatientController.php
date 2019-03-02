<?php

namespace app\controllers;

use app\models\Payment;
use Yii;
use app\models\Patient;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\forms\CreatePatientForm;
use app\forms\UpdatePatientForm;
use app\services\PatientService;

/**
 * PatientController implements the CRUD actions for Patient model.
 */
class PatientController extends Controller
{

    private $patientService;

    public function __construct($id, $module, PatientService $patientService, $config = [])
    {
        $this->patientService = $patientService;
        parent::__construct($id, $module, $config = []);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Patient::find(),
            'pagination' => [ 'pageSize' => 10 ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $patient = $this->findModel($id);
//        $payments = $patient->getPaymentsByDate('2019-03')->all();


//        foreach ($payments as $payment) {
//            echo $payment->id . " " .$payment->date . "</br>";
//        }

        $dataProvider = new ActiveDataProvider([
            'query' => Payment::find()->where(['patient_id' => $id]),
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]]
        ]);

        return $this->render('view', [
            'model' => $patient,
            'provider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $form = new CreatePatientForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $model = $this->patientService->createPatient($form->first_name, $form->last_name, $form->doctor_id);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $patient = $this->findModel($id);

        $form = new UpdatePatientForm($patient);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $model = $this->patientService->updatePatient($form->id, $form->first_name, $form->last_name);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
