<?php


namespace floor12\ecommerce\components;


use floor12\ecommerce\models\entity\Order;
use floor12\ecommerce\models\entity\Payment;
use floor12\ecommerce\models\enum\OrderStatus;
use floor12\ecommerce\models\enum\PaymentStatus;
use floor12\ecommerce\models\filters\OrderFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;


class OrderReportXls
{
    protected $models = [];

    /**
     * OrderReportXls constructor.
     * @param OrderFilter $filter
     */
    public function __construct(OrderFilter $filter)
    {
        $this->models = $filter->getArray();
    }

    public function generateXlsx()
    {

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle("Отчет по заказам на сайте");
        $sheet = $spreadsheet->getActiveSheet();

        //ширина колонок

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(50);

        //высота
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);

        //Тексты в шапку
        $sheet->setCellValue("A1", "Отчет по заказам на сайте");

        $sheet->setCellValue("A2", "ID");
        $sheet->setCellValue("B2", "Создан");
        $sheet->setCellValue("C2", "Имя");
        $sheet->setCellValue("D2", "Телефон");
        $sheet->setCellValue("E2", "Email");
        $sheet->setCellValue("F2", "Стоимость товаров");
        $sheet->setCellValue("G2", "Стоимость доставки");
        $sheet->setCellValue("H2", "Всего");
        $sheet->setCellValue("I2", "Статус");
        $sheet->setCellValue("J2", "Товары");
        $sheet->setCellValue("K2", "Данные платежа");


        //форматирование

        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FCAC4A',
                ],
            ],
        ];


        $sheet->getStyle('A2:K2')->applyFromArray($styleArray);

        $sheet->getStyle('A1')->getFont()->setSize(22)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');
        $sheet->getStyle('F2:H2')->getFill()->getStartColor()->setARGB('c4a2f0');
        $sheet->getStyle('C2:E2')->getFill()->getStartColor()->setARGB('58c965');

        // обрабатываем массив данных и добавляем их в xls
        if ($this->models)
            foreach ($this->models as $key => $order)
                $this->addRowToReport($sheet, $key, $order);


        //записываем в файл
        $writer = new Xlsx($spreadsheet);
        $filename = substr(md5(time()), 0, 10) . ".xlsx";
        $folder = \Yii::getAlias("@runtime/reports");
        if (!file_exists($folder))
            @mkdir($folder);
        $path = "{$folder}/{$filename}";
        $writer->save($path);
        return $path;
    }

    private function addRowToReport($sheet, $key, Order $order)
    {
        $cellNum = $key + 3;

        $sheet->getStyle("A{$cellNum}:K{$cellNum}")->getBorders()->getAllBorders()->setBorderStyle
        (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle("F{$cellNum}:H{$cellNum}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $parsedData = [];
        $paymentData = null;
        if ($payment = Payment::findOne(['status' => PaymentStatus::SUCCESS, 'order_id' => $order->id])) {
            $data = json_decode($payment->comment, true);
            foreach ($data as $key => $value)
                $parsedData[] = "{$key}: {$value}";
        }
        if (!empty($paymentData))
            $paymentData = implode(', ' . PHP_EOL, $parsedData);

        $itemsAsString = null;

        foreach ($order->orderItems as $orderItem) {
            $itemsAsString .= "{$orderItem->productVariation->product->article} ";
            $itemsAsString .= implode(', ', $orderItem->productVariation->parameterValues);
            $itemsAsString .= PHP_EOL;
        }

        $sheet->setCellValue("A{$cellNum}", $order->id);
        $sheet->setCellValue("B{$cellNum}", Yii::$app->formatter->asDateTime($order->created));
        $sheet->setCellValue("C{$cellNum}", $order->fullname);
        $sheet->setCellValue("D{$cellNum}", $order->phone);
        $sheet->setCellValue("E{$cellNum}", $order->email);
        $sheet->setCellValue("F{$cellNum}", $order->products_cost);
        $sheet->setCellValue("G{$cellNum}", $order->delivery_cost);
        $sheet->setCellValue("H{$cellNum}", $order->total);
        $sheet->setCellValue("I{$cellNum}", OrderStatus::getLabel($order->status));
        $sheet->setCellValue("J{$cellNum}", $itemsAsString);
        $sheet->setCellValue("K{$cellNum}", $paymentData);


//        $sheet->getStyle("C{$cellNum}")->getAlignment()->setHorizontal('left');
//        $sheet->getStyle("D{$cellNum}")->getAlignment()->setHorizontal('center');
//        $sheet->getStyle("F{$cellNum}")->getAlignment()->setHorizontal('center');

    }
}
