<?php
namespace Frame\Db;

interface DbInterface {

    public function query($query, array $params = []) : array;

    public function insert(string $repository, array $data) : bool;

}
