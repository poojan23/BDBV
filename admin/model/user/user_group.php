<?php

class ModelUserUserGroup extends PT_Model
{
    public function addUserGroup($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "member_group SET name = '" . $this->db->escape((string) $data['name']) . "', description = '" . $this->db->escape((string) $data['description']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "'");

        return $this->db->lastInsertId();
    }

    public function editUserGroup($member_group_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "member_group SET name = '" . $this->db->escape((string) $data['name']) . "', description = '" . $this->db->escape((string) $data['description']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "' WHERE member_group_id = '" . (int) $member_group_id . "'");
    }

    public function deleteUserGroup($member_group_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "member_group WHERE member_group_id = '" . (int) $member_group_id . "'");
    }

    public function getUserGroup($member_group_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "member_group WHERE member_group_id = '" . (int) $member_group_id . "'");

        $member_group = array(
            'name'          => $query->row['name'],
            'description'   => $query->row['description'],
            'permission'    => json_decode($query->row['permission'], true)
        );

        return $member_group;
    }

    public function getUserGroups($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "member_group";

        $sql .= " ORDER BY name";

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) && isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUserGroups()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_group");

        return $query->row['total'];
    }

    public function addPermission($member_group_id, $type, $route)
    {
        $member_group_query = $this->db->query("SELECT DISTINT * FROM " . DB_PREFIX . "member_group WHERE member_group_id = '" . (int) $member_group_id . "'");

        if ($member_group_query->num_rows) {
            $data = json_decode($member_group_id->row['permission'], true);

            $data[$type][] = $route;

            $this->db->query("UPDATE " . DB_PREFIX . "member_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE member_group_id = '" . (int) $member_group_id . "'");
        }
    }

    public function removePermission($member_group_id, $type, $route)
    {
        $member_group_query = $this->db->query("SELECT DISTINT * FROM " . DB_PREFIX . "member_group WHERE member_group_id = '" . (int) $member_group_id . "'");

        if ($member_group_query->num_rows) {
            $data = json_decode($member_group_id->row['permission'], true);

            $data[$type][] = array_diff($data[$type], array($route));

            $this->db->query("UPDATE " . DB_PREFIX . "member_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE member_group_id = '" . (int) $member_group_id . "'");
        }
    }
}
