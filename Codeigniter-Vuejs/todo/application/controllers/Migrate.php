<?php

class Migrate extends CI_Controller
{
	public function index()
	{
		try {
			$this->input->is_cli_request()
			or exit("Execute via command line: php index.php migrate");

			// load migration library
			$this->load->library('migration');

			if ($this->migration->latest() === FALSE) {
				show_error($this->migration->error_string());
			} else {
				echo 'Migrations ran successfully!';
			}
		} catch (\Exception $ex) {
			echo $ex->getMessage();
		}
	}
}
