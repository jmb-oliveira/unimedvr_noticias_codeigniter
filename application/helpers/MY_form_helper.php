<?php

/**
 * Remover qualquer HTML
 * 
 * @author Jhonatan Müller
 * @param string $str
 * @return string
 */
function sanitizehtml($str)
{
	$str = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
	$str = strip_tags($str);
	return $str;
}


/**
 * Deixa apenas os caractéres numéricos da string
 *
 * @author Jhonatan Müller
 * @param string $str
 * @return string
 */
function strtonumeric($str)
{
	return preg_replace('/[^0-9]/', '', $str);
}

/**
 * Substitui vírgula por ponto e deixa apenas os caractéres numéricos e pontos da string
 *
 * @author Jhonatan Müller
 * @param string $str
 * @return string
 */
function strtomoney($str)
{
	if(is_null($str))
		return NULL;
		
	$str = str_replace(',', '.', $str);
	return preg_replace('/[^0-9.]/', '', $str);
}

/**
 * Verifica se a data passada é válida, tanto em formato quanto em validade gregoriana
 *
 * @author Jhonatan Müller
 * @param string $str
 * @return string
 */
function validDate($str)
{
	if(!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $str))
		return FALSE;
	
	$str = explode('/', $str);
	return checkdate($str[1], $str[0], $str[2]);
}

/**
 * Verifica se o telefone/celular COM DDD passado é válido
 *
 * @author Jhonatan Müller
 * @param string $str
 * @param string modo tel, cel ou any
 * @return string
 */
function validContato($str, $modo = 'any')
{
	$str = strtonumeric($str);
	
	if($modo == 'tel')
		return (strlen($str) == 10);
	elseif($modo == 'cel')
		return (strlen($str) == 11);
	else
		return (strlen($str) == 10 || strlen($str) == 11);
}

/**
 * Transforma o formato dd/mm/yyyy em yyyy-mm-dd para o banco de dados
 * 
 * @author Jhonatan Müller
 * @param string $data
 * @return string
 */
function adjustDateFormat($data)
{
	$data = explode('/', $data);
	return $data[2].'-'.$data[1].'-'.$data[0];
}

/**
 * Decodifica flags de caracteres especiais bloqueados para URL
 *
 * @author Jhonatan Müller
 * @param string $str String codificada
 * @return string String decodificada
 */
function custom_urldecode($str)
{
  if(trim($str) != ''){
    $str = urldecode($str);
    $str = str_replace('%20', ' ', $str);
    $str = str_replace('|-1-', ':', $str);
    $str = str_replace('|-2-', '\\', $str);
    //$str = str_replace('|-3-', '%', $str);
    $str = str_replace('|-4-', '&', $str);
    $str = str_replace('|-5-', '(', $str);
    $str = str_replace('|-6-', ')', $str);
    $str = str_replace('|-7-', '/', $str);
    $str = str_replace('|-8-', '#', $str);
    $str = str_replace('|-9-', "\"", $str);
    $str = str_replace('|-10-', "?", $str);   
  }
  
  return $str;
}

/**
 * Codifica URL observando limitações técnicas do CI quanto a caracteres especiais passados na URL
 *
 * @author Jhonatan Müller
 * @param string $str
 * @return string String codificada
 */
function custom_urlencode($str)
{
  if(trim($str) != ''){
    $str = str_replace(' ', "%20", $str);
    $str = str_replace('+', "%20", $str);
    $str = str_replace(':', '|-1-', $str);
    $str = str_replace('\\', '|-2-', $str);
    //$str = str_replace('%','|-3-', $str);
    $str = str_replace('&','|-4-', $str);
    $str = str_replace('(','|-5-', $str);
    $str = str_replace(')','|-6-', $str);
    $str = str_replace('/','|-7-', $str);
    $str = str_replace('#','|-8-', $str);
    $str = str_replace("\"",'|-9-', $str);
    $str = str_replace("?",'|-10-', $str);
    $str = urlencode($str);
  }
  
  return $str;
}

/**
 * Retorna a sigla de todos os estados brasileiros em um vetor
 *
 * @author Jhonatan Müller
 * @return array
 */
function array_uf()
{
	return array('' => '', 'AC' => 'AC',
			'AL' => 'AL', 'AM' => 'AM', 'AP' => 'AP', 'BA' => 'BA', 'CE' => 'CE', 'DF' => 'DF', 'ES' => 'ES',
			'GO' => 'GO', 'MA' => 'MA', 'MG' => 'MG', 'MS' => 'MS', 'MT' => 'MT', 'PA' => 'PA', 'PB' => 'PB',
			'PE' => 'PE', 'PI' => 'PI', 'PR' => 'PR', 'RJ' => 'RJ', 'RN' => 'RN', 'RO' => 'RO', 'RR' => 'RR',
			'RS' => 'RS', 'SC' => 'SC', 'SE' => 'SE', 'SP' => 'SP', 'TO' => 'TO');
}

/**
 * Verifica se o valor passado pertence ao combo originalmente, para que não seja burlado.
 * Exemplo de uso: valida_valor_combo('talvez', 'sim', 'nao', 'talvez')
 * Neste caso o valor postado (primeiro argumento) deve ser ou sim ou não ou talvez (a partir do segundo argumento).
 * Não foi usado array para se adequar a estrutura da form validation class.
 *
 * @author Jhonatan Müller
 * @return bool
 */
function valida_valor_combo()
{
	// Pega os valores passados como argumento. Ex. valida_valor_combo('sim', 'nao'), o combo possui somente sim e não como valores
	$valores_validos = func_get_args();
	array_shift($valores_validos); // retira o primeiro elemento, pois é o valor postado.

	
	if(!in_array(func_get_arg(0), $valores_validos))
		return FALSE;
	
	return TRUE;
}

/***
 * Função para remover acentos de uma string
 *
 * @autor Thiago Belem <contato@thiagobelem.net>
 */
function removeAcentos($string, $slug = false) {
  $string = strtolower($string);

   $acentos = 'Á Í Ó Ú É Ä Ï Ö Ü Ë À Ì Ò Ù È Ã Õ Â Î Ô Û Ê á í ó ú é ä ï ö ü ë à ì ò ù è ã õ â î ô û ê Ç ç | # $ ^ & * ( ) ! ~ ` " § º ª';
   $letras  = 'A I O U E A I O U E A I O U E A O A I O U E a i o u e a i o u e a i o u e a o a i o u e C c . . . . . . . . . . . . . . .';

   $string = str_replace(explode(' ', $acentos), explode(' ', $letras), $string);

  // Código ASCII das vogais
  $ascii['a'] = range(224, 230);
  $ascii['e'] = range(232, 235);
  $ascii['i'] = range(236, 239);
  $ascii['o'] = array_merge(range(242, 246), array(240, 248));
  $ascii['u'] = range(249, 252);

  // Código ASCII dos outros caracteres
  $ascii['b'] = array(223);
  $ascii['c'] = array(231);
  $ascii['d'] = array(208);
  $ascii['n'] = array(241);
  $ascii['y'] = array(253, 255);

  foreach ($ascii as $key=>$item) {
    $acentos = '';
    foreach ($item AS $codigo) $acentos .= chr($codigo);
    $troca[$key] = '/['.$acentos.']/i';
  }

  $string = preg_replace(array_values($troca), array_keys($troca), $string);

  // Slug?
  if ($slug) {
    // Troca tudo que não for letra ou número por um caractere ($slug)
    $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
    // Tira os caracteres ($slug) repetidos
    $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
    $string = trim($string, $slug);
  }

  return $string;
}


/***
 * Função ajustar o str_pad considerando caracteres do utf8
 *
 * @autor Ronald Ulysses Swanson
 * @url http://stackoverflow.com/questions/14773072/php-str-pad-unicode-issue
 */
function mb_str_pad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT, $encoding = NULL)
{
    $encoding = $encoding === NULL ? mb_internal_encoding() : $encoding;
    $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
    $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
    $pad_len -= mb_strlen($str, $encoding);
    $targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
    $strToRepeatLen = mb_strlen($pad_str, $encoding);
    $repeatTimes = ceil($targetLen / $strToRepeatLen);
    $repeatedString = str_repeat($pad_str, max(0, $repeatTimes)); // safe if used with valid unicode sequences (any charset)
    $before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
    $after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';
    return $before . $str . $after;
}