<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2018-12-19
 * Time: 11:07
 */

namespace floor12\ecommerce\logic;

use floor12\ecommerce\models\entity\Category;
use Yii;

class CategoryPathBuilder
{
    public function execute()
    {
        $categories = Category::find()->orderBy('title')->all();
        if ($categories)
            foreach ($categories as $category) {
                $category->path = $category->title;
                if ($category->parent)
                    $category->path = $category->parent->path . ' / ' . $category->title;
                Yii::$app
                    ->db
                    ->createCommand()
                    ->update(Category::tableName(), ['path' => $category->path], ['id' => $category->id])->execute();
            }

    }
}