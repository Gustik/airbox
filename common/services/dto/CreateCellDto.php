<?php


namespace common\services\dto;


use yii\web\Request;

class CreateCellDto
{
    public string $cellId;
    public string $cellName;
    public string $cellAddress;
    public int $status;
    public bool $busy;
    public int $price;
    public int $daysCount;

    public function load(array $data): bool
    {
        $this->cellName = $data['cellName'];
        $this->cellAddress = $data['cellAddress'];
        $this->price = (int)$data['price'];

        return true;
    }

    public function setAttributes($values): bool
    {
        if (is_array($values)) {
            echo "<pre>";
            var_dump($values);
            die("========");
            foreach ($values as $name => $value) {
                echo $name + "<br>";
                if(isset($this->$name)) {
                    $this->$name = $value;
                }
            }
           // die("============");

            return true;
        }
        return false;
    }
}