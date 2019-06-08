<?php

class ModelCatalogTestimonial extends PT_Model
{
    public function addTestimonial($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "testimonial SET  name = '" . $this->db->escape((string)$data['name']) . "', designation = '" . $this->db->escape((string)$data['designation']) . "', description = '" . $this->db->escape((string)$data['description']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', date_modified = NOW(), date_added = NOW()");

        $testimonial_id =  $this->db->lastInsertId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "testimonial SET image = '" . $this->db->escape((string)$data['image']) . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
        }

        return $testimonial_id;
    }

    public function editTestimonial($testimonial_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "testimonial SET  name = '" . $this->db->escape((string)$data['name']) . "',designation = '" . $this->db->escape((string)$data['designation']) . "',description = '" . $this->db->escape((string)$data['description']) . "',sort_order = '" . (int)$data['sort_order'] . "', status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', date_modified = NOW() WHERE testimonial_id = '" . (int)$testimonial_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "testimonial SET image = '" . $this->db->escape((string)$data['image']) . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
        }
    }

    public function deleteTestimonial($testimonial_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "testimonial WHERE testimonial_id = '" . (int)$testimonial_id . "'");
    }

    public function getTestimonial($testimonial_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "testimonial WHERE testimonial_id = '" . (int)$testimonial_id . "'");

        return $query->row;
    }

    public function getTestimonials($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "testimonial";

        $sort_data = array(
            'name',
            'sort_order'
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
}
