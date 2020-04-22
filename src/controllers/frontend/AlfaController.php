<?php


namespace floor12\ecommerce\controllers\frontend;


use app\logic\invoice\InvoiceCheck;
use app\modules\balance\models\Invoice;
use floor12\ecommerce\logic\AlfaPaymentCheck;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\entity\Payment;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class AlfaController extends Controller
{
    /**
     * @return string
     */
    public function actionSuccess($orderId = null)
    {
        if ($orderId) {
            $model = Payment::findOne(['external_id' => $orderId]);
            if (!$model)
                throw new BadRequestHttpException('Счет не найден.');

            $checker = new AlfaPaymentCheck($model);
            $checker->execute();
        }

        return $this->render('success');
    }

    /**
     * @return string
     */
    public function actionFailure($orderId = null)
    {
        $payLink = null;
        if ($orderId) {
            $model = Payment::findOne(['external_id' => $orderId]);
            if (!$model)
                throw new BadRequestHttpException('Счет не найден.');
            $model->status = PaymentStatus::ERROR;
            $model->save();
            $payLink = Url::toRoute(['shop/frontend/cart/pay', 'order_id' => $model->order_id]);
        }

        return $this->render('failure', ['payLink' => $payLink]);
    }

}
