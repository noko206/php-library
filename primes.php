<?php

/**
 * 素数のリストを取得
 * 計算量：O(nloglog n)
 * @param int $n n以下の素数を取得
 * @return int[] 素数のリスト
 */
function primes(int $n): array
{
	$sieve = array_fill(0, $n + 1, true);
	$sieve[0] = false;
	$sieve[1] = false;
	for ($i = 2; $i <= $n; ++$i) {
		if (!$sieve[$i]) continue;
		for ($j = $i * 2; $j <= $n; $j += $i) {
			$sieve[$j] = false;
		}
	}
	return array_keys(array_filter($sieve));
}
