<?php

use yii\widgets\LinkPager;

$this->title = $magazine->title;
$this->params['breadcrumbs'][] = ['label' => \common\models\ex\T::get('magazine'), 'url' => '/' . Yii::$app->language . '/magazine'];
$this->params['breadcrumbs'][] = ['label' => ' ' . $magazine->title];
?>

    <!-- Content -->
    <div class="container mz-content">
        <div class="col-md-2">
            <div class="mz-date"><?= $magazine->create_date ?></div>
        </div>
        <div class="col-md-8">
            <div class="mz-title text-center">
                <h1><?= $magazine->title ?></h1>
            </div>
            <div class="mz-desc">
                <?php $metaDesc = '';
                foreach ($magazine_articles as $magazine_article) { ?>
                    <div class="article">
                        <?php $metaDesc = html_entity_decode($magazine_article->descr); ?>
                        <?= $metaDesc; ?>

                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php
$metaDesc = substr($metaDesc, 0, 130) . "...";
\Yii::$app->view->params['metaDescriptionMagazine'] = [
    'id' => $magazine->id,
    'img' => \common\models\ex\Image::get($magazine->filePath('image'), 290, 200, ['crop']),
    'title' => $magazine->title,
    'desc' => $metaDesc

];
?>