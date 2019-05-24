<?php

class ControllerUserProfile extends PT_Controller
{
    private $error = array();

    public function index() {
        $this->load->language('user/profile');

        $this->load->model('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        if(($this->request->server['REQUEST_METHOD'] == 'POST')) {
//            print_r($this->request->post);exit;
            $member_data = array_merge($this->request->post, array(
                'user_id'   => $this->user->getGroupId(),
                'status'            => 1
            ));
            
            $this->model_user_user->editUser($this->user->getId(), $member_data);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('user/profile', 'user_token=' . $this->session->data['user_token'], true));
        }

        if(isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if(isset($this->error['warning'])) {
            $data['warning_err'] = $this->error['warning'];
        } else {
            $data['warning_err'] = '';
        }

        if(isset($this->error['name'])) {
            $data['firstname_err'] = $this->error['name'];
        } else {
            $data['firstname_err'] = '';
        }

        if(isset($this->error['lastname'])) {
            $data['lastname_err'] = $this->error['lastname'];
        } else {
            $data['lastname_err'] = '';
        }
       
        if(isset($this->error['email'])) {
            $data['email_err'] = $this->error['email'];
        } else {
            $data['email_err'] = '';
        }

        if(isset($this->error['telephone'])) {
            $data['telephone_err'] = $this->error['telephone'];
        } else {
            $data['telephone_err'] = '';
        }

        if(isset($this->error['password'])) {
            $data['password_err'] = $this->error['password'];
        } else {
            $data['password_err'] = '';
        }

        if(isset($this->error['confirm'])) {
            $data['confirm_err'] = $this->error['confirm'];
        } else {
            $data['confirm_err'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('home/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('user/profile', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('user/profile', 'user_token=' . $this->session->data['user_token'], true);
        
        if($this->request->server['REQUEST_METHOD'] != 'POST') {
            $user_info = $this->model_user_user->getUser($this->user->getId());
        }
//        print_r($user_info);exit;
        if(!empty($user_info)) {
            $data['update'] = sprintf($this->language->get('text_password'), date($this->language->get('date_format_short'), strtotime($user_info['date_modified'])));
        }
        
        if(isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif(!empty($user_info)) {
            $data['name'] = $user_info['name'];
        } else {
            $data['name'] = '';
        }

        if(isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif(!empty($user_info)) {
            $data['lastname'] = $user_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if(isset($this->request->post['designation'])) {
            $data['designation'] = $this->request->post['designation'];
        } elseif(!empty($user_info)) {
            $data['designation'] = $user_info['designation'];
        } else {
            $data['designation'] = '';
        }

        if(isset($this->request->post['gender'])) {
            $data['gender'] = $this->request->post['gender'];
        } elseif(!empty($user_info)) {
            $data['gender'] = $user_info['gender'];
        } else {
            $data['gender'] = 'm';
        }

        if(isset($this->request->post['birthdate'])) {
            $data['birthdate'] = $this->request->post['birthdate'];
        } elseif(!empty($user_info)) {
            $data['birthdate'] = ($user_info['birthdate'] != '0000-00-00') ? $user_info['birthdate'] : '';
        } else {
            $data['birthdate'] = '';
        }

        if(isset($this->request->post['anniversary'])) {
            $data['anniversary'] = $this->request->post['anniversary'];
        } elseif(!empty($user_info)) {
            $data['anniversary'] = ($user_info['anniversary'] != '0000-00-00') ? $user_info['anniversary'] : '';
        } else {
            $data['anniversary'] = '';
        }

        if(isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif(!empty($user_info)) {
            $data['email'] = $user_info['email'];
        } else {
            $data['email'] = '';
        }

        if(isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif(!empty($user_info)) {
            $data['telephone'] = $user_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if(isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } elseif(!empty($user_info)) {
            $data['fax'] = $user_info['fax'];
        } else {
            $data['fax'] = '';
        }

        if(isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if(isset($this->request->post['confirm'])) {
            $data['confirm'] = $this->request->post['confirm'];
        } else {
            $data['confirm'] = '';
        }

        if(isset($this->request->post['avatar'])) {
            $data['avatar'] = $this->request->post['avatar'];
        } elseif(!empty($user_info['image'])) {
            $data['avatar'] = $user_info['image'];
        } else {
            $data['avatar'] = '';
        }
       
        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('profile.png', 164, 164);

        if(is_file(DIR_IMAGE . html_entity_decode($data['avatar'], ENT_QUOTES, 'UTF-8'))) {
            $data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['avatar'], ENT_QUOTES, 'UTF-8'), 164, 164);
        } else {
            $data['thumb'] = $data['placeholder'];
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['nav'] = $this->load->controller('common/nav');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/profile', $data));
    }

    protected function validateForm() {
        if(!$this->user->hasPermission('modify', 'user/profile')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
            $this->error['name'] = $this->language->get('error_firstname');
        }

        if((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
        }
        
        $user_info = $this->model_user_user->getUserByEmail($this->request->post['email']);

        if($user_info && ($this->user->getId() != $user_info['member_id'])) {
            $this->error['warning'] = $this->language->get('error_email_exists');
        }

        if((utf8_strlen(trim($this->request->post['telephone'])) < 8) || (utf8_strlen(trim($this->request->post['telephone'])) > 20)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if($this->request->post['password']) {
            if((utf8_strlen(html_entity_decode($this->request->post['password'])) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'])) > 40)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        return !$this->error;
    }

    public function upload() {
        $this->load->language('user/profile');

        $json = array();

        //$dir = preg_replace('/[^a-zA-Z0-9_\/]/', '_', $this->request->get['dir']);

        # Check user has permission
        if(!$this->user->hasPermission('modify', 'user/profile')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if(!$json) {
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

                foreach($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if(!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                # Allowed file mime type
                $allowed = array();

                $mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

                $filetypes = explode("\n", $mime_allowed);

                foreach($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if(!in_array($this->request->files['avatar']['type'], $allowed)) {
                    $json['error']  = $this->language->get('error_filetype');
                }

                # Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['avatar']['tmp_name']);

                if(preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                # Return an upload error
                if($this->request->files['avatar']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['avatar']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if(!$json) {
            //$file = $filename . '.' . token(32);

            move_uploaded_file($this->request->files['avatar']['tmp_name'], DIR_IMAGE . 'profile/' . $filename);

            $this->load->model('tool/image');

            $json['filename'] = $this->model_tool_image->resize('profile/' . $filename, 164, 164);
            $json['path'] = 'profile/' . $filename;

            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
