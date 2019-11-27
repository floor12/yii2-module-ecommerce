<?php


namespace floor12\ecommerce\logic\exchange;


use floor12\ecommerce\models\entity\Category;
use floor12\ecommerce\models\enum\Status;
use yii\base\ErrorException;
use yii\base\InvalidParamException;

class CategoryCreator
{
    /**
     * @var string
     */
    protected $categoryName;
    /**
     * @var string
     */
    protected $categoryUID;
    /**
     * @var Category
     */
    protected $category;

    /**
     * CategoryCreator constructor.
     * @param string $categoryName
     */
    public function __construct(string $categoryName, string $categoryUID = null)
    {
        $this->categoryName = $categoryName;
        if (empty($this->categoryName))
            throw new InvalidParamException('Category name is empty.');
        $this->categoryUID = $categoryUID;
    }

    /**
     * @throws ErrorException
     */
    public function getCategoryId()
    {
        $this->getCategory();
        return $this->category->id;
    }

    /**
     * @return Category
     * @throws ErrorException
     */
    public function getCategory()
    {
        if (empty($this->category))
            $this->findOrCreateCategory();
        return $this->category;
    }

    /**
     * @return Category
     * @throws ErrorException
     */
    protected function findOrCreateCategory()
    {
        $this->category = Category::find()
            ->where(['title' => $this->categoryName])
            ->orFilterWhere(['external_id' => $this->categoryUID])
            ->one();

        if (is_object($this->category))
            return $this->category;

        $this->category = new Category([
            'external_id' => $this->categoryUID,
            'title' => $this->categoryName,
            'status' => Status::ACTIVE,
            'sort' => Category::find()->max('sort') + 1
        ]);

        var_dump($this->category->sort);

        if (!$this->category->save())
            throw new ErrorException('Error category saving: ' . print_r($this->category->getFirstErrors(), 1));

        return $this->category;
    }

}