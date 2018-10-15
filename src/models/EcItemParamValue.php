<?php

namespace floor12\ecommerce\models;

use Yii;

/**
 * This is the model class for table "ec_item_param_value".
 *
 * @property int $id
 * @property string $value Parameter value
 * @property string $unit Parameter unit of measure
 * @property int $param_id Parameter id
 * @property int $item_id Item id
 *
 * @property EcItem $item
 * @property EcItemParam $param
 */
class EcItemParamValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_item_param_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'param_id', 'item_id'], 'required'],
            [['param_id', 'item_id'], 'integer'],
            [['value', 'unit'], 'string', 'max' => 255],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => EcItem::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['param_id'], 'exist', 'skipOnError' => true, 'targetClass' => EcItemParam::className(), 'targetAttribute' => ['param_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.f12.ecommerce', 'ID'),
            'value' => Yii::t('app.f12.ecommerce', 'Parameter value'),
            'unit' => Yii::t('app.f12.ecommerce', 'Parameter unit of measure'),
            'param_id' => Yii::t('app.f12.ecommerce', 'Parameter id'),
            'item_id' => Yii::t('app.f12.ecommerce', 'Item id'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(EcItem::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParam()
    {
        return $this->hasOne(EcItemParam::className(), ['id' => 'param_id']);
    }

    /**
     * {@inheritdoc}
     * @return \floor12\ecommerce\models\queries\EcItemParamValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \floor12\ecommerce\models\queries\EcItemParamValueQuery(get_called_class());
    }
}
