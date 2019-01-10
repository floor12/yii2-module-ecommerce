<?php

namespace floor12\ecommerce\models;

use floor12\ecommerce\models\queries\CityQuery;
use Yii;

/**
 * This is the model class for table "ec_city".
 *
 * @property int $id
 * @property string $name City name
 * @property string $fullname City name with region
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fullname'], 'required'],
            [['name', 'fullname'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'name' => Yii::t('app.f12.ecommerce', 'City name'),
            'fullname' => Yii::t('app.f12.ecommerce', 'City name with region'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return EcCityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CityQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->fullname;
    }
}
