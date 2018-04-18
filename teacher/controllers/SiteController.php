<?php
namespace teacher\controllers;

use app\models\Courses;
use app\models\Teachers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $teacher = Teachers::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        $courses = Courses::find()->where([
            'teacher_id' => $teacher->id,
        ])->all();
        
        $acc = [];
        foreach($courses as $i) {
            $acc = array_merge($acc, $this->createCalendar($i));
        }
        $this->view->registerJs("data_array = JSON.parse('" . json_encode($acc) . "');" .
            "link_to_lesson = '" . \yii\helpers\Url::to(['lessons/index', 'id' => '']) . "';"
        );
        $this->view->registerJs($this->renderPartial('../../web/js/calendar.js'));
        $this->view->registerCss($this->renderPartial('../../web/css/calendar.css'));

        return $this->render('index');
    }

    public function createCalendar($course) {
        $acc = [];
        if($schedules = $course->getSchedules()->orderBy('`day`, `number`')->all()) {
            $days = array_fill(0, 7, 0);
            foreach($schedules as $i) {
                $days[$i->day != 7 ? $i->day : 0]++;
            }
            
            $day = date_create($course->begin);
            $i = (int)date('w', date_timestamp_get($day));
            $i == 7 and $init = 0;

            $number = $course->quantity;

            for(; $number > 0; $i < 6 and ++$i or $i = 0) {
                $number -= $days[$i];
                $days[$i] and $acc[] = [
                    'id' => $course->id,
                    'time' => $day->format('Y-m-d'),
                    'course' => $course->name,
                    'group' => $course->getGroup()->one()->name,
                ];
                $day->add(new \DateInterval('P1D'));
            }
        }
        return $acc;
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->loginTeacher()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
