<?php

/**
 * 逆元を拡張ユークリッドの互除法で計算
 *
 * @param int $val
 * @param int $mod
 * @return int
 */
function invMod(int $val, int $mod): int
{
	$val %= $mod;
	if ($val < 0) {
		$val += $mod;
	}
	$a = $val;
	$b = $mod;
	$u = 1;
	$v = 0;
	while ($b) {
		$t = intdiv($a, $b);
		$a -= $t * $b;
		list($a, $b) = [$b, $a];
		$u -= $t * $v;
		list($u, $v) = [$v, $u];
	}
	return (($u % $mod) + $mod) % $mod;
}
