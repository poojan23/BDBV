<?php

class ModelToolNotification extends PT_Model
{
    public function getEnquiries($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "enquiry WHERE";

        if (isset($data['status']) && ($data['status'] != 'unread')) {
            $sql .= " status = '" . $data['status'] . "'";
        } else {
            $sql .= " status = 'unread'";
        }

        $sort_data = array(
            'name',
            'status'
        );

        if (isset($data['sort']) && (in_array($data['sort'], $sort_data))) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

    public function getTotalEnquiries()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "enquiry WHERE status = 'unread'");

        return $query->row['total'];
    }
}
