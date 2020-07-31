<?php
namespace App\Service;

class FormHandler extends \Frame\Service {

    private $db;
    private $salt = 'very.S41ty:5alt!';
    private $csrfTable = 'compaan_csrf';
    private $resultsTable = 'compaan_results';

    public function __construct(\Frame\Db\DbInterface $db) {
        $this->db = $db;
    }

    public function save(\stdClass $data) : bool
    {
        $ok = (bool) ($data->csrf && count($this->db->query('SELECT * FROM `' . $this->csrfTable . '` WHERE `token` = ?', [$data->csrf])));

        if ($ok) {
            $this->db->insert($this->resultsTable, [
                'mark' => (int) $data->mark,
                'comment' => $data->comment ?? '',
                'datetime' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'],
            ]);
        }

        return $ok;
    }

    public function getResults() : array
    {
        return $this->db->query('SELECT * FROM `' . $this->resultsTable . '` ORDER BY datetime DESC');
    }

    public function getErrors(\stdClass $data) : array
    {
        $errors = [];

        if (empty($data->mark)) {
            $errors []= 'Er is nog geen rapportcijfer gegeven.';
        } else {
            $mark = (int) $data->mark;

            if ($mark < 1 || $mark > 10) {
                $errors []= 'Er is ongeldige input ontvangen.';
            }
        }

        return $errors;
    }

    public function getCsrfToken() : string
    {
        $token = \md5($this->salt . microtime());
        $this->db->insert($this->csrfTable, ['token' => $token]);
        return $token;
    }

}
