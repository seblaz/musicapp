<?php

/**
 * Recibe un string con el nombre de la etiqueta y un array asociativo con los atributos y los valores
 * de la etiqueta.
 */
function tag($tagname, $attributes){
	$tag = "<$tagname ";
	foreach ($attributes as $k => $v) {
		$tag .= $k.'="'.$v.'" ';
	}
	return $tag .= '></'.$tagname.'>';
}