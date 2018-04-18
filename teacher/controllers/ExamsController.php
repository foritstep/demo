<?php

namespace teacher\controllers;

use Yii;
use app\models\Courses;
use app\models\Exams;
use app\models\Teachers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * ExamsController implements the CRUD actions for Exams model.
 */
class ExamsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Exams models.
     * @return mixed
     */
    public function actionIndex($id = 0)
    {
        if(!$id) {
            $id = Yii::$app->request->cookies->getValue('exam_id');
            if(!$id) {
                return $this->redirect(['site/index']);
            }
        } else {
            Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'exam_id', 'value' => $id,]));
        }
        $teacher = Teachers::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        if($course = Courses::find()->where(['teacher_id' => $teacher->id, 'id' => $id])->one()) {
            $dataProvider = new ActiveDataProvider([
                'query' => Exams::find()->where(['course_id' => $id]),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'course_id' => $course->id,
            ]);
        } else {
            return $this->redirect(['site/index']);
        }
    }

    /**
     * Displays a single Exams model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Exams model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Exams();
        $model->course_id = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Exams model.
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
     * Deletes an existing Exams model.
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
     * Finds the Exams model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Exams the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Exams::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
