<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para cargar las vistas.
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 28/07/2016
 */
class Dashboard extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('parser');
		$this->load->helper(['url', 'html']);
	}

	/**
	 * Carga las vistas desde un json.
	 * El json puede tener los siguientes atributos:
	 * 		title:titulo (string)
	 * 		script_tags:js (array)
	 * 		link_tags:css (array)
	 * 		meta:meta (array)(object)
	 * 		other:other (object)(object)
	 * 		zones:zonas (object)
	 * 		content: (object)
	 */
	public function load($json){
		$this->load->helper('tag');
		$module = empty($this->uri->segment(1)) ? 'home/' : $this->uri->segment(1);
		$jsonData = json_decode($this->load->view($module.'/json/'.$json, '', true), true);
		$headData['title'] = empty($jsonData['title']) ? APP_NAME : $jsonData['title'];
		$headData['head_content'] = '';

		//meta
		if(!empty($jsonData['meta'])){
			foreach ($jsonData['meta'] as $tag) {
				$headData['head_content'] .= meta($tag);
			}
		}

		//css
		if(!empty($jsonData['link_tags'])){
			foreach ($jsonData['link_tags'] as $tag) {
				$headData['head_content'] .= link_tag($tag);
			}
		}

		//js
		if(!empty($jsonData['script_tags'])){
			foreach ($jsonData['script_tags'] as $tag) {
				$headData['head_content'] .= script_tag($tag);
			}
		}

		//other
		if(!empty($jsonData['other'])){
			foreach ($jsonData['other'] as $tagname => $attributes) {
				$headData['head_content'] .= tag($tagname, $attributes);
			}
		}
		
		//content
		if(!empty($jsonData['content'])){
			$content = modules::run($jsonData['content']['module'].'/'.$jsonData['content']['controller'].'/'.$jsonData['content']['function'], $jsonData['content']['params']);
		}
		
		//zones
		if(!empty($jsonData['zones'])){
			foreach ($jsonData['zones'] as $zone => $coordenates) {
				$contentData[$zone] = modules::run($coordenates['module'].'/'.$coordenates['controller'].'/'.$coordenates['function'], $coordenates['params']);
			}
		}
		
		//sidebar options
		if(!empty($jsonData['sidebar_options'])){
			$contentData['sidebar'] = $this->parser->parse('dashboard/sidebar/sidebar-template.html', $jsonData, true, true);
			$headData['head_content'] .= link_tag('dashboard/assets/css/sidebar-view.css');
		}

		//login customization
		$this->load->helper('session');
		$headerData = get_login_data($this);
		
		//print
		echo $this->parser->parse('dashboard/head.html', $headData, true);
		echo $this->load->view('header.html', $headerData,  true);
		
		if(isset($content) && isset($contentData)){ echo "You cannot load both content and zones!"; return;}
		if(isset($content)){ echo $content;}
		else if(isset($contentData)){ echo $this->parser->parse('dashboard/sidebar/content.html', $contentData, true);}
		else{ echo "You must load some content!";}
		
		echo $this->load->view('footer.html', true);
	}
}