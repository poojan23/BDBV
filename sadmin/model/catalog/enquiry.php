<?php

class ModelCatalogEnquiry extends PT_Model {

    public function getEnquiries() {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "enquiry");

        return $query->rows;
    }

}
