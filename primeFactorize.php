<?php

/**
 * 素因数分解
 * 計算量：O(√n)
 * @param int 素因数分解したい値
 * @return array [素因数,指数]となる配列
 */
function primeFactorize(int $n): array
{
	$a = [];
	for ($i = 2; $i * $i <= $n; ++$i) {
		if ($n % $i !== 0) continue;
		$ex = 0;
		while ($n % $i === 0) {
			++$ex;
			$n = intdiv($n, $i);
		}
		$a[] = [$i, $ex];
	}
	if ($n !== 1) {
		$a[] = [$n, 1];
	}
	return $a;
}
