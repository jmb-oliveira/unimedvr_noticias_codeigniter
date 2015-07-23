<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formata string
 *
 * @param string $val Valor sem máscara
 * @param string $mask Formato de saída, usar # como icognita
 * @return string
 */
function mask($val, $mask)
{
	$maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++)
	{
		
		if($mask[$i] == '#'){
			if(isset($val[$k]))
				$maskared .= $val[$k++];
		} else {
			if(isset($mask[$i]))
				$maskared .= $mask[$i];
		}
	}
	
	return $maskared;
}

/**
 * Resume texto
 *
 * @author Thiago Belem
 * @url http://blog.thiagobelem.net/limitando-textos/
 * @param string $texto
 * @param int $limite
 * @param boolean $quebra cortar ou nao as palavras
 * @return string
 */
function limita_caracteres($texto, $limite, $quebra = true)
{
    $tamanho = strlen($texto);
    // Verifica se o tamanho do texto é menor ou igual ao limite
    if ($tamanho <= $limite) {
        $novo_texto = $texto;
    // Se o tamanho do texto for maior que o limite
    } else {
        // Verifica a opção de quebrar o texto
        if ($quebra == true) {
            $novo_texto = trim(substr($texto, 0, $limite)).'...';
        // Se não, corta $texto na última palavra antes do limite
        } else {
            // Localiza o útlimo espaço antes de $limite
            $ultimo_espaco = strrpos(substr($texto, 0, $limite), ' ');
            // Corta o $texto até a posição localizada
            $novo_texto = trim(substr($texto, 0, $ultimo_espaco)).'...';
        }
    }
    // Retorna o valor formatado
    return $novo_texto;
}