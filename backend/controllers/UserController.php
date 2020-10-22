<?php

namespace backend\controllers;


use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws ForbiddenHttpException if admin user does not have access
     */
    public function actionCreate()
    {
        /* if (!!Yii::$app->user->can('createUser')) {
            throw new ForbiddenHttpException('This user can`t create new users.');
        } */

        $user = Yii::$app->user->identity;
        if (($user->group_id !== 1) && $user->group_id !== 2) {
            throw new ForbiddenHttpException('User with group id ' . var_export($user->group_id, true) . ' can`t create new users.');
        }

        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException if admin user does not have access
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('updateUser')) {
            throw new ForbiddenHttpException('User can`t update users.');
        }

        $user = Yii::$app->user->identity;
        if (($user->group_id !== 1) && ($user->group_id !== 3)) {
            throw new ForbiddenHttpException('User with group id ' . var_export($user->group_id, true) . ' can`t update other users data.');
        }

        $postUserId = Yii::$app->request->post('User')['id'];
        if (Yii::$app->request->isPost && !is_null($postUserId)) {
            $resCanChangeStatus = User::adminCanChangeStatus($user);
            if (!$resCanChangeStatus) {
                throw new ForbiddenHttpException('Can`t change admin user status to ' . var_export(Yii::$app->request->post('User')['status'], true));
            }
        }

        $formName = '_form';
        if ($user->group_id == 3) {
            $formName = '_form_moderator';
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'formName' => $formName,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException if admin user does not have access
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('updateUser')) {
            throw new ForbiddenHttpException('User can`t delete users.');
        }

        $user = Yii::$app->user->identity;
        if ($user->group_id !== 1) {
            throw new ForbiddenHttpException('User with group id ' . var_export($user->group_id, true) . ' can`t remove users.');
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
