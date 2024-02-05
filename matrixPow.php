<?php

/**
 * 行列累乗
 * 依存ライブラリ：matrixMul
 *
 * @param list<list<int>> $a
 * @param int $n
 * @param int $mod
 * @return list<list<int>>
 */
function matrixPow(array $a, int $n, int $mod): array
{
	$res = array_fill(0, count($a), array_fill(0, count($a), 0));
	for ($i = 0; $i < count($a); ++$i) {
		$res[$i][$i] = 1;
	}
	while ($n > 0) {
		if ($n & 1) {
			$res = matrixMul($a, $res, $mod);
		}
		$a = matrixMul($a, $a, $mod);
		$n >>= 1;
	}
	return $res;
}
