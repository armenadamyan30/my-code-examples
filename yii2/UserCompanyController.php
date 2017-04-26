<?php

namespace frontend\modules\company\controllers;

use Yii;
use frontend\modules\company\models\UserCompany;
use frontend\modules\company\models\UserCompanySearch;
use frontend\modules\userproducts\models\Products;
use frontend\modules\socialization\models\User;
use yii\console\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use frontend\models\Lang;

/**
 * UserCompanyController implements the CRUD actions for UserCompany model.
 */
class UserCompanyController extends Controller
{
    public $layout = 'company';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['create', 'update'],
                'rules' => [
                    // deny all POST requests
                    [
                        'allow' => false,
                        'verbs' => ['POST']
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all UserCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'company';
        $id =  Yii::$app->user->identity->id;
        $User = User::find()->where(['id'=>$id])->one();
        $company = UserCompany::find()->where(['user_id'=>$id])->one();
        return $this->render('index', [
            'company' => $company,
            'User' => $User,
            'curlang' => Lang::getCurrent(),
        ]);
    }

    /**
     * Displays a single UserCompany model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $id = Yii::$app->user->identity->id;
        $model = new UserCompany();
        $session = Yii::$app->session;
        $step = 1;

        try {
            $data = Yii::$app->request->post('UserCompany');
            switch (Yii::$app->request->post('step')) {
                case 1:
                    $step = 2;
                    $session['data1'] = Yii::$app->request->post();
                    break;
                case 2:
                    $session['data2'] = Yii::$app->request->post();
                    $step = 3;
                    break;
                case 3:
                    $model->load($session['data1']);
                    $model->load($session['data2']);
                    $model->load(Yii::$app->request->post());
                    $model->user_id = $id;
                    $model->logo = UploadedFile::getInstance($model,'logo');
                    $model->date = date("Y-M-d");
                    $step = 4;
                    break;
                default:
                    throw new Exception('Invalid Value');
                    break;
            }
        } catch (\rmrevin\yii\minify\Exception $e){
            $e->getMessage();
        }
        if ($step == 4) {
            try{
                if (Yii::$app->user->isGuest) {
                    throw new Exception('You are not registered');
                } else {
                    if (!empty($model->logo)) {
                        $structure = 'images/companies/' . $id;
                        if (!file_exists($structure)) {
                            if (!mkdir($structure . '/mini', 0700, true) ||
                                !mkdir($structure . '/normal', 0700, true)
                            ) {
                                throw new Exception('Cannot create a directory');
                            }
                        }
                        // SET New image properties
                        $UimageName = Yii::$app->security->generateRandomString().'.'.$model->logo->extension;;
                        $Uimageputh = $structure.'/'.$UimageName;
                        $model->logo->SaveAs($Uimageputh);
                        $model->logo = $UimageName;
                        // image resize to
                        $file=Yii::getAlias($Uimageputh);
                        $image_norm=Yii::$app->image->load($file);
                        $image_norm->resize(216, 216, $master= 'CROP');
                        $image_norm->save(Yii::getAlias('@webroot/'.$structure.'/normal/'.$UimageName), $quality = 80);
                        $image_mini = $image_norm;
                        $image_mini->resize(48, 48, $master = 'CROP');
                        $image_mini->save(Yii::getAlias('@webroot/'.$structure.'/mini/'.$UimageName), $quality = 80);
                    }
                    $model->save();
                    return $this->redirect(['create', 'id' => $model->id]);

                }
            }catch (\rmrevin\yii\minify\Exception $e){
                $e->getMessage();
            }


        }
            return $this->render('_form'.$step, [
                'model' => $model,
                'step' => $session['step'],
            ]);

    }

    /**
     * Updates an existing UserCompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $comp = UserCompany::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        $model = $this->findModel($comp->id);
        try{
            if ($model->load(Yii::$app->request->post()) ) {
                $model->logo = UploadedFile::getInstance($model,'logo');
                if (!empty($model->logo)) {
                    $structure = 'images/companies/' . $comp->user_id;
                    if (!file_exists($structure)) {
                        if (!mkdir($structure . '/mini', 0700, true) ||
                            !mkdir($structure . '/normal', 0700, true)
                        ) {
                            throw new Exception('Cannot create a directory');
                        }
                    }
                    if(!empty($comp->logo)){
                        unlink($structure.'/normal/'.$comp->logo);
                        unlink($structure.'/mini/'.$comp->logo);
                    }
                    // SET New image properties
                    $UimageName = Yii::$app->security->generateRandomString().'.'.$model->logo->extension;;
                    $Uimageputh = $structure.'/'.$UimageName;
                    $model->logo->SaveAs($Uimageputh);
                    $model->logo = $UimageName;
                    // image resize to
                    $file=Yii::getAlias($Uimageputh);
                    $image_norm=Yii::$app->image->load($file);
                    $image_norm->resize(200, 200, $master= 'CROP');
                    $image_norm->save(Yii::getAlias('@webroot/'.$structure.'/normal/'.$UimageName), $quality = 100);
                    $image_mini = $image_norm;
                    $image_mini->resize(50, 50, $master = 'CROP');
                    $image_mini->save(Yii::getAlias('@webroot/'.$structure.'/mini/'.$UimageName), $quality = 100);
                }
                else $model->logo = $comp->logo;
                $model->save();
                return $this->redirect(['index', 'id' => $model->id]);
            }
        } catch (\rmrevin\yii\minify\Exception $e){
            $e->getMessage();
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing UserCompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
