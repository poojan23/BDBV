<?php
error_reporting(0);
class ControllerSettingSetting extends PT_Controller {

    private $error;

    public function index() {
        $this->load->language('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        $this->load->model('tool/image');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (($this->request->post['config_image'] != $this->config->get('config_image')) && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
                $url = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
                $path_parse = parse_url($url, PHP_URL_PATH);
                $path_trim = trim($path_parse, '/');
                $path_parts = explode('/', $path_trim);
                $path = $path_parts[sizeof($path_parts) - 2] . '/' . end($path_parts);
                unlink(DIR_IMAGE . 'cache/' . $path);
                unlink(DIR_IMAGE . $this->config->get('config_image'));
            }

            $this->request->post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $this->model_setting_setting->editSetting('config', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/setting', 'member_token=' . $this->session->data['member_token'], true));
        }

        # Errors
        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['url'])) {
            $data['url_err'] = $this->error['url'];
        } else {
            $data['url_err'] = '';
        }

        if (isset($this->error['meta_title'])) {
            $data['meta_title_err'] = $this->error['meta_title'];
        } else {
            $data['meta_title_err'] = '';
        }

        if (isset($this->error['name'])) {
            $data['name_err'] = $this->error['name'];
        } else {
            $data['name_err'] = '';
        }

        if (isset($this->error['owner'])) {
            $data['owner_err'] = $this->error['owner'];
        } else {
            $data['owner_err'] = '';
        }

        if (isset($this->error['address'])) {
            $data['address_err'] = $this->error['address'];
        } else {
            $data['address_err'] = '';
        }

        if (isset($this->error['email'])) {
            $data['email_err'] = $this->error['email'];
        } else {
            $data['email_err'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['telephone_err'] = $this->error['telephone'];
        } else {
            $data['telephone_err'] = '';
        }

        if (isset($this->error['filename'])) {
            $data['filename_err'] = $this->error['filename'];
        } else {
            $data['filename_err'] = '';
        }

        # Breadcrumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('home/dashboard', 'member_token=' . $this->session->data['member_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/setting', 'member_token=' . $this->session->data['member_token'], true)
        ];

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('setting/setting', 'member_token=' . $this->session->data['member_token'], true);

        $data['member_token'] = $this->session->data['member_token'];

        # General
        if (isset($this->request->post['config_url'])) {
            $data['config_url'] = $this->request->post['config_url'];
        } else {
            $data['config_url'] = $this->config->get('config_url');
        }

        if (isset($this->request->post['config_ssl'])) {
            $data['config_ssl'] = $this->request->post['config_ssl'];
        } else {
            $data['config_ssl'] = $this->config->get('config_ssl');
        }

        if (isset($this->request->post['config_meta_title'])) {
            $data['config_meta_title'] = $this->request->post['config_meta_title'];
        } else {
            $data['config_meta_title'] = $this->config->get('config_meta_title');
        }

        if (isset($this->request->post['config_meta_description'])) {
            $data['config_meta_description'] = $this->request->post['config_meta_description'];
        } else {
            $data['config_meta_description'] = $this->config->get('config_meta_description');
        }

        if (isset($this->request->post['config_meta_keyword'])) {
            $data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
        } else {
            $data['config_meta_keyword'] = $this->config->get('config_meta_keyword');
        }

        # Store
        if (isset($this->request->post['config_name'])) {
            $data['config_name'] = $this->request->post['config_name'];
        } else {
            $data['config_name'] = $this->config->get('config_name');
        }

        if (isset($this->request->post['config_owner'])) {
            $data['config_owner'] = $this->request->post['config_owner'];
        } else {
            $data['config_owner'] = $this->config->get('config_owner');
        }

        if (isset($this->request->post['config_address'])) {
            $data['config_address'] = $this->request->post['config_address'];
        } else {
            $data['config_address'] = $this->config->get('config_address');
        }

        if (isset($this->request->post['config_geocode'])) {
            $data['config_geocode'] = $this->request->post['config_geocode'];
        } else {
            $data['config_geocode'] = $this->config->get('config_geocode');
        }

        if (isset($this->request->post['config_email'])) {
            $data['config_email'] = $this->request->post['config_email'];
        } else {
            $data['config_email'] = $this->config->get('config_email');
        }

        if (isset($this->request->post['config_telephone'])) {
            $data['config_telephone'] = $this->request->post['config_telephone'];
        } else {
            $data['config_telephone'] = $this->config->get('config_telephone');
        }

        if (isset($this->request->post['config_fax'])) {
            $data['config_fax'] = $this->request->post['config_fax'];
        } else {
            $data['config_fax'] = $this->config->get('config_fax');
        }

        if (isset($this->request->post['config_image'])) {
            $data['config_image'] = $this->request->post['config_image'];
        } else {
            $data['config_image'] = $this->config->get('config_image');
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
        } elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no-image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no-image.png', 100, 100);

        if (isset($this->request->post['config_open'])) {
            $data['config_open'] = $this->request->post['config_open'];
        } else {
            $data['config_open'] = $this->config->get('config_open');
        }

        if (isset($this->request->post['config_comment'])) {
            $data['config_comment'] = $this->request->post['config_comment'];
        } else {
            $data['config_comment'] = $this->config->get('config_comment');
        }
//        $this->load->model('localisation/location');
//
//        $data['locations'] = $this->model_localisation_location->getLocations();
////        
//        if (isset($this->request->post['config_location'])) {
//            $data['config_location'] = $this->request->post['config_location'];
//        } elseif ($this->config->get('config_location')) {
//            $data['config_location'] = $this->config->get('config_location');
//        } else {
//            $data['config_location'] = array();
//        }
        
        # Local
        if (isset($this->request->post['config_country_id'])) {
            $data['config_country_id'] = $this->request->post['config_country_id'];
        } else {
            $data['config_country_id'] = $this->config->get('config_country_id');
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        if (isset($this->request->post['config_zone_id'])) {
            $data['config_zone_id'] = $this->request->post['config_zone_id'];
        } else {
            $data['config_zone_id'] = $this->config->get('config_zone_id');
        }
        if (isset($this->request->post['config_timezone'])) {
            $data['config_timezone'] = $this->request->post['config_timezone'];
        } elseif ($this->config->has('config_timezone')) {
            $data['config_timezone'] = $this->config->get('config_timezone');
        } else {
            $data['config_timezone'] = 'UTC';
        }

        // Set Time Zone
        $data['timezones'] = array();

        $timestamp = time();

        $timezones = timezone_identifiers_list();

        foreach ($timezones as $timezone) {
            date_default_timezone_set($timezone);

            $hour = ' (' . date('P', $timestamp) . ')';

            $data['timezones'][] = array(
                'text' => $timezone . $hour,
                'value' => $timezone
            );
        }

//        date_default_timezone_set($this->config->get('config_timezone'));

        if (isset($this->request->post['config_language'])) {
            $data['config_language'] = $this->request->post['config_language'];
        } else {
            $data['config_language'] = $this->config->get('config_language');
        }

        $this->load->model('localisation/zone');

        $zone_info = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));

        $data['zone'] = $zone_info['name'];

        $country_info = $this->model_localisation_country->getCountry($zone_info['country_id']);

        $data['country'] = $country_info['name'];

        if (isset($this->request->post['config_language'])) {
            $data['config_language'] = $this->request->post['config_language'];
        } else {
            $data['config_language'] = $this->config->get('config_language');
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['config_admin_language'])) {
            $data['config_admin_language'] = $this->request->post['config_admin_language'];
        } else {
            $data['config_admin_language'] = $this->config->get('config_admin_language');
        }

        # Options
//        if (isset($this->request->post['config_enquiry_prefix'])) {
//            $data['config_enquiry_prefix'] = $this->request->post['config_enquiry_prefix'];
//        } elseif ($this->config->get('config_enquiry_prefix')) {
//            $data['config_enquiry_prefix'] = $this->config->get('config_enquiry_prefix');
//        } else {
//            $data['config_enquiry_prefix'] = 'E/' . date('y') . '/';
//        }
//
//        if (isset($this->request->post['config_enquiry_web_prefix'])) {
//            $data['config_enquiry_web_prefix'] = $this->request->post['config_enquiry_web_prefix'];
//        } elseif ($this->config->get('config_enquiry_web_prefix')) {
//            $data['config_enquiry_web_prefix'] = $this->config->get('config_enquiry_web_prefix');
//        } else {
//            $data['config_enquiry_web_prefix'] = 'Web/E/' . date('y') . '/';
//        }

        # Images
        if (isset($this->request->post['config_logo'])) {
            $data['config_logo'] = $this->request->post['config_logo'];
        } else {
            $data['config_logo'] = $this->config->get('config_logo');
        }

        if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
            $data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
        } elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
        } else {
            $data['logo'] = $this->model_tool_image->resize('no-image.png', 100, 100);
        }

        if (isset($this->request->post['config_icon'])) {
            $data['config_icon'] = $this->request->post['config_icon'];
        } else {
            $data['config_icon'] = $this->config->get('config_icon');
        }

        if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
            $data['icon'] = $this->model_tool_image->resize($this->request->post['config_icon'], 100, 100);
        } elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
        } else {
            $data['icon'] = $this->model_tool_image->resize('no-image.png', 100, 100);
        }

        if (isset($this->request->post['config_mobile_logo'])) {
            $data['config_mobile_logo'] = $this->request->post['config_mobile_logo'];
        } else {
            $data['config_mobile_logo'] = $this->config->get('config_mobile_logo');
        }

        if (isset($this->request->post['config_mobile_logo']) && is_file(DIR_IMAGE . $this->request->post['config_mobile_logo'])) {
            $data['mobile_logo'] = $this->model_tool_image->resize($this->request->post['config_mobile_logo'], 100, 100);
        } elseif ($this->config->get('config_mobile_logo') && is_file(DIR_IMAGE . $this->config->get('config_mobile_logo'))) {
            $data['mobile_logo'] = $this->model_tool_image->resize($this->config->get('config_mobile_logo'), 100, 100);
        } else {
            $data['mobile_logo'] = $this->model_tool_image->resize('no-image.png', 100, 100);
        }

        # Mail
        if (isset($this->request->post['config_mail_engine'])) {
            $data['config_mail_engine'] = $this->request->post['config_mail_engine'];
        } else {
            $data['config_mail_engine'] = $this->config->get('config_mail_engine');
        }

        if (isset($this->request->post['config_mail_parameter'])) {
            $data['config_mail_parameter'] = $this->request->post['config_mail_parameter'];
        } else {
            $data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
        }

        if (isset($this->request->post['config_mail_smtp_hostname'])) {
            $data['config_mail_smtp_hostname'] = $this->request->post['config_mail_smtp_hostname'];
        } else {
            $data['config_mail_smtp_hostname'] = $this->config->get('config_mail_smtp_hostname');
        }

        if (isset($this->request->post['config_mail_smtp_username'])) {
            $data['config_mail_smtp_username'] = $this->request->post['config_mail_smtp_username'];
        } else {
            $data['config_mail_smtp_username'] = $this->config->get('config_mail_smtp_username');
        }

        if (isset($this->request->post['config_mail_smtp_password'])) {
            $data['config_mail_smtp_password'] = $this->request->post['config_mail_smtp_password'];
        } else {
            $data['config_mail_smtp_password'] = $this->config->get('config_mail_smtp_password');
        }

        if (isset($this->request->post['config_mail_smtp_port'])) {
            $data['config_mail_smtp_port'] = $this->request->post['config_mail_smtp_port'];
        } elseif ($this->config->has('config_mail_smtp_port')) {
            $data['config_mail_smtp_port'] = $this->config->get('config_mail_smtp_port');
        } else {
            $data['config_mail_smtp_port'] = 25;
        }

        if (isset($this->request->post['config_mail_smtp_timeout'])) {
            $data['config_mail_smtp_timeout'] = $this->request->post['config_mail_smtp_timeout'];
        } elseif ($this->config->has('config_mail_smtp_timeout')) {
            $data['config_mail_smtp_timeout'] = $this->config->get('config_mail_smtp_timeout');
        } else {
            $data['config_mail_smtp_timeout'] = 5;
        }

        if (isset($this->request->post['config_mail_alert'])) {
            $data['config_mail_alert'] = $this->request->post['config_mail_alert'];
        } elseif ($this->config->has('config_mail_alert')) {
            $data['config_mail_alert'] = $this->config->get('config_mail_alert');
        } else {
            $data['config_mail_alert'] = array();
        }

        $data['mail_alerts'] = array();

        $data['mail_alerts'][] = array(
            'text' => $this->language->get('text_mail_account'),
            'value' => 'account'
        );

        $data['mail_alerts'][] = array(
            'text' => $this->language->get('text_mail_affiliate'),
            'value' => 'affiliate'
        );

        $data['mail_alerts'][] = array(
            'text' => $this->language->get('text_mail_order'),
            'value' => 'order'
        );

        $data['mail_alerts'][] = array(
            'text' => $this->language->get('text_mail_review'),
            'value' => 'review'
        );

        if (isset($this->request->post['config_mail_alert_email'])) {
            $data['config_mail_alert_email'] = $this->request->post['config_mail_alert_email'];
        } else {
            $data['config_mail_alert_email'] = $this->config->get('config_mail_alert_email');
        }
//
//        if (isset($this->request->post['config_shared'])) {
//            $data['config_shared'] = $this->request->post['config_shared'];
//        } else {
//            $data['config_shared'] = $this->config->get('config_shared');
//        }
//
//        if (isset($this->request->post['config_robots'])) {
//            $data['config_robots'] = $this->request->post['config_robots'];
//        } else {
//            $data['config_robots'] = $this->config->get('config_robots');
//        }
//
//        if (isset($this->request->post['config_seo_url'])) {
//            $data['config_seo_url'] = $this->request->post['config_seo_url'];
//        } else {
//            $data['config_seo_url'] = $this->config->get('config_seo_url');
//        }
//
        if (isset($this->request->post['config_file_max_size'])) {
            $data['config_file_max_size'] = $this->request->post['config_file_max_size'];
        } elseif ($this->config->get('config_file_max_size')) {
            $data['config_file_max_size'] = $this->config->get('config_file_max_size');
        } else {
            $data['config_file_max_size'] = 300000;
        }
//
        if (isset($this->request->post['config_file_ext_allowed'])) {
            $data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
        } else {
            $data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
        }
//
        if (isset($this->request->post['config_file_mime_allowed'])) {
            $data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
        } else {
            $data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
        }
//
//        if (isset($this->request->post['config_maintenance'])) {
//            $data['config_maintenance'] = $this->request->post['config_maintenance'];
//        } else {
//            $data['config_maintenance'] = $this->config->get('config_maintenance');
//        }
//
//        if (isset($this->request->post['config_password'])) {
//            $data['config_password'] = $this->request->post['config_password'];
//        } else {
//            $data['config_password'] = $this->config->get('config_password');
//        }
//
//        if (isset($this->request->post['config_encryption'])) {
//            $data['config_encryption'] = $this->request->post['config_encryption'];
//        } else {
//            $data['config_encryption'] = $this->config->get('config_encryption');
//        }
//
//        if (isset($this->request->post['config_compression'])) {
//            $data['config_compression'] = $this->request->post['config_compression'];
//        } else {
//            $data['config_compression'] = $this->config->get('config_compression');
//        }
//
//        if (isset($this->request->post['config_error_display'])) {
//            $data['config_error_display'] = $this->request->post['config_error_display'];
//        } else {
//            $data['config_error_display'] = $this->config->get('config_error_display');
//        }
//
//        if (isset($this->request->post['config_error_log'])) {
//            $data['config_error_log'] = $this->request->post['config_error_log'];
//        } else {
//            $data['config_error_log'] = $this->config->get('config_error_log');
//        }
//
//        if (isset($this->request->post['config_error_filename'])) {
//            $data['config_error_filename'] = $this->request->post['config_error_filename'];
//        } else {
//            $data['config_error_filename'] = $this->config->get('config_error_filename');
//        }
        $this->load->model('catalog/information');

        $data['informations'] = $this->model_catalog_information->getInformations();
        # Server
        if (isset($this->request->post['config_maintenance'])) {
            $data['config_maintenance'] = $this->request->post['config_maintenance'];
        } else {
            $data['config_maintenance'] = $this->config->get('config_maintenance');
        }

        if (isset($this->request->post['config_seo_url'])) {
            $data['config_seo_url'] = $this->request->post['config_seo_url'];
        } else {
            $data['config_seo_url'] = $this->config->get('config_seo_url');
        }

        if (isset($this->request->post['config_robots'])) {
            $data['config_robots'] = $this->request->post['config_robots'];
        } else {
            $data['config_robots'] = $this->config->get('config_robots');
        }

//        if (isset($this->request->post['config_secure'])) {
//            $data['config_secure'] = $this->request->post['config_secure'];
//        } else {
//            $data['config_secure'] = $this->config->get('config_secure');
//        }

//        if (isset($this->request->post['config_password'])) {
//            $data['config_password'] = $this->request->post['config_password'];
//        } else {
//            $data['config_password'] = $this->config->get('config_password');
//        }

//        if (isset($this->request->post['config_file_max_size'])) {
//            $data['config_file_max_size'] = $this->request->post['config_file_max_size'];
//        } elseif ($this->config->get('config_file_max_size')) {
//            $data['config_file_max_size'] = $this->config->get('config_file_max_size');
//        } else {
//            $data['config_file_max_size'] = 300000;
//        }

//        if (isset($this->request->post['config_file_ext_allowed'])) {
//            $data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
//        } else {
//            $data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
//        }
//
//        if (isset($this->request->post['config_file_mime_allowed'])) {
//            $data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
//        } else {
//            $data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
//        }

//        if (isset($this->request->post['config_error_display'])) {
//            $data['config_error_display'] = $this->request->post['config_error_display'];
//        } else {
//            $data['config_error_display'] = $this->config->get('config_error_display');
//        }

//        if (isset($this->request->post['config_error_log'])) {
//            $data['config_error_log'] = $this->request->post['config_error_log'];
//        } else {
//            $data['config_error_log'] = $this->config->get('config_error_log');
//        }

//        if (isset($this->request->post['config_error_filename'])) {
//            $data['config_error_filename'] = $this->request->post['config_error_filename'];
//        } else {
//            $data['config_error_filename'] = $this->config->get('config_error_filename');
//        }
        $data['cancel'] = $this->url->link('setting/setting', 'member_token=' . $this->session->data['member_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/setting', $data));
    }

    protected function validate() {
        if (!$this->member->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['config_meta_title']) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }

        if (!$this->request->post['config_name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
            $this->error['owner'] = $this->language->get('error_owner');
        }

        if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if ((utf8_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

//        if (!$this->request->post['config_error_filename']) {
//            $this->error['filename'] = $this->language->get('error_log_required');
//        } elseif (preg_match('/\.\.[\/\\\]?/', $this->request->post['config_error_filename'])) {
//            $this->error['filename'] = $this->language->get('error_log_invalid');
//        } elseif (substr($this->request->post['config_error_filename'], strrpos($this->request->post['config_error_filename'], '.')) != '.log') {
//            $this->error['filename'] = $this->language->get('error_log_extension');
//        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

}
