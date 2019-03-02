<?php

namespace app\controllers;

use yii\base\Exception;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use app\forms\CreatePaymentForm;
use app\forms\RejectPaymentForm;
use app\services\PaymentService;
use app\models\Payment;
use app\models\Patient;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct($id,
                                $module,
                                PaymentService $paymentService,
                                $config = []
    )
    {
        $this->paymentService = $paymentService;
        parent::__construct($id, $module, $config = []);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Payment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPay()
    {
        $patient_id = \Yii::$app->getRequest()->getQueryParam('patient_id');
        $form = new CreatePaymentForm($patient_id);

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $model = $this->paymentService->createPayment(
                    $form->date,
                    $patient_id
                );

                return $this->redirect(['payment/view',
                    'id' => $model->id,
                ]);

            } catch (\InvalidArgumentException $e) {
                \Yii::$app->session->setFlash('CreatePay', $e->getMessage());
            }
        }

        $patient = $this->findPatientModel($patient_id);

        return $this->render('pay', [
            'paymentForm' => $form,
            'patient' => $patient,
        ]);

    }

    public function actionReject()
    {
        $patient_id = \Yii::$app->getRequest()->getQueryParam('patient_id');
        $form = new RejectPaymentForm($patient_id);

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {

                $model = $this->paymentService->rejectPayment(
                    $form->date,
                    $form->denial_reason,
                    $patient_id
                );

                return $this->redirect(['payment/view',
                    'id' => $model->id,
                ]);
            } catch (\InvalidArgumentException $e) {
                \Yii::$app->session->setFlash('RejectPay', $e->getMessage());
            }
        }

        $patient = $this->findPatientModel($patient_id);

        return $this->render('reject', [
            'paymentForm' => $form,
            'patient' => $patient,
        ]);

    }

    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findPatientModel($id)
    {
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
