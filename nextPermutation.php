<?php

/**
 * 次の順列を生成
 *
 * @param list<int> &$a 次の順列に上書きされる
 * @return bool true:次の順列が存在する/false:次の順列が存在しない
 */
function nextPermutation(array &$a): bool {
	$n = count($a);
	$i = $n - 1;
	if ($i <= 0) {
		return false;
	}
	while ($a[$i - 1] >= $a[$i]) {
		if (--$i === 0) {
			return false;
		}
	}
	$j = $n - 1;
	while ($j > $i && $a[$j] <= $a[$i - 1]) {
		--$j;
	}
	list($a[$i - 1], $a[$j]) = [$a[$j], $a[$i - 1]];
	$k = $n - $i;
	$rev = [];
	while ($k--) {
		$rev[] = array_pop($a);
	}
	array_push($a, ...$rev);
	return true;
}
