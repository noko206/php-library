<?php

/**
 * 転倒数
 * 依存ライブラリ: BinaryIndexedTree, lowerBound
 *
 * @param list<int> $a
 * @return int
 */
function inversionNumber(array $a): int
{
	$b = array_unique($a);
	sort($b);
	$bit = new BinaryIndexedTree(count($b));
	$ans = 0;
	$n = count($a);
	for ($i = 0; $i < $n; ++$i) {
		$rank = lowerBound($b, $a[$i]);
		$ans += $i - $bit->sum(0, $rank + 1);
		$bit->add($rank, 1);
	}
	return $ans;
}
