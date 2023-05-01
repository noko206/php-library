<?php

/**
 * 長さnの配列aに対して、以下のことがO(log n)でできる
 * ・要素の1点加算
 * ・区間の要素の総和の取得
 * ・二分探索 (a[0]+...+a[i]がx以上(より大きい))最小の添え字の取得
 */
class Binary_Indexed_Tree
{
	/** @var int $n 要素数 */
	private $n;
	/** @var int[] $bit データの格納先 */
	private $bit;

	/**
	 * プロパティの初期化
	 * 計算量：O(n)
	 * @param int $n 要素数
	 */
	public function __construct(int $n) {
		$this->n = $n; // aの要素数
		$this->bit = array_fill(0, $n + 1, 0); // 内部的には1-indexedなのでn+1
	}

	/**
	 * 1点加算
	 * 計算量：O(log n)
	 * @param int $idx キー (0-indexed)
	 * @param int $x 加算する値
	 */
	public function add(int $idx, int $x): void {
		++$idx; // 1-indexedにする
		for ($i = $idx; $i <= $this->n; $i += ($i & -$i)) {
			$this->bit[$i] += $x;
		}
	}

	/**
	 * a[0]+...+a[idx-1]を取得
	 * 計算量：O(log n)
	 * @param int $idx キー (0-indexed)
	 * @return int 最初からidx番目までの和
	 */
	private function sub_sum(int $idx): int {
		++$idx; // 1-indexedにする
		$sum = 0;
		for ($i = $idx; $i > 0; $i -= ($i & -$i)) {
			$sum += $this->bit[$i];
		}
		return $sum;
	}

	/**
	 * a[l]+...+a[r-1]を取得
	 * 計算量：O(log n)
	 * @param int $l 区間の左端 (0-indexed)
	 * @param int $r 区間の右端 (0-indexed)
	 * @return int a[l]+...+a[r-1]
	 */
	public function sum(int $l, int $r): int
	{
		assert(0 <= $l && $l <= $r && $r <= $this->n);
		return $this->sub_sum($r) - $this->sub_sum($l);
	}

	/**
	 * a[0]+...+a[i] >= xとなるような最小のiを取得 (0-indexed)
	 * 計算量：O(log n)
	 * @param int $x 探索したい値
	 * @return int a[0]+...+a[i] >= xとなるような最小のi
	 */
	public function lower_bound(int $x): int {
		return $this->count_lower($x);
	}

	/**
	 * a[0]+...+a[i] > xとなるような最小のiを取得 (0-indexed)
	 * 計算量：O(log n)
	 * @param int $x
	 * @return int a[0]+...+a[i] > xとなるような最小のi
	 */
	public function upper_bound(int $x): int {
		return $this->count_lower($x + 1);
	}

	/**
	 * a[0]+...+a[i] >= xとなるような最小のiを取得 (0-indexed)
	 * 計算量：O(log n)
	 * @param int $x 探索したい値
	 * @return int a[0]+...+a[i] >= xとなるような最小のi
	 */
	private function count_lower(int $x): int {
		if ($x <= 0) return -1;
		$k = 0;
		$idx = 1;
		while ($idx <= $this->n) $idx <<= 1;
		for ($i = $idx; $i > 0; $i >>= 1) {
			if ($k + $i <= $this->n && $this->bit[$k + $i] < $x) {
				$x -= $this->bit[$k + $i];
				$k += $i;
			}
		}
		return $k;
	}
}
