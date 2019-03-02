<?php

namespace app\controllers;

use app\models\Patient;
use Yii;
use app\models\Doctor;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\forms\MonthSelectorForm;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;


/**
 * DoctorController implements the CRUD actions for Doctor model.
 */
class DoctorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Doctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Doctor::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Doctor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $form = new MonthSelectorForm();
        $model = $this->findModel($id);

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            //
        }

        $count = Yii::$app->db->createCommand(' SELECT * FROM Patient, Payment WHERE Patient.doctor_id=:doctor_id ', [':doctor_id' => 1])->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => 'SELECT DISTINCT (patient.id) as patient_id, 
                        capitation, 
                        first_name, 
                        last_name, 
                        status, 
                        denial_reason, 
                        date 
                        FROM Patient 
                        LEFT JOIN `payment` 
                        ON `payment`.`patient_id` = `patient`.`id` 
                        WHERE `patient`.`doctor_id`= :doctor_id 
                        AND date BETWEEN :start AND :end ',
            'params' => [':doctor_id' =>  $id, ':start' =>  $form->getBeginDate(), ':end' =>  $form->getEndDate(),],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // возвращает массив данных
        $models = $provider->getModels();



        return $this->render('view', [
            'model' => $model,
            'provider' => $provider,
            'monthSelectorForm' => $form,
        ]);
    }

    /**
     * Creates a new Doctor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Doctor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Doctor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Doctor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Doctor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
