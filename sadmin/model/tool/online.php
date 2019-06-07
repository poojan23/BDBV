<?php

class ModelToolOnline extends PT_Model
{
    public function getTotalOnlines()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unique_visitor");

        return $query->row['total'];
    }

    public function getTotalOnlineByCurrentDay($date)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unique_visitor WHERE date = '" . $this->db->escape($date) . "' GROUP BY date ORDER BY date ASC");

        return $query->row;
    }
}
