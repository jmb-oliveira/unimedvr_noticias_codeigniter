<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_security extends CI_Security {

	public function csrf_show_error()
	{
		show_error('Voc&ecirc; ficou muito tempo inativo no formul&aacute;rio, por favor, atualize a p&aacute;gina e tente novamente.', 500, 'O formul&aacute;rio expirou!');
	}
}