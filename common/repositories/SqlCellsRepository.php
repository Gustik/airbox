<?php
namespace common\repositories;

use common\entities\Cell;
use common\entities\Id;
use yii\db\Connection;
use yii\db\Query;

class SqlCellsRepository implements CellRepository
{
    private Connection $db;
    private Hydrator $hydrator;

    public function __construct(Connection $db, Hydrator $hydrator)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
    }

    /**
     * @param Id $id
     * @return Cell|object
     * @throws \Exception
     */
    public function get(Id $id): Cell
    {
        $cell = (new Query())->select('*')
            ->from('{{%cell}}')
            ->andWhere(['id' => $id->getId()])
            ->one($this->db);

        if (!$cell) {
            throw new NotFoundException('Cell not found.');
        }

        return $this->hydrator->hydrate(Cell::class, [
            'id' => new Id($cell['id']),
            'name' => $cell['name'],
            'address' => $cell['address'],
            'status' => $cell['status'],
            'baggageId' => $cell['baggageId'] ? new Id($cell['baggageId']) : null,
            'startDate' => $cell['startDate'] ? new \DateTimeImmutable($cell['startDate']) : null,
            'daysCount' => $cell['daysCount'],
            'price' => $cell['price'],
            'pinCode' => $cell['pinCode']
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function all(): array
    {
        $cells = (new Query())->select('*')->from('{{%cell}}')->orderBy('name')->all($this->db);
        $arrCells = [];
        foreach ($cells as $cell) {
            $arrCells[] = $this->hydrator->hydrate(Cell::class, [
                'id' => new Id($cell['id']),
                'name' => $cell['name'],
                'address' => $cell['address'],
                'status' => $cell['status'],
                'baggageId' => $cell['baggageId'] ? new Id($cell['baggageId']) : null,
                'startDate' => $cell['startDate'] ? new \DateTimeImmutable($cell['startDate']) : null,
                'daysCount' => $cell['daysCount'],
                'price' => $cell['price'],
                'pinCode' => $cell['pinCode']
            ]);
        }

        return $arrCells;
    }

    /**
     * @param Cell $cell
     * @throws \Throwable
     */
    public function add(Cell $cell): void
    {
        $this->db->transaction(function () use ($cell) {
            $this->db->createCommand()
                ->insert('{{%cell}}', self::extractCellData($cell))
                ->execute();
        });
    }

    /**
     * @param Cell $cell
     * @throws \Throwable
     */
    public function save(Cell $cell): void
    {
        $this->db->transaction(function () use ($cell) {
            $this->db->createCommand()
                ->update(
                    '{{%cell}}',
                    self::extractCellData($cell),
                    ['id' => $cell->getId()->getId()]
                )->execute();
        });
    }

    /**
     * @param Cell $cell
     * @throws \yii\db\Exception
     */
    public function remove(Cell $cell): void
    {
        $this->db->createCommand()
            ->delete('{{%cell}}', ['id' => $cell->getId()->getId()])
            ->execute();
    }

    /**
     * @param Cell $cell
     * @return array
     */
    private static function extractCellData(Cell $cell)
    {

        return [
            'id' => $cell->getId()->getId(),
            'name' => $cell->getName(),
            'address' => $cell->getAddress(),
            'status' => $cell->getStatus(),
            'baggageId' => $cell->getBaggageId() ? $cell->getBaggageId()->getId() : null,
            'startDate' => $cell->getStartDate() ? $cell->getStartDate()->format('Y-m-d H:i:s') : null,
            'daysCount' => $cell->getDaysCount(),
            'price' => $cell->getPrice(),
            'pinCode' => $cell->getPinCode(),
        ];
    }
}