<?php

namespace backend\controllers;

use common\entities\Cell;
use common\entities\Id;
use common\services\CellService;
use common\services\dto\CreateCellDto;

class CellController extends \yii\web\Controller
{
    private CellService $cellService;

    public function __construct($id, $module, CellService $cellService, $config = [])
    {
        $this->cellService = $cellService;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        return $this->render('index', [ 'cells' => $this->cellService->cellListDto()]);
    }

    public function actionCreate()
    {
        $errors = [];
        $dto = new CreateCellDto();
        if(\Yii::$app->request->post() && $dto->load(\Yii::$app->request->post()) ) {
            try {
                $cell = $this->cellService->createCell($dto);
                $this->redirect('index');
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        return $this->render('create', [ 'errors' => $errors]);
    }

    public function actionView($id)
    {
        $cell = $this->cellService->getCell(new Id($id));
        $baggage = null;

        if($cell->isBaggageLoaded()) {
            $baggage = $this->cellService->getBaggage($cell->getBaggageId());
        }

        return $this->render('view', [
            'cell' => $cell->dto(),
            'baggage' => $baggage,
        ]);
    }

    public function actionDelete($id)
    {
        $this->cellService->deleteCell(new Id($id));

        return $this->redirect(['index']);
    }
}
