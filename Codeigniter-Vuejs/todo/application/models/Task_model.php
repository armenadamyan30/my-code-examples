<?php

class Task_model extends CI_Model
{
	public $table = 'tasks';

	public function showAll()
	{
		$this->db->from($this->table);
		$this->db->order_by("createdAt", "DESC");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function addTask($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function updateTask($id, $field)
	{
		$this->db->where('id', $id);
		$this->db->update($this->table, $field);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}

	}

	public function deleteTask($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function searchTask($match, $id = NULL)
	{
		$this->db->from($this->table);
		$this->db->select('*');
		$this->db->order_by("createdAt", "DESC");
		$this->db->where('name', trim($match));
		if ($id) {
			$this->db->where('id !=', $id);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}
