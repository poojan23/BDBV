<?php

class ModelUserUser extends PT_Controller
{
    public function addUser($data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET email = '" . $this->db->escape((string)$data['email']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '', password = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', name = '" . $this->db->escape((string)$data['name']) . "', image = '" . $this->db->escape((string)$data['image']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

        return $this->db->lastInsertId();
    }

    public function editUser($user_id, $data)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET email = '" . $this->db->escape((string)$data['email']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', name = '" . $this->db->escape((string)$data['name']) . "', image = '" . $this->db->escape((string)$data['image']) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

        if ($data['password']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '', password = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', date_modified = NOW() WHERE user_id = '" . (int)$user_id . "'");
        }
    }

    public function editPassword($user_id, $password)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '', password = '" . $this->db->escape(password_hash(html_entity_decode($password, ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', code = '', date_modified = NOW() WHERE user_id = '" . (int)$user_id . "'");
    }

    public function deleteUser($user_id)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET deleted = '1', status = '0' WHERE user_id = '" . (int)$user_id . "'");
    }

    public function getUser($user_id)
    {
        $query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

        return $query->row;
    }

    public function getUserByEmail($email)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getUsers($data = array())
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "user` WHERE deleted = '0'";

        $sort_data = array(
            'email',
            'status',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY email";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUsersByGroupId($user_group_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

        return $query->row['total'];
    }
}
