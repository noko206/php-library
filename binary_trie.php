<?php

/**
 * 非負整数を管理できる二分木のトライ木
 * 各クエリでA_iが使われるとき、以下のことがO(log(A_Max))でできる
 * ・集合に要素を1つ追加
 * ・集合に要素を1つ削除
 * ・集合内の最大/最小値の取得
 * ・二分探索 (集合内で値x以上(より大きい)最小の要素が昇順で何番目か取得)
 * ・k番目に小さい要素を取得
 */
class Binary_Trie
{
	/** @var int[] ポインタ */
	private $nodes;
	/** @var int[] 部分木に含まれる要素の個数 */
	private $cnt;
	/** @var int 最大のポインタID */
	private $id;
	/** @var int ビット長 */
	private $bitlen;

	/**
	 * プロパティの初期化
	 * 計算量：O(n log(A_Max))
	 * @param int $max_query 集合に含まれるユニークな要素の個数
	 * @param int $bitlen クエリで使用する最大の整数のビット長
	 */
	public function __construct(int $max_query = 200000, int $bitlen = 30)
	{
		$n = $max_query * $bitlen;
		$this->nodes = array_fill(0, 2 * $n, -1);
		$this->cnt = array_fill(0, $n, 0);
		$this->id = 0;
		$this->bitlen = $bitlen;
	}

	/**
	 * 要素を追加
	 * 計算量：O(log(A_Max))
	 * @param int $x 追加する要素
	 */
	public function insert(int $x): void
	{
		$pt = 0;
		for ($i = $this->bitlen - 1; $i >= 0; --$i) {
			$y = $x >> $i & 1;
			if ($this->nodes[2 * $pt + $y] === -1) {
				++$this->id;
				$this->nodes[2 * $pt + $y] = $this->id;
			}
			++$this->cnt[$pt];
			$pt = $this->nodes[2 * $pt + $y];
		}
		++$this->cnt[$pt];
	}

	/**
	 * 要素を削除
	 * 計算量：O(log(A_Max))
	 * @param int $x 削除する要素
	 */
	public function erase(int $x): void
	{
		// 削除する要素がなければ何もしない
		if ($this->count($x) === 0) return;
		$pt = 0;
		for ($i = $this->bitlen - 1; $i >= 0; --$i) {
			$y = $x >> $i & 1;
			--$this->cnt[$pt];
			$pt = $this->nodes[2 * $pt + $y];
		}
		--$this->cnt[$pt];
	}

	/**
	 * 昇順でk番目の値を取得 (1-indexed)
	 * 計算量：O(log(A_Max))
	 * @param int $k 昇順で何番目か
	 * @return int 昇順でk番目の値
	 */
	public function kth_elm(int $k): int
	{
		assert(1 <= $k && $k <= $this->size());
		$pt = 0;
		$ans = 0;
		for ($i = $this->bitlen - 1; $i >= 0; --$i) {
			$ans <<= 1;
			if ($this->nodes[2 * $pt] !== -1 && $this->cnt[$this->nodes[2 * $pt]] > 0) {
				if ($this->cnt[$this->nodes[2 * $pt]] >= $k) {
					$pt = $this->nodes[2 * $pt];
				}
				else {
					$k -= $this->cnt[$this->nodes[2 * $pt]];
					$pt = $this->nodes[2 * $pt + 1];
					++$ans;
				}
			}
			else {
				$pt = $this->nodes[2 * $pt + 1];
				++$ans;
			}
		}
		return $ans;
	}

	/**
	 * $x以上の値のうち、最小の値は昇順で何番目か (1-indexed)
	 * 計算量：O(log(A_Max))
	 * @param int $x 何以上の値か
	 * @return int 昇順で何番目か
	 */
	public function lower_bound(int $x): int
	{
		return $this->count_lower($x);
	}

	/**
	 * $xより大きい値のうち、最小の値は昇順で何番目か (1-indexed)
	 * 計算量：O(log(A_Max))
	 * @param int $x 何より大きい値か
	 * @return int 昇順で何番目か
	 */
	public function upper_bound(int $x): int
	{
		return $this->count_lower($x + 1);
	}

	/**
	 * $x以上の値のうち、最小の値は昇順で何番目か (1-indexed)
	 * 計算量：O(log(A_Max))
	 * @param int $x 何以上の値か
	 * @return int 昇順で何番目か
	 */
	private function count_lower(int $x): int
	{
		$pt = 0;
		$ans = 1;
		for($i = $this->bitlen - 1; $i >= 0; --$i) {
			if ($pt === -1) break;
			if ($x >> $i & 1 && $this->nodes[2 * $pt] !== -1) {
				$ans += $this->cnt[$this->nodes[2 * $pt]];
			}
			$pt = $this->nodes[2 * $pt + ($x >> $i & 1)];
		}
		return $ans;
	}

	/**
	 * 全要素の数を取得
	 * 計算量：O(1)
	 * @return int 全要素の数
	 */
	public function size(): int
	{
		return $this->cnt[0];
	}

	/**
	 * ある要素の個数を取得
	 * 計算量：O(log(A_Max))
	 * @param int $x 個数を取得したい要素の値
	 * @return int ある要素の数
	 */
	public function count(int $x): int
	{
		$pt = 0;
		for ($i = $this->bitlen - 1; $i >= 0; --$i) {
			$y = $x >> $i & 1;
			if ($this->nodes[2 * $pt + $y] === -1) return 0;
			$pt = $this->nodes[2 * $pt + $y];
		}
		return $this->cnt[$pt];
	}

	/**
	 * 最大値を取得
	 * 計算量：O(log(A_Max))
	 * @return int 最大値
	 */
	public function max(): int
	{
		return $this->kth_elm($this->size());
	}

	/**
	 * 最小値を取得
	 * 計算量：O(log(A_Max))
	 * @return int 最小値
	 */
	public function min(): int
	{
		return $this->kth_elm(1);
	}
}
