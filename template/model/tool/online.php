<?php
/*DROP TABLE IF EXISTS `pt_unique_visitor`;
CREATE TABLE `pt_unique_visitor`(
	`date` DATE UNIQUE NOT NULL,
    `ip` TEXT NOT NULL,
    `url` TEXT NOT NULL,
    `referer` TEXT NOT NULL,
    `view` INT(11) DEFAULT 1 NOT NULL,
    `date_added` DATETIME NOT NULL,
    PRIMARY KEY(`date`)
)ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci;*/
class ModelToolOnline extends PT_Model
{
    public function addOnline($date, $ip, $url, $referer)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "unique_visitor` WHERE `date` = '" . $this->db->escape($date) . "'");
        if ($query->num_rows) {
            $newIp = $query->row['ip'];

            if (!preg_match('/' . $ip . '/', $newIp)) {
                $newIp .= ' ' . $ip;
            }

            $this->db->query("UPDATE `" . DB_PREFIX . "unique_visitor` SET `ip` = '" . $newIp . "', `view` = `view` + 1 WHERE `date` = '" . $this->db->escape($date) . "'");
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "unique_visitor` SET `date` = '" . $this->db->escape($date) . "', `ip` = '" . $this->db->escape($ip) . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', date_added = NOW()");
        }
    }

    public function getTotalOnlines()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unique_visitor");

        return $query->row['total'];
    }
}
