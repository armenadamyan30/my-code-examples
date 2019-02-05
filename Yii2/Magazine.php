<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "press".
 *
 * @property string $id
 * @property string $title
 * @property string $sef
 * @property string $image
 * @property string $descr
 * @property string $create_date
 * @property integer $status
 */
class Magazine extends \common\models\base\MagazineBase
{
    public static function MLattributes()
    {
        return ['title', 'descr'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'magazine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules = ArrayHelper::merge([
            [['descr'], 'string'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['sef', 'image'], 'string', 'max' => 32]
        ], $rules);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'sef' => 'Sef',
            'image' => 'Image',
            'descr' => 'Descr',
            'create_date' => 'Create date',
            'status' => 'Status',
        ];
    }


}
