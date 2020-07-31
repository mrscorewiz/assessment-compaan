<?php
    namespace Frame\Db;

    class QueryException extends \Exception
    {
        private $errorInfo = [];

        public function __construct($message, $errorInfo)
        {
            parent::__construct($message);
            $this->errorInfo = $errorInfo;
        }

        public function getErrorInfo()
        {
            return $this->errorInfo;
        }

    }


