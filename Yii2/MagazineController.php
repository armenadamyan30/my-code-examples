<?php

namespace app\modules\main\controllers;

use common\models\Languages;
use common\models\MagazineArticle;
use common\models\MagazineLanguage;
use Yii;
use common\models\Magazine;
use common\models\Catalog;
use common\models\Products;
use common\models\Wishlist;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\controllers\MyFrontendController;
use yii\data\Pagination;


/**
 * WishlistController implements the CRUD actions for Wishlist model.
 */
class MagazineController extends MyFrontendController
{

    public $itemsPerPage = 12; //3x4

    /**
     * Lists all Wishlist models.
     * @return mixed
     */

    public function init()
    {

        parent::init();

    }

    public function actionIndex()
    {

        $lang = substr(Yii::$app->language, 0, 2);
        $lang_id = Languages::findOne(['code' => $lang]);
        $magazinesLanguage = MagazineLanguage::find()->where(['lang_' . $lang_id->id => 1])->all();
        $magazine_ids = array();

        foreach ($magazinesLanguage as $magazineLanguage) {
            $magazine_ids[] = $magazineLanguage['magazine_id'];
        }

        $query = Magazine::find()->where(['in', 'id', $magazine_ids])->andWhere(['status' => 1])->orderBy(['create_date' => SORT_DESC]);
        $defaultPageSize = $this->itemsPerPage;
        $magazine_count = $query->count();

        $pagination = new Pagination([
            'defaultPageSize' => $defaultPageSize,
            'totalCount' => $magazine_count,
            'route' => '/' . $lang . '/magazine',
        ]);

        $magazine = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        foreach ($magazine as $magazin) {
            $magazin->create_date = date('d M Y', strtotime($magazin->create_date));
        }

        if ($magazine_count > 0) {
            return $this->render('index', [
                    'magazine' => $magazine,
                    'pagination' => $pagination,
                    'lang' => $lang,
                ]
            );
        } else {
            $this->redirect('https://' . DOMAIN . '/' . $lang);
        }
    }

    public function actionInfo($id)
    {

        $lang = substr(Yii::$app->language, 0, 2);

        $magazine = Magazine::find()->where(['sef' => $id, 'status' => 1])->one();
        if (!$magazine) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        $magazine_articles = MagazineArticle::find()->where(['magazine_id' => $magazine->id])->all();

        $magazine->create_date = date('d M Y', strtotime($magazine->create_date));

        return $this->render('info', [
            'magazine' => $magazine,
            'magazine_articles' => $magazine_articles,
            'lang' => $lang,
        ]);

    }
}
