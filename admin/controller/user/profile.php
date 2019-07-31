<?php

class ControllerUserProfile extends PT_Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('user/profile');

        $this->load->model('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $member_data = array_merge($this->request->post, array(
                'member_group_id'   => $this->member->getGroupId(),
                'status'            => 1
            ));

            $this->model_user_user->editUser($this->user->getId(), $member_data);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/profile', 'member_token=' . $this->session->data['member_token']));
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['firstname_err'] = $this->error['firstname'];
        } else {
            $data['firstname_err'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['lastname_err'] = $this->error['lastname'];
        } else {
            $data['lastname_err'] = '';
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

        if (isset($this->error['password'])) {
            $data['password_err'] = $this->error['password'];
        } else {
            $data['password_err'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['confirm_err'] = $this->error['confirm'];
        } else {
            $data['confirm_err'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('home/dashboard', 'member_token=' . $this->session->data['member_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('user/profile', 'member_token=' . $this->session->data['member_token'], true)
        );

        $data['action'] = $this->url->link('user/profile', 'member_token=' . $this->session->data['member_token'], true);

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $member_info = $this->model_user_user->getUser($this->user->getId());
        }

        if (!empty($member_info)) {
            $data['update'] = sprintf($this->language->get('text_password'), date($this->language->get('date_format_short'), strtotime($member_info['date_modified'])));
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($member_info)) {
            $data['firstname'] = $member_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($member_info)) {
            $data['lastname'] = $member_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['designation'])) {
            $data['designation'] = $this->request->post['designation'];
        } elseif (!empty($member_info)) {
            $data['designation'] = $member_info['designation'];
        } else {
            $data['designation'] = '';
        }

        if (isset($this->request->post['gender'])) {
            $data['gender'] = $this->request->post['gender'];
        } elseif (!empty($member_info)) {
            $data['gender'] = $member_info['gender'];
        } else {
            $data['gender'] = 'm';
        }

        if (isset($this->request->post['birthdate'])) {
            $data['birthdate'] = $this->request->post['birthdate'];
        } elseif (!empty($member_info)) {
            $data['birthdate'] = ($member_info['birthdate'] != '0000-00-00') ? $member_info['birthdate'] : '';
        } else {
            $data['birthdate'] = '';
        }

        if (isset($this->request->post['anniversary'])) {
            $data['anniversary'] = $this->request->post['anniversary'];
        } elseif (!empty($member_info)) {
            $data['anniversary'] = ($member_info['anniversary'] != '0000-00-00') ? $member_info['anniversary'] : '';
        } else {
            $data['anniversary'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($member_info)) {
            $data['email'] = $member_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($member_info)) {
            $data['telephone'] = $member_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } elseif (!empty($member_info)) {
            $data['fax'] = $member_info['fax'];
        } else {
            $data['fax'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        if (isset($this->request->post['avatar'])) {
            $data['avatar'] = $this->request->post['avatar'];
        } elseif (!empty($member_info['image'])) {
            $data['avatar'] = $member_info['image'];
        } else {
            $data['avatar'] = '';
        }

        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('profile.png', 164, 164);

        if (is_file(DIR_IMAGE . html_entity_decode($data['avatar'], ENT_QUOTES, 'UTF-8'))) {
            $data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['avatar'], ENT_QUOTES, 'UTF-8'), 164, 164);
        } else {
            $data['thumb'] = $data['placeholder'];
        }

        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/profile', $data));
    }

    protected function validateForm()
    {
        if (!$this->member->hasPermission('modify', 'user/profile')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $member_info = $this->model_user_user->getUserByEmail($this->request->post['email']);

        if ($member_info && ($this->member->getId() != $member_info['member_id'])) {
            $this->error['warning'] = $this->language->get('error_email_exists');
        }

        if ((utf8_strlen(trim($this->request->post['telephone'])) < 8) || (utf8_strlen(trim($this->request->post['telephone'])) > 20)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if ($this->request->post['password']) {
            if ((utf8_strlen(html_entity_decode($this->request->post['password'])) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'])) > 40)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        return !$this->error;
    }

    public function upload()
    {
        $this->load->language('user/profile');

        $json = array();

        # Check user has permission
        if (!$this->member->hasPermission('modify', 'user/profile')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (!empty($this->request->files['avatar']['name']) && is_file($this->request->files['avatar']['tmp_name'])) {
                # Sanitize the filename
                $filename = basename(html_entity_decode($this->request->files['avatar']['name'], ENT_QUOTES, 'UTF-8'));

                # Validate the filename length
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                # Allowed file extention types
                $allowed = array();

                $extention_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

                $filetypes = explode("\n", $extention_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                # Allowed file mime type
                $allowed = array();

                $mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

                $filetypes = explode("\n", $mime_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array($this->request->files['avatar']['type'], $allowed)) {
                    $json['error']  = $this->language->get('error_filetype');
                }

                # Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['avatar']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                # Return an upload error
                if ($this->request->files['avatar']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['avatar']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            move_uploaded_file($this->request->files['avatar']['tmp_name'], DIR_IMAGE . 'template/profile/' . $filename);

            $this->load->model('tool/image');

            $json['filename'] = $this->model_tool_image->resize('template/profile/' . $filename, 164, 164);
            $json['path'] = 'template/profile/' . $filename;

            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
