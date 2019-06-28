<?php

class ModelCatalogTestimonial extends PT_Model
{
    public function addTestimonial($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "testimonial SET name = '" . $this->db->escape((string)$data['name']) . "', company = '" . $this->db->escape((string)$data['company']) . "', designation = '" . $this->db->escape((string)$data['designation']) . "', description = '" . $this->db->escape((string)$data['description']) . "', sort_order = '0', status = '0', date_modified = NOW(), date_added = NOW()");

        $testimonial_id = $this->db->lastInsertId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "testimonial SET image = '" . $this->db->escape((string)$data['image']) . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
        }

        return $testimonial_id;
    }

    public function getTestimonials($start = 0, $limit = 20)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "testimonial WHERE status = ' 1' AND date_added <= NOW() ORDER BY sort_order LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }
}
