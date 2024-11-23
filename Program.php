<?php
declare(strict_types=1);

//==============Не редактировать
final class DataBase
{
    private bool $isConnected = false;

    public function connect(): bool
    {
        sleep(1);
        $this->isConnected = true;
        return 'connected';
    }

    public function random()
    {
        $this->isConnected = rand(0, 3) ? $this->isConnected : false;
    }

    public function fetch($id): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(100000);
        return 'fetched - ' . $id;
    }

    public function insert($data): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(900000);
        return 'inserted - ' . $data;
    }


    public function batchInsert($data): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(900000);
        return 'batch inserted';
    }
}
//==============

class DataBaseHelper
{
    public function __construct(private readonly DataBase $db)
    {
        $this->initConnection();
    }

    public function fetch(int $id): string
    {
        return $this->retry(fn () => $this->db->fetch($id));
    }

    public function insert(int $data): string
    {
        return $this->retry(fn () => $this->db->insert($data));
    }

    private function initConnection(): void
    {
        try {
            $this->db->connect();
        } catch (Throwable) {
        }
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    private function retry(callable $callback)
    {
        do {
            try {
                return $callback();
            } catch (Throwable $e) {
                if ($e instanceof Exception && 'No connection' === $e->getMessage()) {
                    $this->initConnection();
                }
            }
        } while (true);
    }
}

function step1(array $ids): void
{
    $helper = new DataBaseHelper(new DataBase());
    foreach ($ids as $id) {
        print($helper->fetch($id));
        print(PHP_EOL);
    }
}

function step2(array $datas): void
{
    $helper = new DataBaseHelper(new DataBase());
    foreach ($datas as $data) {
        print($helper->insert($data));
        print(PHP_EOL);
    }
}

//==============Не редактировать
$dataToFetch = [1, 2, 3, 4, 5, 6];
$dataToInsert = [7, 8, 9, 10, 11, 12];

step1($dataToFetch);
step2($dataToInsert);
print("Success");
//==============