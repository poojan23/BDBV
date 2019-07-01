<?php

class ControllerCommonTestimonial extends PT_Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('common/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/testimonial');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_catalog_testimonial->addTestimonial($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('common/testimonial/success'));
        }

        $this->getForm();
    }

    protected function getForm()
    {
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['company'])) {
            $data['error_company'] = $this->error['company'];
        } else {
            $data['error_company'] = '';
        }

        if (isset($this->error['designation'])) {
            $data['error_designation'] = $this->error['designation'];
        } else {
            $data['error_designation'] = '';
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = '';
        }

        $data['action'] = $this->url->link('common/testimonial');

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['company'])) {
            $data['company'] = $this->request->post['company'];
        } else {
            $data['company'] = '';
        }

        if (isset($this->request->post['designation'])) {
            $data['designation'] = $this->request->post['designation'];
        } else {
            $data['designation'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } else {
            $data['description'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        $data['placeholder'] = HTTP_SERVER . 'image/default-image.png';

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/testimonial', $data));
    }

    protected function validate()
    {
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['company']) < 3) || (utf8_strlen($this->request->post['company']) > 96)) {
            $this->error['company'] = $this->language->get('error_company');
        }

        if ((utf8_strlen($this->request->post['designation']) < 3) || (utf8_strlen($this->request->post['designation']) > 32)) {
            $this->error['designation'] = $this->language->get('error_designation');
        }

        if ((utf8_strlen($this->request->post['description']) < 10) || (utf8_strlen($this->request->post['description']) > 200)) {
            $this->error['description'] = $this->language->get('error_message');
        }

        return !$this->error;
    }

    public function success()
    {
        $this->load->language('common/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/success', $data));
    }

    public function upload()
    {
        $this->load->language('common/testimonial');

        $json = array();

        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                # Sanitize the filename
                $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

                # Allowed file extension types
                $allowed = array(
                    'jpg',
                    'jpeg',
                    'png',
                    'gif'
                );

                if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                # Allowed file mime types
                $allowed = array(
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/x-png',
                    'image/gif'

                );

                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                # Check filesize limit
                $limit = 2097152;

                if ($this->request->files['file']['size'] > $limit || $this->request->files['file']['size'] == 0) {
                    $json['error'] = $this->language->get('error_filesize');
                }

                # Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            $directory = DIR_IMAGE . 'template/testimonial/';

            $file = HTTP_SERVER . 'image/template/testimonial/' . $filename;
            $target = 'template/testimonial/' . $filename;

            move_uploaded_file($this->request->files['file']['tmp_name'], $directory . $filename);

            $json['thumb'] = $file;
            $json['target'] = $target;

            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
