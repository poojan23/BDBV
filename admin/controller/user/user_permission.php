<?php

class ControllerUserUserPermission extends PT_Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_user_user_group->addUserGroup($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/user_permission', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_user_user_group->editUserGroup($this->request->get['member_group_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/user_permission', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user_group');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $member_group_id) {
                $this->model_user_user_group->deleteUserGroup($member_group_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/user_permission', 'member_token=' . $this->session->data['member_token']));
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
            'href'  => $this->url->link('user/user_permission', 'member_token=' . $this->session->data['member_token'])
        );

        $data['add'] = $this->url->link('user/user_permission/add', 'member_token=' . $this->session->data['member_token']);
        $data['delete'] = $this->url->link('user/user_permission/delete', 'member_token=' . $this->session->data['member_token']);

        $data['user_groups'] = array();

        $results = $this->model_user_user_group->getUserGroups();

        foreach ($results as $result) {
            $data['user_groups'][] = array(
                'member_group_id'   => $result['member_group_id'],
                'name'              => $result['name'],
                'edit'              => $this->url->link('user/user_permission/edit', 'member_token=' . $this->session->data['member_token'] . '&member_group_id=' . $result['member_group_id'])
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

        $this->response->setOutput($this->load->view('user/user_group_list', $data));
    }

    protected function getForm()
    {
        $data['text_form'] = !isset($this->request->get['member_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['name'])) {
            $data['name_err'] = $this->error['name'];
        } else {
            $data['name_err'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('user/user_permission', 'member_token=' . $this->session->data['member_token'])
        );

        if (!isset($this->request->get['member_group_id'])) {
            $data['action'] = $this->url->link('user/user_permission/add', 'member_token=' . $this->session->data['member_token']);
            $data['breadcrumbs'][] = array(
                'text'  => $this->language->get('text_add'),
                'href'  => $this->url->link('user/user_permission/add', 'member_token=' . $this->session->data['member_token'])
            );
        } else {
            $data['action'] = $this->url->link('user/user_permission/edit', 'member_token=' . $this->session->data['member_token'] . '&member_group_id=' . $this->request->get['member_group_id']);
            $data['breadcrumbs'][] = array(
                'text'  => $this->language->get('text_edit'),
                'href'  => $this->url->link('user/user_permission/edit', 'member_token=' . $this->session->data['member_token'])
            );
        }

        $data['cancel'] = $this->url->link('user/user_permission', 'member_token=' . $this->session->data['member_token']);

        if (isset($this->request->get['member_group_id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {
            $user_group_info = $this->model_user_user_group->getUserGroup($this->request->get['member_group_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($user_group_info)) {
            $data['name'] = $user_group_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($user_group_info)) {
            $data['description'] = $user_group_info['description'];
        } else {
            $data['description'] = '';
        }

        $ignore = array(
            'common/dashboard',
            'common/startup',
            'user/login',
            'user/logout',
            'user/forgotten',
            'user/reset',
            'common/footer',
            'common/header',
            'error/not_found',
            'error/permission'
        );

        $data['permissions'] = array();

        $files = array();

        # Make path into an array
        $path = array(DIR_APPLICATION . 'controller/*');

        # While the path array is still populated keep looping through
        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                # If directory add to path array
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                # Add the file to the files to be deleted array
                if (is_file($file)) {
                    $files[] = $file;
                }
            }
        }

        # Sort the file array
        sort($files);

        foreach ($files as $file) {
            $controller = substr($file, strlen(DIR_APPLICATION . 'controller/'));

            $permission = substr($controller, 0, strrpos($controller, '.'));

            if (!in_array($permission, $ignore)) {
                $data['permissions'][] = $permission;
            }
        }

        if (isset($this->request->post['permission']['access'])) {
            $data['access'] = $this->request->post['permission']['access'];
        } elseif (isset($user_group_info['permission']['access'])) {
            $data['access'] = $user_group_info['permission']['access'];
        } else {
            $data['access'] = array();
        }

        if (isset($this->request->post['permission']['modify'])) {
            $data['modify'] = $this->request->post['permission']['modify'];
        } elseif (isset($user_group_info['permission']['modify'])) {
            $data['modify'] = $user_group_info['permission']['modify'];
        } else {
            $data['modify'] = array();
        }

        if (isset($this->request->post['permission']['delete'])) {
            $data['delete'] = $this->request->post['permission']['delete'];
        } elseif (isset($user_group_info['permission']['delete'])) {
            $data['delete'] = $user_group_info['permission']['delete'];
        } else {
            $data['delete'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_group_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'user/user_permission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('delete', 'user/user_permission')) {
            $this->error['warning'] = $this->language->get('error_delete');
        }

        $this->load->model('user/user');

        foreach ($this->request->post['selected'] as $member_group_id) {
            $user_total = $this->model_user_user->getTotalUsersByGroupId($member_group_id);

            if ($user_total) {
                $this->error['warning'] = sprintf($this->language->get('error_user'), $user_total);
            }
        }

        return !$this->error;
    }
}
