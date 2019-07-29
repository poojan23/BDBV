<?php

class ModelCatalogEnquiry extends PT_Model
{
    public function deleteEnquiry($enquiry_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "enquiry WHERE enquiry_id = '" . (int) $enquiry_id . "'");
    }

    public function getEnquiry($enquiry_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "enquiry WHERE enquiry_id = '" . (int) $enquiry_id . "'");

        return $query->row;
    }

    public function getEnquiries($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "enquiry";

        $sort_data = array(
            'name',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalEnquiries()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "enquiry");

        return $query->row['total'];
    }

    public function readStatus($enquiry_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "enquiry SET status = 'read', date_modified = NOW() WHERE enquiry_id = '" . (int) $enquiry_id . "'");
    }

    public function updateStatus($enquiry_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "enquiry SET status = '" . $this->db->escape((string) $data['status']) . "', date_modified = NOW() WHERE enquiry_id = '" . (int) $enquiry_id . "'");
    }
}
