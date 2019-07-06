<?php

class ModelToolNotification extends PT_Model
{
    # Enquiries
    public function getEnquiries($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "enquiry";

        if (isset($data['status']) && ($data['status'] != 'unread')) {
            $sql .= " WHERE status = '" . $data['status'] . "'";
        }

        $sort_data = array(
            'enquiry_id',
            'name',
            'status'
        );

        if (isset($data['sort']) && (in_array($data['sort'], $sort_data))) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY enquiry_id";
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
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "enquiry");

        return $query->row['total'];
    }

    public function getTotalUnreadEnquiries()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "enquiry WHERE status = 'unread'");

        return $query->row['total'];
    }

    # Testimonials
    public function getTestimonials($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "testimonial";

        if (isset($data['status']) && ($data['status'] != 'unread')) {
            $sql .= " WHERE status = '" . $data['status'] . "'";
        }

        $sort_data = array(
            'testimonial_id',
            'name',
            'sort_order'
        );

        if (isset($data['sort']) && (in_array($data['sort'], $sort_data))) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY testimonial_id";
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

    public function getTotalTestimonials()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "testimonial");

        return $query->row['total'];
    }

    public function getTotalUnreadTestimonials()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "testimonial WHERE status = '0'");

        return $query->row['total'];
    }
}
