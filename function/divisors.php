<?php

/**
 * 約数を取得
 * 計算量：O(√n log √n)
 * @param int $n 約数を取得したい値
 * @return int[] 約数のリスト (昇順)
 */
function divisors(int $n): array
{
	$a = [];
	for ($i = 1; $i * $i <= $n; ++$i) {
		if ($n % $i === 0) {
			$a[] = $i;
			$j = intdiv($n, $i);
			if ($j !== $i) {
				$a[] = $j;
			}
		}
	}
	sort($a);
	return $a;
}
