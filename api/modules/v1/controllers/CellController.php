<?php


namespace api\modules\v1\controllers;


use Assert\AssertionFailedException;
use common\entities\Cabinet;
use common\entities\Cell;
use common\entities\Id;
use common\repositories\MemoryCellsRepository;
use common\services\CellService;
use common\services\dto\CreateCellDto;
use common\services\dto\CreateClientDto;
use DateTimeImmutable;

class CellController extends \yii\rest\Controller
{
    private CellService $cellService;

    public function __construct($id, $module, CellService $cellService, $config = [])
    {
        $this->cellService = $cellService;
        parent::__construct($id, $module, $config);
    }

    function actionLoad()
    {
        $createCellDto = new CreateCellDto();
        $createCellDto->cellName = "#1";
        $createCellDto->cellAddress = "1";
        $cell = $this->cellService->createCell($createCellDto);

        $clientDto = new CreateClientDto();
        $clientDto->phoneCountry = 7;
        $clientDto->phoneCode = '920';
        $clientDto->phoneNumber = '00000001';


        $cell = $this->cellService->loadBaggage($clientDto, $cell->getId(), $startDate = new DateTimeImmutable, $daysCount = 42);


        return ['result' => $cell->getPinCode()];
    }
}