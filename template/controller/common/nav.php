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

        $this->load->model('catalog/information');

        $results = $this->model_catalog_information->getInformations();

        $data['menus'] = array();

        if ($results[1]['status']) {
            $data['menus'][] = array(
                'title' => $this->language->get('text_service'),
                'href'  => 'rt_services'
            );
        }

        if ($results[3]['status']) {
            $data['menus'][] = array(
                'title' => $this->language->get('text_about'),
                'href'  => 'rt_features'
            );
        }

        if ($results[4]['status']) {
            $data['menus'][] = array(
                'title' => $this->language->get('text_project'),
                'href'  => 'rt_screenshots'
            );
        }

        if ($results[5]['status']) {
            $data['menus'][] = array(
                'title' => $this->language->get('text_team'),
                'href'  => 'rt_team'
            );
        }

        if ($results[7]['status']) {
            $data['menus'][] = array(
                'title' => $this->language->get('text_testimonial'),
                'href'  => 'rt_testimonial'
            );
        }

        if ($results[9]['status']) {
            $data['menus'][] = array(
                'title' => $this->language->get('text_blog'),
                'href'  => 'rt_blog'
            );
        }

        if ($results[10]['status']) {
            $data['menus'][] = array(
                'title' => $this->language->get('text_contact'),
                'href'  => 'rt_contact'
            );
        }

        $data['home'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

        return $this->load->view('common/nav', $data);
    }
}
