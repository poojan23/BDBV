<?php

class ControllerCatalogEnquiry extends PT_Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('catalog/enquiry');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/enquiry');

        $this->getList();
    }

    public function edit()
    {
        $this->load->language('catalog/enquiry');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/enquiry');

        $this->model_catalog_enquiry->readStatus($this->request->get['enquiry_id']);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_enquiry->updateStatus($this->request->get['enquiry_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/enquiry', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/enquiry');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/enquiry');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $enquiry_id) {
                $this->model_catalog_enquiry->deleteEnquiry($enquiry_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/enquiry', 'member_token=' . $this->session->data['member_token']));
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
            'href' => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/enquiry', 'member_token=' . $this->session->data['member_token'])
        );

        $data['enquiries'] = array();

        $results = $this->model_catalog_enquiry->getEnquiries();

        foreach ($results as $result) {
            $data['enquiries'][] = array(
                'enquiry_id'    => $result['enquiry_id'],
                'name'          => $result['name'],
                'email'         => $result['email'],
                'status'        => $result['status'],
                'edit'          => $this->url->link('catalog/enquiry/edit', 'member_token=' . $this->session->data['member_token'] . '&enquiry_id=' . $result['enquiry_id'])
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

        $this->response->setOutput($this->load->view('catalog/enquiry_list', $data));
    }

    protected function getForm()
    {
        $data['text_form'] = $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('catalog/enquiry', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_edit'),
            'href'  => $this->url->link('catalog/enquiry/edit', 'member_token=' . $this->session->data['member_token'])
        );

        $data['action'] = $this->url->link('catalog/enquiry/edit', 'member_token=' . $this->session->data['member_token'] . '&enquiry_id=' . $this->request->get['enquiry_id']);
        $data['cancel'] = $this->url->link('catalog/enquiry', 'member_token=' . $this->session->data['member_token']);

        if (isset($this->request->get['enquiry_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $enquiry_info = $this->model_catalog_enquiry->getEnquiry($this->request->get['enquiry_id']);
        }

        if (!empty($enquiry_info)) {
            $data['name'] = $enquiry_info['name'];
        }

        if (!empty($enquiry_info)) {
            $data['email'] = $enquiry_info['email'];
        }

        if (!empty($enquiry_info)) {
            $data['message'] = $enquiry_info['message'];
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($enquiry_info)) {
            $data['status'] = $enquiry_info['status'];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/enquiry_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/enquiry')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('delete', 'catalog/enquiry')) {
            $this->error['warning'] = $this->language->get('error_delete');
        }

        return !$this->error;
    }
}
