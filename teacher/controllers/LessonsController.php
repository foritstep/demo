<?php

namespace teacher\controllers;

use Yii;
use app\models\Courses;
use app\models\Lessons;
use app\models\Teachers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LessonsController implements the CRUD actions for Lessons model.
 */
class LessonsController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'remove', 'homework', 'mark'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'remove' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lessons models.
     * @return mixed
     */
    public function actionIndex($id = 0)
    {
        if(!$id) {
            $id = Yii::$app->request->cookies->getValue('course_id');
            if(!$id) {
                return $this->redirect(['site/index']);
            }
        } else {
            Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'course_id', 'value' => $id,]));
        }
        $teacher = Teachers::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        if($course = Courses::find()->where(['teacher_id' => $teacher->id, 'id' => $id])->one()) {
            $dataProvider = new ActiveDataProvider([
                'query' => Lessons::find()->where(['course_id' => $course->id]),
            ]);
            $this->view->registerCss("form { display: inline; }");

            return $this->render('index', [
                'course' => $course,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect(['site/index']);
        }
    }

    /**
     * Displays a single Lessons model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $this->view->registerCss("form { display: inline; }");
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lessons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $date)
    {
        $model = new Lessons();

        $model->course_id = $id;
        $model->date = $date;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Lessons model.
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
     * Deletes an existing Lessons model.
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

    public function actionRemove($id)
    {
        $model = $this->findModel($id);
        @unlink("../../uploads/lessons/$model->id." . pathinfo($model->file, PATHINFO_EXTENSION));
        $model->file = "";
        $model->remove_file = true;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionHomework($id = 0) {
        if(!$id) {
            $id = Yii::$app->request->cookies->getValue('homework_id');
            if(!$id) {
                return $this->redirect(['site/index']);
            }
        } else {
            Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'homework_id', 'value' => $id,]));
        }
        $lesson = Lessons::find()->where(['id' => $id])->one();
        $teacher = Teachers::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        if($lesson->getCourse()->one()->teacher_id == $teacher->id) {
            $dataProvider = new ActiveDataProvider([
                'query' => $lesson->getHomeworks(),
            ]);

            return $this->render('homeworks', [
                'homeworks' => $dataProvider,
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionMark($student, $lesson) {
        $homework = \app\models\Homeworks::find()->where(['student_id' => $student, 'lesson_id' => $lesson])->one();
        $teacher = Teachers::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        if($homework && $homework->getLesson()->one()->getCourse()->one()->getTeacher()->one()->id == $teacher->id) {
            $homework->mark = $_POST['Homeworks']['mark'];
            $homework->save();
        }
        return $this->redirect(['homework']);
    }

    /**
     * Finds the Lessons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lessons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lessons::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
