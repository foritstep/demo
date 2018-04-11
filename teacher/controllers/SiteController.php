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
        $data = Courses::find()->where([
            'teacher_id' => $teacher->id,
        ])->all();
        
        $acc = [];
        foreach($data as $i) {
            $time = new \DateTime();
            $schedules = $i->getSchedules();
            $schedules = $schedules->orderBy('`day`, number');
            $schedules = $schedules->all();
            if($schedules) {
                for($j = 0; $j < $i->quantity;) {
                    foreach($schedules as $s) {
                        $p = 'P' . $s->day . 'D';
                        $acc[] = [
                            'name' => $i->name,
                            'time' => (clone $time)->add(new \DateInterval($p))->format('Y-m-d'),
                            'classroom' => $s->getClassroom()->one()->name,
                            'group' => $i->getGroup()->one()->name,
                            'course' => $i->id,
                        ];
                        $j++;
                    }
                    $time = $time->add(new \DateInterval('P7D'));
                }
            }
        }
        $this->view->registerJs("data_array = JSON.parse('" . json_encode($acc) . "');" .
            "link_to_lesson = '" . \yii\helpers\Url::to(['lessons/create', 'id' => '']) . "';"
        );
        $this->view->registerJs($this->renderPartial('../../web/js/calendar.js'));
        $this->view->registerCss($this->renderPartial('../../web/css/calendar.css'));

        return $this->render('index', [
            'data' => $data,
        ]);
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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
