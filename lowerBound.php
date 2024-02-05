<?php

/**
 * 二分探索
 * $x以上となる$aの最小のインデックスを返す
 * そのような値が存在しない場合は$aのサイズを返す
 * @param list<int> $a
 * @param int $x
 * @return int
 */
function lowerBound(array $a, int $x): int
{
	$ok = count($a);
	$ng = -1;
	while ($ok - $ng !== 1) {
		$mid = intdiv($ok + $ng, 2);
		if ($a[$mid] >= $x) {
			$ok = $mid;
		} else {
			$ng = $mid;
		}
	}
	return $ok;
}
