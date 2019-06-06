<?php

class ModelCatalogTeam extends PT_Model
{
    public function addTeam($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "team SET  name = '" . $this->db->escape((string)$data['name']) . "',designation = '" . $this->db->escape((string)$data['designation']) . "',description = '" . $this->db->escape((string)$data['description']) . "',sort_order = '" . (int)$data['sort_order'] . "', status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', date_modified = NOW(), date_added = NOW()");

        $team_id = $this->db->lastInsertId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "team SET image = '" . $this->db->escape((string)$data['image']) . "' WHERE team_id = '" . (int)$team_id . "'");
        }

        return $team_id;
    }

    public function editTeam($team_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "team SET  name = '" . $this->db->escape((string)$data['name']) . "',designation = '" . $this->db->escape((string)$data['designation']) . "',description = '" . $this->db->escape((string)$data['description']) . "',sort_order = '" . (int)$data['sort_order'] . "', status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', date_modified = NOW() WHERE team_id = '" . (int)$team_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "team SET image = '" . $this->db->escape((string)$data['image']) . "' WHERE team_id = '" . (int)$team_id . "'");
        }
    }

    public function deleteTeam($team_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "team WHERE team_id = '" . (int)$team_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'team_id=" . (int)$team_id . "'");

        $this->cache->delete('team');
    }

    public function getTeam($team_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "team WHERE team_id = '" . (int)$team_id . "'");

        return $query->row;
    }

    public function getTeams($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "team";

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

    public function getTeamDescriptions($team_id)
    {
        $team_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "team_description WHERE team_id = '" . (int)$team_id . "'");

        foreach ($query->rows as $result) {
            $team_description_data[$result['language_id']] = array(
                'title'             => $result['title'],
                'description'       => $result['description'],
                'meta_title'        => $result['meta_title'],
                'meta_description'  => $result['meta_description'],
                'meta_keyword'      => $result['meta_keyword']
            );
        }

        return $team_description_data;
    }

    public function getTeamSeoUrls($team_id)
    {
        $team_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'team_id=" . (int)$team_id . "'");

        foreach ($query->rows as $result) {
            $team_seo_url_data[$result['language_id']] = $result['keyword'];
        }

        return $team_seo_url_data;
    }

    public function getTotalTeams()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "team");

        return $query->row['total'];
    }
}
