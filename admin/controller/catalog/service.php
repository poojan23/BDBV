<?php

class ControllerCatalogService extends PT_Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('catalog/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/service');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('catalog/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/service');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_catalog_service->addService($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/service', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('catalog/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/service');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_service->editService($this->request->get['service_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/service', 'member_token=' . $this->session->data['member_token']));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/service');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/service');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $service_id) {
                $this->model_catalog_service->deleteService($service_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/service', 'member_token=' . $this->session->data['member_token']));
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
            'href'  => $this->url->link('catalog/service', 'member_token=' . $this->session->data['member_token'])
        );

        $data['add'] = $this->url->link('catalog/service/add', 'member_token=' . $this->session->data['member_token']);
        $data['delete'] = $this->url->link('catalog/service/delete', 'member_token=' . $this->session->data['member_token']);

        $data['services'] = array();

        $results = $this->model_catalog_service->getServices();

        foreach ($results as $result) {
            $data['services'][] = array(
                'service_id'    => $result['service_id'],
                'name'          => $result['name'],
                'sort_order'    => $result['sort_order'],
                'status'        => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit'          => $this->url->link('catalog/service/edit', 'member_token=' . $this->session->data['member_token'] . '&service_id=' . $result['service_id']),
                'delete'        => $this->url->link('catalog/service/delete', 'member_token=' . $this->session->data['member_token'] . '&service_id=' . $result['service_id'])
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

        $this->response->setOutput($this->load->view('catalog/service_list', $data));
    }

    protected function getForm()
    {
        $this->document->addStyle("view/dist/plugins/iCheck/all.css");
        $this->document->addScript("view/dist/plugins/ckeditor/ckeditor.js");
        $this->document->addScript("view/dist/plugins/ckeditor/adapters/jquery.js");
        $this->document->addScript("view/dist/plugins/iCheck/icheck.min.js");

        $data['text_form'] = !isset($this->request->get['service_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['name'])) {
            $data['name_err'] = $this->error['name'];
        } else {
            $data['name_err'] = array();
        }

        if (isset($this->error['description'])) {
            $data['description_err'] = $this->error['description'];
        } else {
            $data['description_err'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['meta_title_err'] = $this->error['meta_title'];
        } else {
            $data['meta_title_err'] = array();
        }

        if (isset($this->error['keyword'])) {
            $data['keyword_err'] = $this->error['keyword'];
        } else {
            $data['keyword_err'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('catalog/service', 'member_token=' . $this->session->data['member_token'])
        );

        if (!isset($this->request->get['service_id'])) {
            $data['action'] = $this->url->link('catalog/service/add', 'member_token=' . $this->session->data['member_token']);
            $data['breadcrumbs'][] = array(
                'text'  => $this->language->get('text_add'),
                'href'  => $this->url->link('catalog/service/add', 'member_token=' . $this->session->data['member_token'])
            );
        } else {
            $data['action'] = $this->url->link('catalog/service/edit', 'member_token=' . $this->session->data['member_token'] . '&service_id=' . $this->request->get['service_id']);
            $data['breadcrumbs'][] = array(
                'text'  => $this->language->get('text_edit'),
                'href'  => $this->url->link('catalog/service/edit', 'member_token=' . $this->session->data['member_token'] . '&service_id=' . $this->request->get['service_id'])
            );
        }

        $data['cancel'] = $this->url->link('catalog/service', 'member_token=' . $this->session->data['member_token']);

        if (isset($this->request->get['service_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $service_info = $this->model_catalog_service->getService($this->request->get['service_id']);
        }

        $data['member_token'] = $this->session->data['member_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['service_description'])) {
            $data['service_description'] = $this->request->post['service_description'];
        } elseif (!empty($service_info)) {
            $data['service_description'] = $this->model_catalog_service->getServiceDescriptions($this->request->get['service_id']);
        } else {
            $data['service_description'] = array();
        }

        $data['icons'] = array();

        $pattern = '/\.(icon-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
        $subject = file_get_contents('view/dist/css/et-line.css');

        preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

        sort($matches);

        $pattern1 = '/\.(py-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
        $subject1 = file_get_contents('view/dist/css/popaya.css');

        preg_match_all($pattern1, $subject1, $matches1, PREG_SET_ORDER);

        $mergers = array_merge($matches1, $matches);

        foreach ($mergers as $key => $match) {
            $data['icons'][$key] = array(
                'icon'  => ($match[1] == 'py-popaya') ? 'py ' . $match[1] : $match[1],
                'name' => str_replace(array('icon-', '-'), ' ', $match[1])
            );
        }

        if (isset($this->request->post['icon'])) {
            $data['iconn'] = $this->request->post['icon'];
        } elseif (!empty($service_info)) {
            $data['iconn'] = $service_info['icon'];
        } else {
            $data['iconn'] = 'py py-popaya';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($service_info)) {
            $data['image'] = $service_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('no-image.png', 100, 100);

        if (is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
            $data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
        } else {
            $data['thumb'] = $data['placeholder'];
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($service_info)) {
            $data['sort_order'] = $service_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($service_info)) {
            $data['status'] = $service_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['service_seo_url'])) {
            $data['service_seo_url'] = $this->request->post['service_seo_url'];
        } elseif (!empty($service_info)) {
            $data['service_seo_url'] = $this->model_catalog_service->getServiceSeoUrls($this->request->get['service_id']);
        } else {
            $data['service_seo_url'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/service_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/service')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['service_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['description']) < 10)) {
                $this->error['description'][$language_id] = $this->language->get('error_description');
            }

            if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if ($this->request->post['service_seo_url']) {
            $this->load->model('design/seo_url');

            foreach ($this->request->post['service_seo_url'] as $language_id => $keyword) {
                if ($keyword) {
                    $seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);

                    foreach ($seo_urls as $seo_url) {
                        if (($seo_url['language_id'] == $language_id) && (!isset($this->request->get['service_id']) || ($seo_url['query'] != 'service_id=' . (int) $this->request->get['service_id']))) {
                            $this->error['keyword'][$language_id] = $this->language->get('error_keyword');

                            break;
                        }
                    }
                } else {
                    $this->error['keyword'][$language_id] = $this->language->get('error_seo');
                }
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('delete', 'catalog/service')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/service');

            $filter_data = array(
                'filter_name'   => $this->request->get['filter_name'],
                'sort'          => 'name',
                'order'         => 'ASC',
                'start'         => 0,
                'limit'         => 5
            );

            $results = $this->model_catalog_service->getServices($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'service_id'  => $result['service_id'],
                    'title'                 => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value;
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
