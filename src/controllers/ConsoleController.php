<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29/11/2018
 * Time: 20:26
 */

namespace floor12\ecommerce\controllers;


use floor12\ecommerce\models\Item;
use SimpleXMLElement;
use yii\console\Controller;
use yii\helpers\Console;

class ConsoleController extends Controller
{
    public function actionImport($filename)
    {
        if (!file_exists($filename)) {
            $this->stdout("File not found.\n", Console::FG_RED);
            return 1;
        }

        $items = new SimpleXMLElement(file_get_contents($filename));


        foreach ($items as $xmlItem) {
            $this->stdout("\n\nItem: {$xmlItem->name}");
            $item = Item::findOne(['external_id' => $xmlItem->id]);
            if (!$item) {
                $this->stdout("\nItem not found in shop DB.", Console::FG_YELLOW);
                continue;
            }

            $item->available = str_replace(' ', '', $xmlItem->available);
            $this->stdout("\nItem found!", Console::FG_GREEN);
            $this->stdout("\nAvailable: {$item->available}");



            if ($item->save(true, ['available']))
                $this->stdout("\nItem saved", Console::FG_GREEN);


        }
        $this->stdout("\n\nGoodbuy.\n", Console::FG_GREEN);

    }
}