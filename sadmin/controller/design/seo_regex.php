<?php

class ControllerDesignSeoRegex extends PT_Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('design/seo_regex');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_regex');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('design/seo_regex');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_regex');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_design_seo_regex->addSeoRegex($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token']));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('design/seo_regex');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_regex');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_seo_regex->editSeoRegex($this->request->get['seo_regex_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token']));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('design/seo_regex');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_regex');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $seo_regex_id) {
                $this->model_design_seo_regex->deleteSeoRegex($seo_regex_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token']));
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
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token'])
        );

        $data['add'] = $this->url->link('design/seo_regex/add', 'user_token=' . $this->session->data['user_token']);
        $data['delete'] = $this->url->link('design/seo_regex/delete', 'user_token=' . $this->session->data['user_token']);

        $data['seo_regexes'] = array();

        $filter_data = array(
            'sort'  => 'query',
            'order' => 'ASC'
        );

        $results = $this->model_design_seo_regex->getSeoRegexes($filter_data);

        foreach ($results as $result) {
            $data['seo_regexes'][] = array(
                'seo_regex_id'  => $result['seo_regex_id'],
                'name'          => $result['name'],
                'regex'         => $result['regex'],
                'sort_order'    => $result['sort_order'],
                'edit'          => $this->url->link('design/seo_regex/edit', 'user_token=' . $this->session->data['user_token'] . '&seo_regex_id=' . $result['seo_regex_id']),
                'delete'        => $this->url->link('design/seo_regex/delete', 'user_token=' . $this->session->data['user_token'] . '&seo_regex_id=' . $result['seo_regex_id'])
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

        $this->response->setOutput($this->load->view('design/seo_regex_list', $data));
    }

    protected function getForm()
    {
        $data['text_form'] = !isset($this->request->get['seo_regex_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['regex'])) {
            $data['error_regex'] = $this->error['regex'];
        } else {
            $data['error_regex'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token'])
        );

        if (!isset($this->request->get['seo_regex_id'])) {
            $data['action'] = $this->url->link('design/seo_regex/add', 'user_token=' . $this->session->data['user_token']);
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_add'),
                'href' => $this->url->link('design/seo_regex/add', 'user_token=' . $this->session->data['user_token'])
            );
        } else {
            $data['action'] = $this->url->link('design/seo_regex/edit', 'user_token=' . $this->session->data['user_token'] . '&seo_regex_id=' . $this->request->get['seo_regex_id']);
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('design/seo_regex/edit', 'user_token=' . $this->session->data['user_token'] . '&seo_regex_id=' . $this->request->get['seo_regex_id'])
            );
        }

        $data['cancel'] = $this->url->link('design/seo_regex', 'user_token=' . $this->session->data['user_token']);

        if (isset($this->request->get['seo_regex_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $seo_regex_info = $this->model_design_seo_regex->getSeoRegex($this->request->get['seo_regex_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($seo_regex_info)) {
            $data['name'] = $seo_regex_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['regex'])) {
            $data['regex'] = $this->request->post['regex'];
        } elseif (!empty($seo_regex_info)) {
            $data['regex'] = $seo_regex_info['regex'];
        } else {
            $data['regex'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($seo_regex_info)) {
            $data['sort_order'] = $seo_regex_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/seo_regex_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->member->hasPermission('modify', 'design/seo_regex')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (@preg_match('/' . html_entity_decode($this->request->post['regex'], ENT_QUOTES, 'UTF-8') . '/', null) === false) {
            $this->error['regex'] = $this->language->get('error_invalid');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->member->hasPermission('delete', 'design/seo_regex')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
