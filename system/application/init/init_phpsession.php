<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! class_exists('PhpSession'))
{
     require_once(APPPATH.'libraries/phpsession'.EXT);
}

$obj =& get_instance();
$obj->phpsession = new PhpSession();
$obj->ci_is_loaded[] = 'phpsession';

?>