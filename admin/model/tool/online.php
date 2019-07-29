<?php

class ModelToolOnline extends PT_Model
{
    public function getTotalOnlines()
    {
        $query = $this->db->query("SELECT SUM(`view`) AS total FROM " . DB_PREFIX . "unique_visitor");

        if ($query->num_rows) {
            return $query->row['total'];
        } else {
            return '0';
        }
    }

    public function getTotalOnlineByDate($date)
    {
        $query = $this->db->query("SELECT `view` AS total FROM " . DB_PREFIX . "unique_visitor WHERE date = '" . $this->db->escape($date) . "'");

        if ($query->num_rows) {
            return $query->row['total'];
        } else {
            return '0';
        }
    }

    public function getTotalOnlineByCurrentWeek()
    {
        $query = $this->db->query("SELECT SUM(`view`) AS total FROM " . DB_PREFIX . "unique_visitor WHERE YEARWEEK(date) = YEARWEEK(NOW())");

        if ($query->num_rows) {
            return $query->row['total'];
        } else {
            return '0';
        }
    }

    public function getTotalOnlineByLastWeek()
    {
        $query = $this->db->query("SELECT SUM(`view`) AS total FROM " . DB_PREFIX . "unique_visitor WHERE YEARWEEK(date) = YEARWEEK(NOW() - INTERVAL 1 WEEK)");

        if ($query->num_rows) {
            return $query->row['total'];
        } else {
            return '0';
        }
    }
}
