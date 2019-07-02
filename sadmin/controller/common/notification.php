<?php

class ControllerCommonNotification extends PT_Controller
{
    # Enquiries
    public function sseEnquiries()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $limit = 5;

        $this->load->model('tool/notification');

        $json = array();

        $filter_data = array(
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1
        );

        $total = $this->model_tool_notification->getTotalEnquiries();

        $json['count'][] = $this->model_tool_notification->getTotalUnreadEnquiries();

        $results = $this->model_tool_notification->getEnquiries($filter_data);

        if (isset($this->session->data['enquiry_id']) && ($this->session->data['enquiry_id'] != '')) {
            if ($results) {
                if (($this->session->data['enquiry_id'] != $results[0]['enquiry_id'])) {
                    foreach ($results as $result) {
                        $time = strtotime($result['date_added']);

                        $json['data'] = array(
                            'name'          => $result['name'],
                            'date_added'    => timeLapse($time),
                            'status'        => $result['status'],
                            'total'         => $total,
                            'limit'         => $limit
                        );
                    }

                    $this->session->data['enquiry_id'] = $results[0]['enquiry_id'];
                }
            } else {
                unset($this->session->data['enquiry_id']);
            }
        } else {
            if ($results) {
                foreach ($results as $result) {
                    $time = strtotime($result['date_added']);

                    $json['data'] = array(
                        'name'          => $result['name'],
                        'date_added'    => timeLapse($time),
                        'status'        => $result['status'],
                        'total'         => $total,
                        'limit'         => $limit
                    );
                }

                $this->session->data['enquiry_id'] = $results[0]['enquiry_id'];
            }
        }

        $data = json_encode($json);

        echo "data: {$data}\n\n";

        ob_flush();
        flush();
    }

    public function ajaxEnquiries()
    {
        $limit = 5;

        $this->load->model('tool/notification');

        $json = array();

        $filter_data = array(
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1
        );

        $total = $this->model_tool_notification->getTotalEnquiries();

        $json['count'][] = $this->model_tool_notification->getTotalUnreadEnquiries();

        $results = $this->model_tool_notification->getEnquiries($filter_data);

        if (isset($this->session->data['enquiry_id']) && ($this->session->data['enquiry_id'] != '')) {
            if ($results && ($results[0]['status'] == 'unread')) {
                if ($this->session->data['enquiry_id'] != $results[0]['enquiry_id']) {
                    foreach ($results as $result) {
                        $time = strtotime($result['date_added']);

                        $json['data'] = array(
                            'name'          => $result['name'],
                            'date_added'    => timeLapse($time),
                            'status'        => $result['status'],
                            'total'         => $total,
                            'limit'         => $limit
                        );
                    }

                    $this->session->data['enquiry_id'] = $results[0]['enquiry_id'];
                }
            } else {
                unset($this->session->data['enquiry_id']);
            }
        } else {
            if ($results && ($results[0]['status'] == 'unread')) {
                foreach ($results as $result) {
                    $time = strtotime($result['date_added']);

                    $json['data'] = array(
                        'name'          => $result['name'],
                        'date_added'    => timeLapse($time),
                        'status'        => $result['status'],
                        'total'         => $total,
                        'limit'         => $limit
                    );
                }

                $this->session->data['enquiry_id'] = $results[0]['enquiry_id'];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    # Testimonials
    public function sseTestimonials()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $limit = 5;

        $this->load->model('tool/notification');

        $json = array();

        $filter_data = array(
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1
        );

        $total = $this->model_tool_notification->getTotalTestimonials();

        $json['count'][] = $this->model_tool_notification->getTotalUnreadTestimonials();

        $results = $this->model_tool_notification->getTestimonials($filter_data);

        if (isset($this->session->data['testimonial_id']) && ($this->session->data['testimonial_id'] != '')) {
            if ($results) {
                if ($this->session->data['testimonial_id'] != $results[0]['testimonial_id']) {
                    foreach ($results as $result) {
                        $time = strtotime($result['date_added']);

                        $json['data'] = array(
                            'name'          => $result['name'],
                            'message'       => (utf8_strlen($result['description']) > 24 ? utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 24) . '...' : $result['description']),
                            'date_added'    => timeLapse($time),
                            'status'        => $result['status'],
                            'total'         => $total,
                            'limit'         => $limit
                        );
                    }

                    $this->session->data['testimonial_id'] = $results[0]['testimonial_id'];
                }
            } else {
                unset($this->session->data['testimonial_id']);
            }
        } else {
            if ($results) {
                foreach ($results as $result) {
                    $time = strtotime($result['date_added']);

                    $json['data'] = array(
                        'name'          => $result['name'],
                        'message'       => (utf8_strlen($result['description']) > 24 ? utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 24) . '...' : $result['description']),
                        'date_added'    => timeLapse($time),
                        'status'        => $result['status'],
                        'total'         => $total,
                        'limit'         => $limit
                    );
                }

                $this->session->data['testimonial_id'] = $results[0]['testimonial_id'];
            }
        }

        $data = json_encode($json);

        echo "data: {$data}\n\n";

        ob_flush();
        flush();
    }

    public function ajaxTestimonials()
    {
        $limit = 5;

        $this->load->model('tool/notification');

        $json = array();

        $filter_data = array(
            'order' => 'DESC',
            'start' => 0,
            'limit' => 1
        );

        $total = $this->model_tool_notification->getTotalTestimonials();

        $json['count'][] = $this->model_tool_notification->getTotalUnreadTestimonials();

        $results = $this->model_tool_notification->getTestimonials($filter_data);

        if (isset($this->session->data['testimonial_id']) && ($this->session->data['testimonial_id'] != '')) {
            if ($results && ($results[0]['status'] == '0')) {
                if ($this->session->data['testimonial_id'] != $results[0]['testimonial_id']) {
                    foreach ($results as $result) {
                        $time = strtotime($result['date_added']);

                        $json['data'] = array(
                            'name'          => $result['name'],
                            'message'       => (utf8_strlen($result['description']) > 24 ? utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 24) . '...' : $result['description']),
                            'date_added'    => timeLapse($time),
                            'status'        => $result['status'],
                            'total'         => $total,
                            'limit'         => $limit
                        );
                    }

                    $this->session->data['testimonial_id'] = $results[0]['testimonial_id'];
                }
            } else {
                unset($this->session->data['testimonial_id']);
            }
        } else {
            if ($results && ($results[0]['status'] == '0')) {
                foreach ($results as $result) {
                    $time = strtotime($result['date_added']);

                    $json['data'] = array(
                        'name'          => $result['name'],
                        'message'       => (utf8_strlen($result['description']) > 24 ? utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 24) . '...' : $result['description']),
                        'date_added'    => timeLapse($time),
                        'status'        => $result['status'],
                        'total'         => $total,
                        'limit'         => $limit
                    );
                }

                $this->session->data['testimonial_id'] = $results[0]['testimonial_id'];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
