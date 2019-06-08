<?php

class ModelToolOnline extends PT_Model
{
    public function getTotalOnlines()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unique_visitor");

        return $query->row['total'];
    }

    public function getTotalOnlineByDate($date)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unique_visitor WHERE date = '" . $this->db->escape($date) . "'");

        return $query->row['total'];
    }

    public function getTotalOnlineByCurrentWeek()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unique_visitor WHERE date > DATE_SUB(NOW(), INTERVAL 1 WEEK)");

        return $query->row['total'];
    }

    public function getTotalOnlineByLastWeek()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unique_visitor WHERE YEARWEEK(date) = YEARWEEK(NOW() - INTERVAL 1 WEEK)");

        return $query->row['total'];
    }
}
