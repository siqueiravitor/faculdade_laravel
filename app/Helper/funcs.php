<?php

/**
 * Verifica se um CPF é válido.
 *
 * @param string $cpf O CPF a ser validado.
 * @return bool True se o CPF for válido, false caso contrário.
 */

 function validaCPF($cpf)
{
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    for ($i = 0; $i < 10; $i++) {
        if (preg_match("/^{$i}{$i}{$i}{$i}{$i}{$i}{$i}{$i}{$i}{$i}$/", $cpf)) {
            return false;
        }
    }

    for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
        $soma += $cpf[$i] * $j;
    }

    $resto = $soma % 11;

    if ($cpf[9] != ($resto < 2 ? 0 : 11 - $resto)) {
        return false;
    }

    for ($i = 0, $j = 11; $i < 10; $i++, $j--) {
        $soma += $cpf[$i] * $j;
    }

    $resto = $soma % 11;

    return $cpf[10] == ($resto < 2 ? 0 : 11 - $resto);
}