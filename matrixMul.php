<?php

/**
 * 行列の積
 *
 * @param list<list<int>> $a 演算子の左側の値
 * @param list<list<int>> $b 演算子の右側の値
 * @param int $mod
 * @return list<list<int>>
 */
function matrixMul(array $a, array $b, int $mod): array
{
	$res = array_fill(0, count($a), array_fill(0, count($b[0]), 0));
	for ($i = 0; $i < count($a); ++$i) {
		for ($j = 0; $j < count($b[0]); ++$j) {
			for ($k = 0; $k < count($b); ++$k) {
				$res[$i][$j] += ($a[$i][$k] * $b[$k][$j]) % $mod;
				$res[$i][$j] %= $mod;
			}
		}
	}
	return $res;
}
