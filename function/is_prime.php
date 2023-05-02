<?php

/**
 * 素数判定
 * 計算量：O(√n)
 * @param int $n 素数か判定したい値
 * @return bool true:素数|false:素数ではない
 */
function is_prime(int $n): bool
{
	if ($n === 1 || $n === 0) return false;
	for ($i = 2; $i * $i <= $n; ++$i) {
		if ($n % $i === 0) return false;
	}
	return true;
}
