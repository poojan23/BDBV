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
}
