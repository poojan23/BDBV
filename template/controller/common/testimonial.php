<?php

class ControllerCommonTestimonial extends PT_Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('common/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        //$this->load->model('common/testimonial');

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/testimonial', $data));
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
            $target = 'testimonial/' . $filename;

            move_uploaded_file($this->request->files['file']['tmp_name'], $directory . $filename);

            $json['thumb'] = $file;
            $json['target'] = $target;

            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
