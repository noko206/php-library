<?php

/**
 * 二項係数
 * 依存ライブラリ:invMod
 *
 * @param int $n
 * @param int $k
 * @param int $mod
 */
function binomial(int $n, int $k, int $mod): int
{
	if ($k < 0 || $n < $k) return 0;
	static $fact = [];
	static $ifact = [];
	if (count($fact) < $n + 1) {
		$pos = count($fact);
		for ($i = $pos; $i <= $n; ++$i) {
			if ($i > 0) {
				$fact[$i] = ($i * $fact[$i - 1]) % $mod;
			} else {
				$fact[$i] = 1;
			}
		}
	}
	$mx = max($k, $n - $k);
	if (count($ifact) < $mx + 1) {
		$pos = count($ifact);
		$ifact[$mx] = invMod($fact[$mx], $mod);
		for ($i = $mx - 1; $i >= $pos; --$i) {
			$ifact[$i] = (($i + 1) * $ifact[$i + 1]) % $mod;
		}
	}
	return ((($fact[$n] * $ifact[$k]) % $mod) * $ifact[$n - $k]) % $mod;
}
