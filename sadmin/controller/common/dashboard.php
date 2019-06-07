<?php

class ControllerCommonDashboard extends PT_Controller
{
    public function index()
    {
        $this->load->language('common/dashboard');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/dist/plugins/chart.js/Chart.min.js');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'])
        );

        $this->load->model('tool/online');

        $dayCount = array();

        $datePeriod = array();

        $start = date('Y-m-d', strtotime("last sunday"));
        $end = date('Y-m-d', strtotime("next saturday"));

        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach ($period as $date) {
            $datePeriod[] = $date->format('Y-m-d');
        }

        foreach ($datePeriod as $value) {
            $dayCount[] = $this->model_tool_online->getTotalOnlineByCurrentDay($value);
        }

        print_r($dayCount);
        exit;

        $this->load->model('catalog/enquiry');

        $data['enquiries'] = array();

        $result_enquiries = $this->model_catalog_enquiry->getTopEnquiries();

        foreach ($result_enquiries as $result) {
            $data['enquiries'][] = array(
                'enquiry_id'    => $result['enquiry_id'],
                'name'          => $result['name'],
                'email'         => $result['email'],
                'date_added'    => date("d-m-Y", strtotime($result['date_added']))
            );
        }

        $this->load->model('catalog/service');

        $data['services'] = array();

        $result_services = $this->model_catalog_service->getServices();

        foreach ($result_services as $result) {
            $data['services'][] = array(
                'service_id'  => $result['service_id'],
                'name'        => $result['name'],
                'sort_order'  => $result['sort_order']
            );
        }

        $data['website']    = $this->config->get('config_website');
        $data['software']   = $this->config->get('config_software');
        $data['client']     = $this->config->get('config_client');

        $data['header']     = $this->load->controller('common/header');
        $data['nav']        = $this->load->controller('common/nav');
        $data['footer']     = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/dashboard', $data));
    }
}
