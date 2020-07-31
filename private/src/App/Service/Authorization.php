<?php
namespace App\Service;

class Authorization extends \Frame\Service
{

    public function isAuthorized () : bool
    {
        return !empty($_SESSION['authorized']);
    }

    public function authorize (array $data) : bool
    {
        if (!empty($data['user']) && !empty($data['password'])) {
            if ($this->checkCredentials((string) $data['user'], (string) $data['password'])) {
                $_SESSION['authorized'] = true;
                return true;
            }
        }

        return false;
    }

    public function checkCredentials (string $user, string $password)
    {
        // very simple implementation for now, could be e.g. a db check
        return $user == 'compaan' && $password == 'kenau1';
    }

}
