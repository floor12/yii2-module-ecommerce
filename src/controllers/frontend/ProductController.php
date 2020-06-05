<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 17/10/2018
 * Time: 16:52
 */

namespace floor12\ecommerce\controllers\frontend;


use floor12\ecommerce\components\ParameterSelectorWidget;
use floor12\ecommerce\models\entity\Product;
use floor12\ecommerce\models\enum\Status;
use floor12\ecommerce\models\filters\FrontendProductFilter;
use floor12\ecommerce\models\filters\ProductFrontendFilter;
use floor12\ecommerce\models\forms\ProductSelectorForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class ProductController extends Controller
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = Yii::$app->getModule('shop')->layout;
    }

    /**
     * @param $page
     * @param int $category_id
     * @param int $sale
     * @return string
     */
    public function actionIndex($page, $category_id = 0, $sale = 0)
    {
        $model = new FrontendProductFilter();
        $model->load(Yii::$app->request->get());
        $model->prepare();
        $elements = [];
        Yii::$app->response->headers->add('Cache-Control', 'no-cache, no-store, must-revalidate');
        Yii::$app->response->headers->add('Pragma', 'no-cache');
        Yii::$app->response->headers->add('Expires', 0);
        Yii::$app->response->headers->add('Total-products', $model->count());
        if (Yii::$app->request->isAjax) {
            foreach ($model->getProducts() as $product)
                $elements[] = $this->renderPartial(Yii::$app->getModule('shop')->viewIndexListItem, ['model' => $product]);
            return implode(PHP_EOL, $elements);
        }

        if ($model->offset) {
            $model->limit += $model->offset;
            $model->offset = 0;
        }
        return $this->render(Yii::$app->getModule('shop')->viewIndex, ['model' => $model]);
    }


    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {

        $model = Product::findOne($id);

        if (!$model)
            throw new NotFoundHttpException('Product is not found.');

        if ($model->status == Status::DISABLED && !Yii::$app->getModule('shop')->adminMode())
            throw new ForbiddenHttpException('Product is disabled.');

        if (Yii::$app->getModule('shop')->registerGoogleTagEvents) {
            $productJson = json_encode([
                'id' => $model->id,
                'name' => $model->title,
                'price' => $model->price,
                'category' => $model->categories ? $model->categories[0]->title : NULL,
            ]);

            $this->getView()->registerJs("f12Tag.productView([{$productJson}])");
        }

        $seoTitle = $model->seo_title ?: str_replace('{title}', $model->title, Yii::$app->getModule('shop')->productPageTitleTemplate);

        Yii::$app->metamaster
            ->setTitle(strval($seoTitle))
            ->setType('product')
            ->setDescription(strval($model->seo_description));

        if (isset($model->images[0]))
            Yii::$app->metamaster->image = $model->images[0]->href;

        Yii::$app->metamaster->register(Yii::$app->getView());

        $productSelectorForm = new ProductSelectorForm();
        $productSelectorForm->load(Yii::$app->request->get());

        return $this->render(Yii::$app->getModule('shop')->viewItem, [
            'productSelectorForm' => $productSelectorForm,
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionParameterSelectorWidget($id)
    {
        $product = Product::findOne((int)$id);
        if (!$product)
            throw new NotFoundHttpException();
        $form = new ProductSelectorForm();
        $form->load(Yii::$app->request->get());
        $widget = new ParameterSelectorWidget([
            'model' => $form,
            'product' => $product
        ]);
        return $widget->run();

    }

}
