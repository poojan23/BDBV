<?php

class ControllerDesignSeoUrl extends PT_Controller {

    private $error = array();

    public function index() {
        $this->load->language('design/seo_url');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_url');

        $this->getList();
    }

    public function add() {
        $this->load->language('design/seo_url');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_url');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_seo_url->addSeoUrl($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('design/seo_url', 'member_token=' . $this->session->data['member_token'] , true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('design/seo_url');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_url');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_design_seo_url->editSeoUrl($this->request->get['seo_url_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('design/seo_url', 'member_token=' . $this->session->data['member_token'], true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('design/seo_url');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/seo_url');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $seo_url_id) {
                $this->model_design_seo_url->deleteSeoUrl($seo_url_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('design/seo_url', 'member_token=' . $this->session->data['member_token'], true));
        }

        $this->getList();
    }

    protected function getList() {

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('design/seo_url', 'member_token=' . $this->session->data['member_token'], true)
        );

        $data['add'] = $this->url->link('design/seo_url/add', 'member_token=' . $this->session->data['member_token'], true);
        $data['edit'] = $this->url->link('design/seo_url/edit', 'member_token=' . $this->session->data['member_token'], true);
        $data['delete'] = $this->url->link('design/seo_url/delete', 'member_token=' . $this->session->data['member_token'], true);

        $data['member_token'] = $this->session->data['member_token'];

         if(isset($this->error['warning'])) {
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

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/seo_url_list', $data));
    }
    
    public function getData() {
        $json = array();

        $this->load->model('design/seo_url');

        $totalData = $this->model_design_seo_url->getTotalSeoUrls();

        $totalFiltered = $totalData;

        $results = $this->model_design_seo_url->getSeoUrls();

        $table = array();

        foreach($results as $result) {
            $nestedData['seo_url_id']       = $result['seo_url_id'];
            $nestedData['query']            = $result['query'];
            $nestedData['keyword']          = $result['keyword'];
            $nestedData['language']         = $result['language'];

            $table[] = $nestedData;
        }

        $json = array(
            'resultsTotal'      => intval($totalData),
            'resultFiltered'    => intval($totalFiltered),
            'data'              => $table
        );

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['seo_url_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['query'])) {
            $data['error_query'] = $this->error['query'];
        } else {
            $data['error_query'] = '';
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        if (isset($this->error['push'])) {
            $data['error_push'] = $this->error['push'];
        } else {
            $data['error_push'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('design/seo_url', 'member_token=' . $this->session->data['member_token'], true)
        );

        if (!isset($this->request->get['seo_url_id'])) {
            $data['action'] = $this->url->link('design/seo_url/add', 'member_token=' . $this->session->data['member_token'] , true);
        } else {
            $data['action'] = $this->url->link('design/seo_url/edit', 'member_token=' . $this->session->data['member_token'] . '&seo_url_id=' . $this->request->get['seo_url_id'] , true);
        }

        $data['cancel'] = $this->url->link('design/seo_url', 'member_token=' . $this->session->data['member_token'] , true);

        if (isset($this->request->get['seo_url_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $seo_url_info = $this->model_design_seo_url->getSeoUrl($this->request->get['seo_url_id']);
        }

        if (isset($this->request->post['query'])) {
            $data['query'] = $this->request->post['query'];
        } elseif (!empty($seo_url_info)) {
            $data['query'] = htmlspecialchars($seo_url_info['query'], ENT_COMPAT, 'UTF-8');
        } else {
            $data['query'] = '';
        }

        if (isset($this->request->post['keyword'])) {
            $data['keyword'] = $this->request->post['keyword'];
        } elseif (!empty($seo_url_info)) {
            $data['keyword'] = $seo_url_info['keyword'];
        } else {
            $data['keyword'] = '';
        }

        if (isset($this->request->post['push'])) {
            $data['push'] = $this->request->post['push'];
        } elseif (!empty($seo_url_info)) {
            $data['push'] = htmlspecialchars($seo_url_info['push'], ENT_COMPAT, 'UTF-8');
        } else {
            $data['push'] = '';
        }

//        $data['stores'] = array();
//
//        $data['stores'][] = array(
//            'store_id' => 0,
//            'name' => $this->language->get('text_default')
//        );
//
//        $this->load->model('setting/store');
//
//        $stores = $this->model_setting_store->getStores();
//
//        foreach ($stores as $store) {
//            $data['stores'][] = array(
//                'store_id' => $store['store_id'],
//                'name' => $store['name']
//            );
//        }
//
//        if (isset($this->request->post['store_id'])) {
//            $data['store_id'] = $this->request->post['store_id'];
//        } elseif (!empty($seo_url_info)) {
//            $data['store_id'] = $seo_url_info['store_id'];
//        } else {
//            $data['store_id'] = '';
//        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['language_id'])) {
            $data['language_id'] = $this->request->post['language_id'];
        } elseif (!empty($seo_url_info)) {
            $data['language_id'] = $seo_url_info['language_id'];
        } else {
            $data['language_id'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/seo_url_form', $data));
    }

    protected function validateForm() {
        if (!$this->member->hasPermission('modify', 'design/seo_url')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->request->post['query']) {
            $seo_urls = $this->model_design_seo_url->getSeoUrlsByQuery($this->request->post['query']);

            foreach ($seo_urls as $seo_url) {
                if (($seo_url['language_id'] == $this->request->post['language_id']) && (!isset($this->request->get['seo_url_id']) || (($seo_url['query'] != 'seo_url_id=' . $this->request->get['seo_url_id'])))) {
                    $this->error['query'] = $this->language->get('error_query_exists');

                    break;
                }
            }
        } else {
            $this->error['query'] = $this->language->get('error_query');
        }

        if ($this->request->post['keyword']) {
            if (preg_match('/[^a-zA-Z0-9_\-]+/', $this->request->post['keyword'])) {
                $this->error['keyword'] = $this->language->get('error_keyword');
            }

            $seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($this->request->post['keyword']);

            foreach ($seo_urls as $seo_url) {
                if (($seo_url['language_id'] == $this->request->post['language_id']) && (!isset($this->request->get['seo_url_id']) || (($seo_url['query'] != 'seo_url_id=' . $this->request->get['seo_url_id'])))) {
                    $this->error['keyword'] = $this->language->get('error_keyword_exists');

                    break;
                }
            }
        }

        if (!$this->request->post['push']) {
            $this->error['push'] = $this->language->get('error_push');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->member->hasPermission('modify', 'design/seo_url')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}
