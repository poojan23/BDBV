<?php

class ControllerUserLogout extends PT_Controller
{
    public function index()
    {
        $this->user->logout();

        unset($this->session->data['member_token']);

        $this->response->redirect($this->url->link('user/login'));
    }
}
