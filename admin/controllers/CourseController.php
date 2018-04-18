<?php

namespace admin\controllers;

use Yii;
use app\models\Courses;
use app\models\Schedules;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CourseController implements the CRUD actions for Courses model.
 */
class CourseController extends Controller
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
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'schedule', 'intersect', 'test'],
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
     * Lists all Courses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Courses::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Courses model.
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
     * Creates a new Courses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Courses();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Courses model.
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
     * Deletes an existing Courses model.
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

    public function actionSchedule($id) {        
        $model = $this->findModel($id);

        if(Yii::$app->request->post()) {
            $model->schedule = Yii::$app->request->post()['arr'];
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('schedule', [
            'model' => $model,
        ]);
    }

    public static function intersect() {
        if($data = Courses::intersect()) {
            Yii::$app->session->setFlash('error', 
                Html::a("Пересечение графиков", ['intersect'])
            );
        }
    }

    public function actionIntersect() {
        $data = Courses::intersect();

        return $this->render('intersect', [
            'data' => $data,
        ]);
    }

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // #->
    function group1($arr, $key) {
        $acc = [];
        foreach($arr as $i) {
            $acc[$i[$key]][] = $i;
        }
        return $acc;
    }
    
    function group2($arr, $key) {
        $acc = [];
        foreach($arr as $k => $v) {
            $acc[$k] = $this->group1($v, $key);
        }
        return $acc;
    }
    
    function group3($arr, $key) {
        $acc = [];
        foreach($arr as $k => $v) {
            $acc[$k] = $this->group2($v, $key);
        }
        return $acc;
    }
    
    function search($d, $key) {
        $d = $this->group3($d, $key);
        $i = [];
        
        foreach($d as $l0) {
            foreach($l0 as $l1) {
                foreach($l1 as $l2) {
                    count($l2) > 1 and $i[] = $l2;
                }
            }
        }
        return $i;
    }
    
    public function actionTest()
    {
        $all = [];
        foreach(Courses::find()->all() as $i) {
            $all = array_merge($all, $this->test($i));
        }

        $all = $this->group1($all, 'date');
        $all = $this->group2($all, 'number');

        return json_encode($this->search($all, 'classroom'));
    }

    public function test($course)
    {
        $acc = [];
        if($schedules = $course->getSchedules()->orderBy('`day`, `number`')->all()) {
            $days = array_fill(0, 7, []);
            foreach($schedules as $i) {
                $days[$i->day != 7 ? $i->day : 0][] = $i;
            }

            $day = date_create($course->begin);
            $i = (int)date('w', date_timestamp_get($day));
            $i == 7 and $init = 0;

            $number = $course->quantity;

            for(; $number > 0; $i < 6 and ++$i or $i = 0) {
                foreach($days[$i] as $s) {
                    if($number--) {
                        $acc[] = [
                            'date' => $day->format('Y-m-d'),
                            'number' => $s->number,
                            'teacher' => $course->teacher_id,
                            'classroom' => $s->classroom_id,
                            'course' => $s->course_id,
                            'group' => $course->group_id,
                        ];
                    }
                }
                $day->add(new \DateInterval('P1D'));
            }
        }
        return $acc;
    }

    // <-#    
}
