<?php


namespace api\modules\v1\controllers;


use common\entities\Cell;
use common\entities\Id;
use common\services\CellService;
use common\services\dto\CreateCellDto;
use DateTimeImmutable;

class CellController extends \yii\rest\Controller
{
    private CellService $cellService;

    public function __construct($id, $module, CellService $cellService, $config = [])
    {
        $this->cellService = $cellService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                ]
            ],
        ];
    }

    private function createCell($name, $address): Cell
    {
        $createCellDto = new CreateCellDto();
        $createCellDto->cellName = $name;
        $createCellDto->cellAddress = $address;
        $createCellDto->daysCount = 2;
        $createCellDto->price = 100;

        return $this->cellService->createCell($createCellDto);
    }

    /**
     * @param $cellId
     *
     * Зарезервировать ячейку
     * @return array
     */
    function actionReserve($cellId)
    {
        $cell = $this->cellService->getCell(new Id($cellId));
        $this->cellService->reserveCell($cell);

        return ['status' => 200];
    }

    /**
     * @param $cellId
     * @param $phone
     * @param $days
     * @return array
     *
     * Загрузить багаж в ячейку
     */
    function actionLoad($cellId, $phone, $days)
    {
        try {
            $cell = $this->cellService->loadBaggage($phone, new Id($cellId), new DateTimeImmutable, $days);
        } catch (\Exception $e) {
            return ['status'=>500, 'error' => $e->getMessage()];
        }

        return ['status' => 200];
    }

    /**
     * @return array
     *
     * Список всех ячеек
     */
    public function actionList()
    {
        return [ 'cells' => $this->cellService->cellListDto()];
    }
}