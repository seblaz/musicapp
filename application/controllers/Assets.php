<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * ASSETS Controller
 * This file allows you to  access assets from within your modules directory
 */
class Assets extends MX_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		
		if (count($this->uri->segments) == 2) {
			show_error("Serving assets for: ".APPPATH.'modules/'.implode('/', $this->uri->segments));
			exit;
		}

		$file       = APPPATH.'modules/'.implode('/', $this->uri->segments);
		$path_parts = pathinfo($file);

		if (is_file($file)) {

			$file_type = strtolower($path_parts['extension']);

			switch ($file_type) {
				case 'css':
					header('Content-type: text/css');
					break;

				case 'js':
					header('Content-type: text/javascript');
					break;

				case 'json':
					header('Content-type: application/json;charset=UTF-8');
					break;

				case 'xml':
					header('Content-type: text/xml');
					break;

				case 'pdf':
					header('Content-type: application/pdf');
					break;

				case 'jpg' || 'jpeg' || 'png' || 'gif':
					header('Content-type: image/'.$file_type);
					break;
			}
			readfile($file);
		} else {
			show_error("Asset not found: $file");
		}
		exit;
	}
}