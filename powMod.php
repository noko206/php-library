<?php

/**
 * 繰り返し二乗法
 *
 * @param int $val
 * @param int $exp
 * @param int $mod
 * @return int
 */
function powMod(int $val, int $exp, int $mod): int
{
	assert($exp >= 0);
	$val %= $mod;
	if ($val < 0) $val += $mod;
	$pow = function ($val, $exp) use (&$pow, $mod) {
		if ($exp === 0) return 1;
		if ($exp === 1) return $val % $mod;
		if ($exp % 2 === 1) return ($val * $pow($val, $exp - 1, $mod)) % $mod;
		return pow($pow($val, $exp >> 1, $mod), 2) % $mod;
	};
	return $pow($val, $exp);
}
