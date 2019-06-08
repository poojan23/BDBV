<?php

class ModelCatalogEnquiry extends PT_Model
{
    public function addEnquiry($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "enquiry SET name = '" . $this->db->escape((string)$data['name']) . "', email = '" . $this->db->escape((string)$data['email']) . "', message = '" . $this->db->escape((string)$data['message']) . "', date_added = NOW()");

        return $this->db->lastInsertId();
    }
}
