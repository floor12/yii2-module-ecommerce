<?php

use yii\db\Migration;

/**
 * Class m191102_195630_add_sort_to_category
 */
class m191102_195630_add_sort_to_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ec_category}}', 'sort', $this
            ->integer()
            ->notNull()
            ->defaultValue(0));

        $this->createIndex('ec_category-sort', '{{%ec_category}}', 'sort');

        $models = \floor12\ecommerce\models\Category::find()->all();
        if ($models) foreach ($models as $key => $model) {
            $model->sort = ++$key;
            $model->save(false, ['sort']);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ec_category}}', 'sort');
    }

}
