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

        # Total Count
        $data['total_visitors'] = $this->model_tool_online->getTotalOnlines();

        # Current Week Count
        $current_total_visitors = $this->model_tool_online->getTotalOnlineByCurrentWeek();

        # Last Week Count
        $last_total_visitors = $this->model_tool_online->getTotalOnlineByLastWeek();

        # Get percentage between last and current week visitors count
        if ($current_total_visitors > 0 && $last_total_visitors > 0) {
            $data['percentage'] = (1 - $last_total_visitors / $current_total_visitors) * 100;
        } else {
            $data['percentage'] = 0;
        }

        # Last Week
        $last_week_count = array();

        $last_week_period = array();

        $last_week = strtotime("-1 week");
        $last_sunday = strtotime("last sunday", $last_week);
        $last_saturday = strtotime("next saturday", $last_sunday);

        $last_start = date('Y-m-d', $last_sunday);
        $last_end = date('Y-m-d', $last_saturday);

        $interval = new DateInterval('P1D');

        $realLastEnd = new DateTime($last_end);
        $realLastEnd->add($interval);

        $last_period = new DatePeriod(new DateTime($last_start), $interval, $realLastEnd);

        foreach ($last_period as $last_date) {
            $last_week_period[] = $last_date->format('Y-m-d');
        }

        foreach ($last_week_period as $last_value) {
            $last_week_count[] = $this->model_tool_online->getTotalOnlineByDate($last_value);
        }

        $data['last_week'] = json_encode($last_week_count);

        # Current Week
        $dayCount = array();

        $datePeriod = array();

        $sunday = strtotime("last sunday");
        $saturday = strtotime("next saturday", $sunday);

        $start = date('Y-m-d', $sunday);
        $end = date('Y-m-d', $saturday);

        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach ($period as $date) {
            $datePeriod[] = $date->format('Y-m-d');
        }

        foreach ($datePeriod as $value) {
            $dayCount[] = $this->model_tool_online->getTotalOnlineByDate($value);
        }

        $data['current_week'] = json_encode($dayCount);

        # Enquiries
        $this->load->model('catalog/enquiry');

        $data['enquiries'] = array();

        $filter_data = array(
            'sort'  => 'date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 5
        );

        $result_enquiries = $this->model_catalog_enquiry->getEnquiries($filter_data);

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

        $data['website'] = $this->config->get('config_website');
        $data['website_icon'] = $this->config->get('config_website_icon');

        $data['ecommerce'] = $this->config->get('config_ecommerce');
        $data['ecommerce_icon'] = $this->config->get('config_ecommerce_icon');

        $data['software'] = $this->config->get('config_software');
        $data['software_icon'] = $this->config->get('config_software_icon');

        $data['client'] = $this->config->get('config_client');
        $data['client_icon'] = $this->config->get('config_client_icon');

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/dashboard', $data));
    }
}
