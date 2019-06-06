<?php

class ModelCatalogService extends PT_Model
{
    public function getServices($start = 0, $limit = 10)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service s LEFT JOIN " . DB_PREFIX . "service_description sd ON (s.service_id = sd.service_id) WHERE status = '1' ORDER BY s.sort_order ASC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }
}
