<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13/10/2018
 * Time: 09:49
 */


namespace floor12\ecommerce\models\filters;

use floor12\ecommerce\models\Item;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\BadRequestHttpException;

/**
 * Class OrderFilter
 * @package floor12\ecommerce\models\filters
 * @property string $filter
 * @property integer $status
 * @property EcItemQuery $_query
 */
class ItemFilter extends Model
{
    public $filter;
    public $status;
    public $hideOptions = 0;

    private $_query;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['status', 'hideOptions'], 'integer'],
            [['filter'], 'string'],
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        if (!$this->validate())
            throw new BadRequestHttpException('Search model validation error.');

        $expression = new Expression("CASE WHEN parent_id=0 THEN id ELSE parent_id END AS sort");

        $this->_query = Item::find()->with('categories')
            ->addSelect(["*", $expression])
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['OR', ['LIKE', 'title', $this->filter], ['LIKE', 'article', $this->filter]]);

        if ($this->hideOptions)
            $this->_query->root();

        $dataProvider = new ActiveDataProvider([
            'query' => $this->_query
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id' => [
                    'asc' => ['sort' => SORT_ASC],
                    'desc' => ['sort' => SORT_DESC],
                    'default' => SORT_ASC
                ],
            ],
            'defaultOrder' => [
                'id' => SORT_ASC
            ]
        ]);

        return $dataProvider;
    }

    /**@inheritdoc
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'hideOptions' => Yii::t('app.f12.ecommerce', 'hide options')
        ];
    }


}