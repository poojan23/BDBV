<?php

class ControllerCommonHome extends PT_Controller
{
    public function index()
    {
        $this->load->language('common/home');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/information');

        $this->load->model('design/banner');

        $this->load->model('tool/image');

        # Top
        $top_info = $this->model_catalog_information->getInformation(1);

        if ($top_info) {
            $data['top_title'] = $top_info['title'];
            $data['top_description'] = trim(strip_tags(html_entity_decode($top_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['top_meta_description'] = $top_info['meta_description'];
            $data['top_status'] = $top_info['status'];
        }

        $data['tops'] = array();

        $results = $this->model_design_banner->getBanner(1, 0, 1);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
                $data['tops'][] = array(
                    'title' => $result['title'],
                    'link'  => $result['link'],
                    'image' => $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 733, 1315)
                );
            }
        }

        # Services
        $service_info = $this->model_catalog_information->getInformation(2);

        if ($service_info) {
            $data['service_title'] = $service_info['title'];
            $data['service_description'] = trim(strip_tags(html_entity_decode($service_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['service_meta_description'] = $service_info['meta_description'];
            $data['service_status'] = $service_info['status'];
        }

        $this->load->model('catalog/service');

        $data['services'] = array();

        $results = $this->model_catalog_service->getServices(0, 3);

        foreach ($results as $result) {
            $data['services'][] = array(
                'icon'          => $result['icon'],
                'name'          => $result['name'],
                'description'   => trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')))
            );
        }

        # How It Works
        $work_info = $this->model_catalog_information->getInformation(3);

        if ($work_info) {
            $data['work_title'] = $work_info['title'];
            $data['work_description'] = trim(strip_tags(html_entity_decode($work_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['work_meta_description'] = $work_info['meta_description'];
            $data['work_status'] = $work_info['status'];
        }

        $data['works'] = array();

        $results = $this->model_design_banner->getBanner(2, 0, 1);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
                list($width, $height) = getimagesize(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'));

                $data['works'][] = array(
                    'title' => $result['title'],
                    'image' => $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $width, $height)
                );
            }
        }

        # About Popaya™
        $about_info = $this->model_catalog_information->getInformation(4);

        if ($about_info) {
            $data['about_title'] = $about_info['title'];

            $string = explode('<p>', str_replace('</p>', '', html_entity_decode($about_info['description'], ENT_COMPAT, 'UTF-8')));
            $data['about_description'] = array_splice($string, 1);

            $data['about_meta_description'] = $about_info['meta_description'];
            $data['about_status'] = $about_info['status'];
        }

        # Projects
        $project_info = $this->model_catalog_information->getInformation(5);

        if ($project_info) {
            $data['project_title'] = $project_info['title'];
            $data['project_description'] = trim(strip_tags(html_entity_decode($project_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['project_meta_description'] = $project_info['meta_description'];
            $data['project_status'] = $project_info['status'];
        }

        $data['projects'] = array();

        $results = $this->model_design_banner->getBanner(3, 0, 10);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
                $data['projects'][] = array(
                    'title' => $result['title'],
                    'image' => $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 750, 1335)
                );
            }
        }

        # Team
        $team_info = $this->model_catalog_information->getInformation(6);

        if ($team_info) {
            $data['team_title'] = $team_info['title'];
            $data['team_description'] = trim(strip_tags(html_entity_decode($team_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['team_meta_description'] = $team_info['meta_description'];
            $data['team_status'] = $team_info['status'];
        }

        $this->load->model('catalog/team');

        $data['teams'] = array();

        $results = $this->model_catalog_team->getTeams(0, 6);

        foreach ($results as $result) {
            if ($result['image']) {
                $thumb = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 400, 500);
            } else {
                $thumb = $this->model_tool_image->resize(html_entity_decode('default-image.png', ENT_QUOTES, 'UTF-8'), 400, 500);
            }

            $data['teams'][] = array(
                'name'          => $result['name'],
                'designation'   => $result['designation'],
                'thumb'         => $thumb
            );
        }

        # Watch (See Our Work Showcase)
        $watch_info = $this->model_catalog_information->getInformation(7);

        if ($watch_info) {
            $data['watch_title'] = $watch_info['title'];
            $data['watch_description'] = trim(strip_tags(html_entity_decode($watch_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['watch_meta_description'] = $watch_info['meta_description'];
            $data['watch_status'] = $watch_info['status'];
        }

        $data['watches'] = array();

        $results = $this->model_design_banner->getBanner(4, 0, 1);

        foreach ($results as $result) {
            if (DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8')) {
                list($width, $height) = getimagesize(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'));

                $data['watches'][] = array(
                    'title' => $result['title'],
                    'link'  => preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", "//www.youtube.com/embed/$1?rel=0&autoplay=0", $result['link']),
                    'image' => $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $width, $height)
                );
            }
        }

        # Testimonials
        $testimonial_info = $this->model_catalog_information->getInformation(8);

        if ($testimonial_info) {
            $data['testimonial_title'] = $testimonial_info['title'];
            $data['testimonial_description'] = trim(strip_tags(html_entity_decode($testimonial_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['testimonial_meta_description'] = $testimonial_info['meta_description'];
            $data['testimonial_status'] = $testimonial_info['status'];
        }

        $this->load->model('catalog/testimonial');

        $data['testimonials'] = array();

        $results = $this->model_catalog_testimonial->getTestimonials(0, 6);

        foreach ($results as $result) {
            if ($result['image']) {
                $thumb = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 150, 150);
            } else {
                $thumb = $this->model_tool_image->resize(html_entity_decode('default-image.png', ENT_QUOTES, 'UTF-8'), 150, 150);
            }

            $data['testimonials'][] = array(
                'name'          => $result['name'],
                'description'   => trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))),
                'designation'   => $result['designation'],
                'thumb'         => $thumb
            );
        }

        # Facts
        $fact_info = $this->model_catalog_information->getInformation(9);

        if ($fact_info) {
            $data['fact_title'] = $fact_info['title'];
            $data['fact_description'] = trim(strip_tags(html_entity_decode($fact_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['fact_meta_description'] = $fact_info['meta_description'];
            $data['fact_status'] = $fact_info['status'];
        }

        $this->load->model('tool/online');

        $data['website_icon'] = $this->config->get('config_website_icon');
        $data['website'] = $this->config->get('config_website');

        $data['ecommerce_icon'] = $this->config->get('config_ecommerce_icon');
        $data['ecommerce'] = $this->config->get('config_ecommerce');

        $data['software_icon'] = $this->config->get('config_software_icon');
        $data['software'] = $this->config->get('config_software');

        $data['client_icon'] = $this->config->get('config_client_icon');
        $data['client'] = $this->config->get('config_client');

        $data['visitor_icon'] = $this->config->get('config_visitor_icon');
        $data['visitor'] = ($this->model_tool_online->getTotalOnlines() > 9999) ? '9999' : $this->model_tool_online->getTotalOnlines();

        # Blog
        $blog_info = $this->model_catalog_information->getInformation(10);

        if ($blog_info) {
            $data['blog_title'] = $blog_info['title'];
            $data['blog_description'] = trim(strip_tags(html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['blog_meta_description'] = $blog_info['meta_description'];
            $data['blog_status'] = $blog_info['status'];
        }

        # Contact
        $contact_info = $this->model_catalog_information->getInformation(11);

        if ($contact_info) {
            $data['contact_title'] = $contact_info['title'];
            $data['contact_description'] = trim(strip_tags(html_entity_decode($contact_info['description'], ENT_QUOTES, 'UTF-8')));
            $data['contact_meta_description'] = $contact_info['meta_description'];
            $data['contact_status'] = $contact_info['status'];
        }

        $data['address'] = nl2br($this->config->get('config_address'));
        $data['telephone'] = $this->config->get('config_telephone');
        $data['email'] = $this->config->get('config_email');
        $data['open'] = preg_replace("/^(.*)<br.*\/?>/m", '<p>$1</p><p>', nl2br($this->config->get('config_open')));

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/home', $data));
    }

    public function send()
    {
        $this->load->language('common/home');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
                $json['error']['name'] = $this->language->get('error_name');
            }

            if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if ((utf8_strlen($this->request->post['message']) < 10) || (utf8_strlen($this->request->post['message']) > 3000)) {
                $json['error']['message'] = $this->language->get('error_message');
            }

            if (!$json) {
                $json['success'] = $this->language->get('text_success');

                if (filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                    $mail = new Mail($this->config->get('config_mail_engine'));
                    $mail->parameter = $this->config->get('config_mail_parameter');
                    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                    $mail->setTo($this->config->get('config_email'));
                    $mail->setFrom($this->request->post['email']);
                    $mail->setReplyTo($this->request->post['email']);
                    $mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
                    $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
                    $mail->setText($this->request->post['message']);
                    $mail->send();
                }

                if (isset($this->request->post['name'])) {
                    $data['name'] = $this->request->post['name'];
                } else {
                    $data['name'] = '';
                }

                if (isset($this->request->post['email'])) {
                    $data['email'] = $this->request->post['email'];
                } else {
                    $data['email'] = '';
                }

                if (isset($this->request->post['message'])) {
                    $data['message'] = $this->request->post['message'];
                } else {
                    $data['message'] = '';
                }

                $this->load->model('catalog/enquiry');

                $this->model_catalog_enquiry->addEnquiry($this->request->post);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
