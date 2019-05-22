<?php

class ControllerCommonNav extends PT_Controller
{
    public function index()
    {
        $this->load->language('common/nav');

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_logo')) && is_file(DIR_IMAGE . $this->config->get('config_logo_colour'))) {
            $data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
            $data['logo_colour'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo_colour');
        } else {
            $data['logo'] = '';
            $data['logo_colour'] = '';
        }

        $data['home'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

        return $this->load->view('common/nav', $data);
    }
}
