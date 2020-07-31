<?php
namespace App\Controller;

class Main extends \Frame\Controller {

    public function form() {

        $handler = $this->srv->formHandler;

        if (count($_POST)) {
            $data = (object) $_POST;

            $errors = $handler->getErrors($data);

            if (!count($errors)) {
                if ($handler->save($data)) {
                    return $this->renderThanks();
                }
                $errors []= 'Ongeldige actie';
            }
        }

        $data = $data ?? new \stdClass;
        $data->csrf = $data->csrf ?? $handler->getCsrfToken();
        $errors = $errors ?? [];

        $this->view->data = $data;
        $this->view->errors = $errors;

        return $this->renderForm();
    }

    public function results() {
        if (!$this->srv->authorization->isAuthorized()) {
            header('Location:login?redirect=results');
            exit;
        }

        $this->view->data = $this->srv->formHandler->getResults();

        return $this->view->render('results');
    }

    private function renderForm() {
        return $this->view->render('form');
    }

    private function renderThanks() {
        return $this->view->render('thanks');
    }

}
