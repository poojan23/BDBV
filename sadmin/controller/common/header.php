<?php

class ControllerCommonHeader extends PT_Controller
{
    public function index()
    {
        $data['title'] = $this->document->getTitle();

        $data['base'] = HTTP_SERVER;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts('header');
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $this->load->language('common/header');

        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());

        if (!isset($this->request->get['user_token']) || !isset($this->session->data['user_token']) || ($this->request->get['user_token'] != $this->session->data['user_token'])) {
            $data['logged'] = '';

            $data['home'] = $this->url->link('user/login');
        } else {
            $data['logged'] = true;

            $data['home'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);
            $data['logout'] = $this->url->link('user/logout', 'user_token=' . $this->session->data['user_token']);
            $data['profile'] = $this->url->link('user/profile', 'user_token=' . $this->session->data['user_token']);

            $this->load->model('tool/image');

            $data['username'] = '';
            $data['email'] = '';
            $data['user_group'] = '';
            $data['image'] = $this->model_tool_image->resize('profile.png', 25, 25);
            $data['thumb'] = $this->model_tool_image->resize('profile.png', 128, 128);

            # User
            $this->load->model('user/user');

            $user_info = $this->model_user_user->getUser($this->user->getId());

            if ($user_info) {
                $data['username'] = $user_info['name'];
                $data['email'] = $user_info['email'];
                $data['user_group'] = $user_info['user_group'];

                if (is_file(DIR_IMAGE . html_entity_decode($user_info['image'], ENT_QUOTES, 'UTF-8'))) {
                    $data['image'] = $this->model_tool_image->resize(html_entity_decode($user_info['image'], ENT_QUOTES, 'UTF-8'), 25, 25);
                    $data['thumb'] = $this->model_tool_image->resize(html_entity_decode($user_info['image'], ENT_QUOTES, 'UTF-8'), 128, 128);
                }
            }

            # Enquiries
            $this->load->model('tool/notification');

            $data['enquiries'] = array();

            $filter_data = array(
                'order' => 'DESC',
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_tool_notification->getEnquiries($filter_data);

            foreach ($results as $result) {
                $time = strtotime($result['date_added']);

                $data['enquiries'][] = array(
                    'name'          => $result['name'],
                    'date_added'    => timeLapse($time),
                    'status'        => $result['status']
                );
            }

            $data['count'] = $this->model_tool_notification->getTotalUnreadEnquiries();

            # Testimonials
            $this->load->model('tool/notification');

            $data['testimonials'] = array();

            $filter_data = array(
                'order' => 'DESC',
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_tool_notification->getTestimonials($filter_data);

            foreach ($results as $result) {
                $time = strtotime($result['date_added']);

                $data['testimonials'][] = array(
                    'name'          => $result['name'],
                    'message'       => (utf8_strlen($result['description']) > 24 ? utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 24) . '...' : $result['description']),
                    'date_added'    => timeLapse($time),
                    'status'        => $result['status']
                );
            }

            $data['count'] = $this->model_tool_notification->getTotalUnreadEnquiries();

            // print_r(end($data['testimonials']));
            // exit;
        }

        return $this->load->view('common/header', $data);
    }
}
