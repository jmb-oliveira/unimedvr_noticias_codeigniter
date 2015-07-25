<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_logs extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function countLogs()
	{		
		return $this->db->count_all_results('unmd_logs');		
	}

	function getLogs($inicio, $offset)
	{		
		return $this->db->select(array('t1.descricao', 't1.created_on', 't2.login'))
						->from('unmd_logs t1')
						->join('unmd_usuarios t2', 't1.id_usuario = t2.id_usuario', 'inner')
						->order_by('t1.id_log', 'DESC')
						->limit($offset, $inicio)
						->get()						
						->result();
	}
}