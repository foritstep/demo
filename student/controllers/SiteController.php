<?php
namespace student\controllers;

use app\models\Exams;
use app\models\Students;
use app\models\Lessons;
use app\models\Homeworks;
use app\models\UploadForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;
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
                        'actions' => ['logout', 'index', 'upload', 'delete', 'exam'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['post'],
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
        $student = Students::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();

        $courses = $student->getGroup()->one()->getCourses()->all();
        $courses_id = [];
        foreach($courses as $i) {
            $courses_id[] = $i->id;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Lessons::find()
                ->select('`lessons`.`id`, `course_id`, `theme`, `homework`, `lessons`.`file`, `lessons`.`date`, `homeworks`.`id` AS `h_id`, `homeworks`.`file` AS `h_file`, `mark`, `homeworks`.`date` AS `h_date` ')
                ->where(['course_id' => $courses_id])
                ->join('LEFT JOIN', 'homeworks', 'lessons.id = homeworks.lesson_id AND homeworks.student_id = ' . $student->id)
                ->with('homeworks'),
        ]);

        $tmp = $dataProvider->getModels();

        $this->view->registerCss("form { display: inline; }");

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'student_id' => $student->id,
        ]);
    }

    public function actionUpload($id)
    {
        $model = new UploadForm();
        $student = Students::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $homework = Homeworks::find()->where(['lesson_id' => $id, 'student_id' => $student->id])->one();
            if(!$homework) {
                $homework = new Homeworks();
                $homework->student_id = $student->id;
                $homework->lesson_id = $id;
            } else {
                @unlink("../../uploads/homeworks/{$homework->id}-{$student->id}." . pathinfo($homework->file, PATHINFO_EXTENSION));
            }
            if ($model->upload($id, $student->id)) {
                $homework->mark = NULL;
                $homework->date = (new \DateTime())->format('Y-m-d');
                $homework->file = $model->file->basename . '.' . $model->file->extension;
                $homework->save();
            }
        }
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $student = Students::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        if($homework = Homeworks::find()->where(['lesson_id' => $id, 'student_id' => $student->id])->one()) {
            @unlink("../../uploads/homeworks/{$homework->id}-{$student->id}." . pathinfo($homework->file, PATHINFO_EXTENSION));
            $homework->delete();
        }
        return $this->redirect(['index']);
    }

    public function actionExam()
    {
        $student = Students::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        
        $dataProvider = new ActiveDataProvider([
            'query' => Exams::find()->where(['student_id' => $student->id]),
        ]);

        return $this->render('exams', [
            'dataProvider' => $dataProvider,
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
        if ($model->load(Yii::$app->request->post()) && $model->loginStudent()) {
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
