<?php

namespace admin\controllers;

use Yii;
use app\models\Courses;
use app\models\Schedules;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
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

        $this->view->registerJs("test_path = '" . Url::to(['test', 'id' => $id]) . "';");
        $this->view->registerJsFile("@web/js/intersect.js");

        return $this->render('schedule', [
            'model' => $model,
        ]);
    }

    public static function intersect() {
        if(count(static::createList())) {
            Yii::$app->session->setFlash('error',
                Html::a("Пересечение графиков", ['course/intersect'])
            );
        }
    }

    public function actionIntersect() {
        $raw = static::createList();
        $data = [];

        foreach($raw as $l0) {
            foreach($l0 as $i) {
                $data[] = [
                    'date' => $i['date'],
                    'number' => $i['number'],
                    'classroom' => \app\models\Classrooms::find()->where(['id' => $i['classroom']])->one()->name,
                    'classroom_id' => $i['classroom'],
                    'course' => Courses::find()->where(['id' => $i['course']])->one()->name,
                    'course_id' => $i['course'],
                    'group_id' => $i['group'],
                ];
            }
        }

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
            $acc[$k] = static::group1($v, $key);
        }
        return $acc;
    }

    function group3($arr, $key) {
        $acc = [];
        foreach($arr as $k => $v) {
            $acc[$k] = static::group2($v, $key);
        }
        return $acc;
    }

    function search($d, $key) {
        $d = static::group3($d, $key);
        $i = [];

        foreach($d as $l0) {
            foreach($l0 as $l1) {
                foreach($l1 as $l2) {
                    if(count($l2) > 1){
                        $i[] = $l2;
                    }
                }
            }
        }
        return $i;
    }

    public function createList($id = 0, $schedules = "") {
        $all = [];
        $schedules = json_decode($schedules);
        foreach(Courses::find()->all() as $i) {
            if($i->id == $id) {
                $all = array_merge($all, static::test($i, $schedules));
            } else {
                $all = array_merge($all, static::test($i));
            }
        }

        $all = static::group1($all, 'date');
        $all = static::group2($all, 'number');

        return static::search($all, 'classroom');
    }

    public function actionTest($id, $schedules)
    {
        return json_encode(static::createList($id, $schedules));
    }

    public function test($course, $schedules = [])
    {
        $acc = [];
        if($schedules or $schedules = $course->getSchedules()->orderBy('`day`, `number`')->all()) {
            $days = array_fill(0, 7, []);
            foreach($schedules as $i) {
                $days[$i->day != 7 ? $i->day : 0][] = $i;
            }

            $day = date_create($course->begin);
            $i = (int)date('w', date_timestamp_get($day));
            $i == 7 and $init = 0;

            $number = $course->quantity;

            /**
             * данный код позволяет образовать цикл в котором
             * i будет повторятся от 0 до 6 включительно
             * после 6 будет идти 0
             */ 
            for(; $number > 0; $i < 6 and ++$i or $i = 0) {
                foreach($days[$i] as $s) {
                    if($number--) {
                        $acc[] = [
                            'date' => $day->format('Y-m-d'),
                            'number' => $s->number,
                            'teacher' => $course->teacher_id,
                            'classroom' => $s->classroom_id,
                            'course' => $course->id,
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
