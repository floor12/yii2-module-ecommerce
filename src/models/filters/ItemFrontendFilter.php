<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 21:32
 */

namespace floor12\ecommerce\models\filters;


use app\components\Pagination;
use floor12\ecommerce\models\EcCategory;
use floor12\ecommerce\models\EcItem;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ItemFrontendFilter extends Model
{
    public $category_id;
    public $category_title;

    private $_category;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->category_id) {
            $this->_category = EcCategory::findOne((int)$this->category_id);
            $this->category_title = $this->_category->title;
        } else {
            $this->category_title = Yii::t('app.f12.ecommerce', 'Catalog');
        }
        parent::init();
    }

    /**
     * @return EcCategory|null
     */
    public function getCategory()
    {
        return $this->_category;
    }


    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $query = EcItem::find();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'class' => Pagination::class,
                'route' => parse_url(Yii::$app->request->url, PHP_URL_PATH),
                'defaultPageSize' => Yii::$app->getModule('shop')->itemPerPage
            ],
        ]);
    }
}