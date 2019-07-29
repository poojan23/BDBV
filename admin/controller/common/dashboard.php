<?php

class ControllerCommonDashboard extends PT_Controller
{
    public function index()
    {
        $this->load->language('common/dashboard');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/dist/js/jquery.easypiechart.min.js');
        $this->document->addScript('view/dist/js/jquery.sparkline.index.min.js');
        $this->document->addScript('view/dist/js/jquery.flot.min.js');
        $this->document->addScript('view/dist/js/jquery.flot.pie.min.js');
        $this->document->addScript('view/dist/js/jquery.flot.resize.min.js');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'member_token=' . $this->session->data['member_token'])
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('catalog/information', 'member_token=' . $this->session->data['member_token'])
        );

        $this->load->model('tool/online');

        # Total Count
        $data['total_visitors'] = $this->model_tool_online->getTotalOnlines();

        # Current Week Count
        $current_total_visitors = $this->model_tool_online->getTotalOnlineByCurrentWeek();
        $data['current_total_visitors'] = $current_total_visitors;

        # Last Week Count
        $last_total_visitors = $this->model_tool_online->getTotalOnlineByLastWeek();
        $data['last_total_visitors'] = $last_total_visitors;

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

        $results = $this->model_catalog_enquiry->getEnquiries($filter_data);

        foreach ($results as $result) {
            $data['enquiries'][] = array(
                'enquiry_id'    => $result['enquiry_id'],
                'name'          => $result['name'],
                'email'         => $result['email'],
                'status'        => $result['status'],
                'date_added'    => date("d-m-Y", strtotime($result['date_added']))
            );
        }

        # Services
        $this->load->model('catalog/service');

        $data['services'] = array();

        $results = $this->model_catalog_service->getServices();

        foreach ($results as $result) {
            $data['services'][] = array(
                'service_id'  => $result['service_id'],
                'name'        => $result['name'],
                'sort_order'  => $result['sort_order']
            );
        }

        # Testimonials
        $this->load->model('catalog/testimonial');

        $this->load->model('tool/image');

        $data['testimonials'] = array();

        $filter_data = array(
            'sort'  => 'date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 5
        );

        $results = $this->model_catalog_testimonial->getTestimonials($filter_data);

        foreach ($results as $result) {
            if (html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8')) {
                $thumb = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 36, 36);
            } else {
                $thumb = $this->model_tool_image->resize(html_entity_decode('default-image.png', ENT_QUOTES, 'UTF-8'), 36, 36);
            }

            $time = strtotime($result['date_added']);

            $data['testimonials'][] = array(
                'name'          => $result['name'],
                'description'   => (utf8_strlen($result['description'], ENT_QUOTES, 'UTF-8') > 145 ? utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 145) . ' ...' : trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')))),
                'thumb'         => $thumb,
                'date_added'    => timeLapse($time)
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

        $this->load->model('user/user');

        $user_info = $this->model_user_user->getUser($this->user->getId());

        $data['welcome'] = sprintf($this->language->get('text_welcome'), $user_info['firstname'] . ' ' . $user_info['lastname'], $user_info['user_group']);

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/dashboard', $data));
    }
}
