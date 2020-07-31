<?php
    namespace Frame\Db;

    class Mysql extends \Frame\Service implements DbInterface {

        public function __construct($cfg)
        {
            $this->pdo = new \PDO('mysql:host=' . $cfg->host . ';dbname=' . $cfg->name, $cfg->user, $cfg->pass);
        }

        public function query($query, array $params = []) : array
        {
            if ($query) {
                $stmt = $this->pdo->prepare($query);

                if (!$stmt->execute($params)) {
                    throw new QueryException('Query failed', $this->pdo->errorInfo());
                }

                $rows = [];
                $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

                foreach ($result as $row) {
                     $rows []= $row;
                }

                return $rows;
            } else {
                throw new \Exception('No query');
            }
        }

        public function insert(string $repository, array $data) : bool
        {
            $qm = [];
            $cols = [];
            $params = [];

            foreach ($data as $col => $value) {
                if (is_scalar($value)) {
                    $qm []= '?';
                    $cols []= '`' . $col . '`';
                    $params []= $value;
                }
            }

            $query = 'INSERT INTO `' . $repository . '` (' . join(',', $cols) . ') VALUES (' . join(',', $qm) . ')';

            try {
                $this->query($query, $params);
            } catch (\Exception $e) {
                return false;
            }


            return true;
        }

    }


