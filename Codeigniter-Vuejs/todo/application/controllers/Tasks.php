<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class User
 * @property Task_model $Task_model
 */
class Tasks extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Task_model');

	}

	public function index()
	{
		$this->load->view('template/header');
		$this->load->view('tasks/index');
		$this->load->view('template/footer');
	}

	public function showAll()
	{
		$result['tasks'] = array();
		$query = $this->Task_model->showAll();
		if ($query) {
			$result['tasks'] = $this->Task_model->showAll();
		}
		print_r(json_encode($result));
		exit;
	}

	public function addTask()
	{
		$config = array(
			array('field' => 'task_name',
				'label' => 'Task Name',
				'rules' => 'trim|required'
			)
		);
		$result = array();
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() === FALSE) {
			$result['error'] = true;
			$result['msg'] = array(
				'name' => form_error('task_name')
			);
		} else {
			$data = array(
				'name' => $this->input->post('task_name')

			);
			if ($this->Task_model->addTask($data)) {
				$result['error'] = false;
				$result['msg'] = 'The task added successfully';
			}
		}
		print_r(json_encode($result));
		exit;
	}

	public function updateTask()
	{
		$config = array(
			array('field' => 'id',
				'label' => 'Id',
				'rules' => 'trim|required'
			),
			array('field' => 'task_name',
				'label' => 'Task Name',
				'rules' => 'trim|required'
			)
		);
		$result = array();
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() === FALSE) {
			$result['error'] = true;
			$result['msg'] = array(
				'name' => form_error('task_name')
			);

		} else {
			$id = $this->input->post('id');
			$data = array(
				'name' => $this->input->post('task_name')
			);
			if ($this->Task_model->updateTask($id, $data)) {
				$result['error'] = false;
				$result['success'] = 'The task updated successfully';
			}

		}
		print_r(json_encode($result));
		exit;
	}

	public function deleteTask()
	{
		$msg = array();
		$id = $this->input->post('id');
		if ($this->Task_model->deleteTask($id)) {
			$msg['error'] = false;
			$msg['msg'] = 'The task deleted successfully';
		} else {
			$msg['error'] = true;
		}
		print_r(json_encode($msg));
		exit;

	}

	public function searchTask()
	{
		$result['tasks'] = array();

		$value = $this->input->post('task_name');
		$id = $this->input->post('id');
		$query = $this->Task_model->searchTask($value, $id);
		if ($query) {
			$result['tasks'] = $query;
		}

		print_r(json_encode($result));
		exit;

	}
}
