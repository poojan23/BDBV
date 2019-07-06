<?php
class ModelUserUser extends PT_Model
{
	public function addUser($data)
	{
		$this->db->query("INSERT INTO `" . DB_PREFIX . "member` SET username = '" . $this->db->escape((string) $data['username']) . "', member_group_id = '" . (int) $data['member_group_id'] . "', salt = '', password = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', firstname = '" . $this->db->escape((string) $data['firstname']) . "', lastname = '" . $this->db->escape((string) $data['lastname']) . "', email = '" . $this->db->escape((string) $data['email']) . "', image = '" . $this->db->escape((string) $data['image']) . "', status = '" . (int) $data['status'] . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editUser($member_id, $data)
	{
		$this->db->query("UPDATE `" . DB_PREFIX . "member` SET username = '" . $this->db->escape((string) $data['username']) . "',designation = '" . $this->db->escape((string) $data['designation']) . "', name = '" . $this->db->escape((string) $data['name']) . "', email = '" . $this->db->escape((string) $data['email']) . "', image = '" . $this->db->escape((string) $data['image']) . "', status = '" . (int) $data['status'] . "' WHERE member_id = '" . (int) $member_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "member` SET salt = '', password = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "' WHERE member_id = '" . (int) $member_id . "'");
		}
	}

	public function editPassword($member_id, $password)
	{
		$this->db->query("UPDATE `" . DB_PREFIX . "member` SET salt = '', password = '" . $this->db->escape(password_hash(html_entity_decode($password, ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', code = '' WHERE member_id = '" . (int) $member_id . "'");
	}

	public function editCode($email, $code)
	{
		$this->db->query("UPDATE `" . DB_PREFIX . "member` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteUser($member_id)
	{
		$this->db->query("DELETE FROM `" . DB_PREFIX . "member` WHERE member_id = '" . (int) $member_id . "'");
	}

	public function getUser($member_id)
	{
		$query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "member_group` ug WHERE ug.member_group_id = u.member_group_id) AS user_group FROM `" . DB_PREFIX . "member` u WHERE u.member_id = '" . (int) $member_id . "'");

		return $query->row;
	}

	public function getUserByUsername($username)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "member` WHERE username = '" . $this->db->escape($username) . "'");

		return $query->row;
	}

	public function getUserByEmail($email)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "member` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getUserByCode($code)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "member` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getUsers($data = array())
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "member`";

		$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
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

			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalUsers()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "member`");

		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($member_group_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "member` WHERE member_group_id = '" . (int) $member_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalUsersByEmail($email)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "member` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}
}
