<?php

class ControllerUserUser extends PT_Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_user_user->addUser($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/user', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_user_user->editUser($this->request->get['member_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/user', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $member_id) {
                $this->model_user_user->deleteUser($member_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/user', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getList();
    }

    protected function getList()
    {
        $this->document->addScript("view/dist/js/jquery.dataTables.min.js");
        $this->document->addScript("view/dist/js/jquery.dataTables.bootstrap.min.js");
        $this->document->addScript("view/dist/js/dataTables.buttons.min.js");
        $this->document->addScript("view/dist/js/buttons.flash.min.js");
        $this->document->addScript("view/dist/js/buttons.html5.min.js");
        $this->document->addScript("view/dist/js/buttons.print.min.js");
        $this->document->addScript("view/dist/js/buttons.colVis.min.js");
        $this->document->addScript("view/dist/js/dataTables.select.min.js");

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('user/user', 'member_token=' . $this->session->data['member_token'])
        );

        $data['add'] = $this->url->link('user/user/add', 'member_token=' . $this->session->data['member_token']);
        $data['delete'] = $this->url->link('user/user/delete', 'member_token=' . $this->session->data['member_token']);

        $data['users'] = array();

        $results = $this->model_user_user->getUsers();

        foreach ($results as $result) {
            $data['users'][] = array(
                'member_id'     => $result['member_id'],
                'name'          => $result['firstname'] . ' ' . $result['lastname'],
                'email'         => $result['email'],
                'member_group'  => $result['member_group'],
                'status'        => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'ip'            => $result['ip'],
                'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'edit'          => $this->url->link('user/user/edit', 'member_token=' . $this->session->data['member_token'] . '&member_id=' . $result['member_id'])
            );
        }

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_list', $data));
    }

    protected function getForm()
    {
        $data['text_form'] = !isset($this->request->get['member_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['email'])) {
            $data['email_err'] = $this->error['email'];
        } else {
            $data['email_err'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['firstname_err'] = $this->error['firstname'];
        } else {
            $data['firstname_err'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['lastname_err'] = $this->error['lastname'];
        } else {
            $data['lastname_err'] = '';
        }

        if (isset($this->error['password'])) {
            $data['password_err'] = $this->error['password'];
        } else {
            $data['password_err'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['confirm_err'] = $this->error['confirm'];
        } else {
            $data['confirm_err'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('user/user', 'member_token=' . $this->session->data['member_token'])
        );

        if (!isset($this->request->get['member_id'])) {
            $data['action'] = $this->url->link('user/user/add', 'member_token=' . $this->session->data['member_token']);
            $data['breadcrumbs'][] = array(
                'text'  => $this->language->get('text_add'),
                'href'  => $this->url->link('user/user/add', 'member_token=' . $this->session->data['member_token'])
            );
        } else {
            $data['action'] = $this->url->link('user/user/edit', 'member_token=' . $this->session->data['member_token'] . '&member_id=' . $this->request->get['member_id']);
            $data['breadcrumbs'][] = array(
                'text'  => $this->language->get('text_edit'),
                'href'  => $this->url->link('user/user/edit', 'member_token=' . $this->session->data['member_token'])
            );
        }

        $data['cancel'] = $this->url->link('user/user', 'member_token=' . $this->session->data['member_token']);

        if (isset($this->request->get['member_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $user_info = $this->model_user_user->getUser($this->request->get['member_id']);
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($user_info)) {
            $data['email'] = $user_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['member_group_id'])) {
            $data['member_group_id'] = $this->request->post['member_group_id'];
        } elseif (!empty($user_info)) {
            $data['member_group_id'] = $user_info['member_group_id'];
        } else {
            $data['member_group_id'] = '';
        }

        $this->load->model('user/user_group');

        $data['user_groups'] = $this->model_user_user_group->getUserGroups();

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($user_info)) {
            $data['firstname'] = $user_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($user_info)) {
            $data['lastname'] = $user_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($user_info)) {
            $data['status'] = $user_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'user/user')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $user_info = $this->model_user_user->getUserByEmail($this->request->post['email']);

        if (!isset($this->request->get['member_id'])) {
            if ($user_info) {
                $this->error['warning'] = $this->language->get('error_exists_email');
            }
        } else {
            if ($user_info && ($this->request->get['member_id'] != $user_info['member_id'])) {
                $this->error['warning'] = $this->language->get('error_exists_email');
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ($this->request->post['password'] || (!isset($this->request->get['member_id']))) {
            if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('delete', 'user/user')) {
            $this->error['warning'] = $this->language->get('error_delete');
        }

        foreach ($this->request->post['selected'] as $member_id) {
            if ($this->user->getId() == $member_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        return !$this->error;
    }
}
