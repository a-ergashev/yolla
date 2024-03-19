<?php

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Vacancy;
use app\models\Users;
use app\models\Skill;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string | Response
     * @throws Exception
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) return $this->redirect(['/site/login']);

        $ids = Yii::$app->db->createCommand('
            SELECT DISTINCT(vacancy_skill.vacancy_id) as id
            from user_skill
            join vacancy_skill
            on user_skill.skill_id = vacancy_skill.skill_id
            WHERE user_skill.user_id = :id;')
            ->bindValue(':id', Yii::$app->user->id)
            ->queryAll();
        $vacancies = Vacancy::findAll($ids);
        
        $arr = array_column($ids, 'id');
        $appliedVacs = (new yii\db\Query())
            ->select('vacancy_id')
            ->from('user_vacancy')
            ->where(['vacancy_id' => $arr, 'user_id' => Yii::$app->user->id])
            ->column();

        return $this->render('index', [
            'vacancies' => $vacancies,
            'applieds' => $appliedVacs
        ]);
    }
    
    /**
     * Sign up action
     * 
     * @return Response|string
     */
    public function actionSignup(){
        
        $model = new Users();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $existingUser = Users::find()->where(['email' => $model->email])->exists();
            if (!$existingUser) {
                $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                if ($model->save()) {
                    // User successfully saved
                    return $this->goBack();
                } else {
                    Yii::error('Error saving user: ' . print_r($model->errors, true));
                }
            } else {
                Yii::$app->session->setFlash('error', 'User with this email already exists.');
            }
        }

        return $this->render('signup', ['model' => $model]);
        
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionApply(){
        $vacId = Yii::$app->request->post('id');
        $userId = Yii::$app->user->id;

        Yii::$app->db->createCommand('
                insert into 
                user_vacancy (user_id, vacancy_id)
                VALUES (:user_id, :vacancy_id);')
                ->bindValue(':user_id', $userId)
                ->bindValue(':vacancy_id', $vacId)
                ->execute();
        
        return $vacId;
    }
    
    /**
     * Displays profile page.
     *
     * @return string
     */
    public function actionProfile()
    {
        if(Yii::$app->user->isGuest) return $this->redirect(['/site/login']);

        $model = new Skill();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $user = Users::findone(Yii::$app->user->id);
            $skill = Skill::find()->where(['name' => $model->name])->one();
            
            $user->link('skills', $skill);
            $user->save();
        }

        $user_skills = Yii::$app->user->identity->skills;
        
        $skills = array_diff(Skill::find()->select(['name'])->column(),
            array_column($user_skills, 'name'));
        
        return $this->render('profile', [
            'model' => $model,
            'skills' => $skills,
            'user_skills' => $user_skills
        ]);
    }
}