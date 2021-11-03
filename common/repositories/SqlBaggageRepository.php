<?php
namespace common\repositories;

use common\entities\Baggage;
use common\entities\Cell;
use common\entities\Id;
use yii\db\Connection;
use yii\db\Query;

class SqlBaggageRepository implements BaggageRepository
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
     * @return Baggage|object
     * @throws \Exception
     */
    public function get(Id $id): Baggage
    {
        $baggage = (new Query())->select('*')
            ->from('{{%baggage}}')
            ->andWhere(['id' => $id->getId()])
            ->one($this->db);

        if (!$baggage) {
            throw new NotFoundException('Baggage not found.');
        }

        return $this->hydrateBaggageData($baggage);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function all(): array
    {
        $baggageList = (new Query())->select('*')->from('{{%baggage}}')->all($this->db);
        $arrBaggage = [];
        foreach ($baggageList as $cell) {
            $arrBaggage[] = $this->hydrateBaggageData($cell);
        }

        return $arrBaggage;
    }

    /**
     * @param Baggage $baggage
     * @throws \Throwable
     */
    public function add(Baggage $baggage): void
    {
        $this->db->transaction(function () use ($baggage) {
            $this->db->createCommand()
                ->insert('{{%baggage}}', self::extractBaggageData($baggage))
                ->execute();
        });
    }

    /**
     * @param Baggage $baggage
     * @throws \Throwable
     */
    public function save(Baggage $baggage): void
    {
        $this->db->transaction(function () use ($baggage) {
            $this->db->createCommand()
                ->update(
                    '{{%baggage}}',
                    self::extractBaggageData($baggage),
                    ['id' => $baggage->getId()->getId()]
                )->execute();
        });
    }

    /**
     * @param Baggage $baggage
     * @throws \yii\db\Exception
     */
    public function remove(Baggage $baggage): void
    {
        $this->db->createCommand()
            ->delete('{{%baggage}}', ['id' => $baggage->getId()->getId()])
            ->execute();
    }

    /**
     * @param Baggage $baggage
     * @return array
     */
    private static function extractBaggageData(Baggage $baggage)
    {

        return [
            'id' => $baggage->getId()->getId(),
            'phone' => $baggage->getPhone(),
            'status' => $baggage->getStatus(),
            'date' => $baggage->getDate() ? $baggage->getDate()->format('Y-m-d H:i:s') : null,
        ];
    }

    /**
     * @param $baggage
     * @return Baggage|object
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function hydrateBaggageData($baggage): Baggage
    {
        return $this->hydrator->hydrate(Baggage::class, [
            'id' => new Id($baggage['id']),
            'phone' => $baggage['phone'],
            'status' => $baggage['status'],
            'date' => $baggage['date'] ? new \DateTimeImmutable($baggage['date']) : null,
        ]);
    }
}