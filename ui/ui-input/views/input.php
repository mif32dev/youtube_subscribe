<?php
/**
 * Description: Fox ui-elements
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 *
 * @package ui_input_fox
 *
 * @since 0.2.1
 */

if ( ! empty( $label ) ) {
  echo '<label>' . $label . '</label>';
}
echo '<input' . $attributes;

if( ! empty( $datalist ) ) {
    echo 'list="' . $datalist_id . '"';
}
echo '>';

if( ! empty( $datalist ) ){
    echo '<datalist id="' . $datalist_id . '">';
    foreach( $datalist as $dataitem ) {
	echo '<option>' . $dataitem . '</option>';
    }
echo '</datalist>';
    
}
