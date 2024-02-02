<?php

const INF64 = 1001001001001001001;

/**
 * 最長増加部分列の長さを取得
 * @param list<int> $a
 * @param bool $isStrong true:狭義単調増加/false:広義単調増加
 * @return int
 */
function LIS(array $a, bool $isStrong)
{
	$n = count($a);
	$dp = array_fill(0, $n, INF64);
	for ($i = 0; $i < $n; ++$i) {
		if ($isStrong) {
			$dp[lowerBound($dp, $a[$i])] = $a[$i];
		} else {
			$dp[lowerBound($dp, $a[$i] + 1)] = $a[$i];
		}
	}
	return lowerBound($dp, INF64);
}
