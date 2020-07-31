<?php
namespace App\Controller;

class Auth extends \Frame\Controller {

    public function login(array $params) {
        if (count($_POST)) {
            if ($this->srv->authorization->authorize($_POST)) {
                header('Location:' . $params['redirect']);
                exit;
            }

            $this->view->errors = ['Ongeldige combinatie gebruikersnaam/wachtwoord'];
        }

        return $this->view->render('login');
    }

}
