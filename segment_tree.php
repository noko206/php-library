<?php

/**
 * モノイドに対して使用できるデータ構造
 * モノイドとは集合Sとその上の二項演算・: S × S → S が与えられ、以下の条件を満たすもの
 * ・結合律：Sの任意の元a,b,cに対して、(a・b)・c = a・(b・c)
 * ・単位元の存在：Sの元eが存在して、Sの任意の元aに対して、e・a = a・e = a
 * 長さnのSの配列aに対して、以下のことがO(log n)でできる
 * ・要素の1点変更
 * ・区間の要素の総積の取得
 * 計算量は二項演算opと単位元の取得eが定数時間で動作すると仮定したときのもの
 * 各計算量がO(f(n))である場合、全ての計算量はO(f(n))倍になる
 */
class Segment_Tree
{
	private const MIN_N = 1;
	private const MAX_N = 100000000;

	/** @var int 配列の長さ */
	private $n;
	/** @var callable function($a, $b) { // a,bに対する二項演算 } */
	private $op;
	/** @var callable function() { // opの単位元 } */
	private $e;
	/** @var int log(木の頂点数) */
	private $log;
	/** @var int 木の頂点数 */
	private $size;
	/** @var mixed[] Sを管理する木 */
	private $data;

	/**
	 * プロパティの初期化
	 * 計算量：O(n)
	 * @param int $n 配列aの要素数
	 * @param callable $op function($a, $b) { // a,bに対する二項演算 }
	 * @param callable $e callable function() { // opの単位元 }
	 * @param mixed[] $a 配列aの初期値 (空配列の場合は単位元が初期値)
	 */
	public function __construct(int $n, callable $op, callable $e, array $a = [])
	{
		assert(self::MIN_N <= $n && $n <= self::MAX_N);
		$this->n = $n;
		$this->op = $op;
		$this->e = $e;
		$this->log = $this->bit_length($n - 1);
		$this->size = 1 << $this->log;
		$this->data = array_fill(0, 2 * $this->size, $e());
		if (!empty($a)) {
			// 葉に配列の初期値を入れる
			for ($i = 0; $i < $n; ++$i) {
				$this->data[$this->size + $i] = $a[$i];
			}
			// 葉に近い場所から順に更新
			for ($i = $this->size - 1; $i >= 1; --$i) {
				$this->update($i);
			}
		}
	}

	/**
	 * ビット長を取得
	 * 計算量：O(log n)
	 * @param int $n ビット長を求めたい値
	 * @return int ビット長
	 */
	private function bit_length(int $n): int
	{
		$len = 0;
		while ($n > 0) {
			$n >>= 1;
			++$len;
		}
		return $len;
	}

	/**
	 * 値の更新
	 * 計算量：O(1)
	 * @param int $k 更新したいdataの添え字
	 */
	private function update(int $k): void
	{
		$this->data[$k] = ($this->op)($this->data[2 * $k], $this->data[2 * $k + 1]);
	}

	/**
	 * a[p]をxに更新
	 * 計算量：O(log n)
	 * @param int $p 配列aの添え字(0-indexed)
	 * @param mixed $x 更新したい値(x∈S)
	 */
	public function set(int $p, $x): void
	{
		assert(0 <= $p && $p < $this->n);
		// 葉に移動
		$p += $this->size;
		$this->data[$p] = $x;
		for ($i = 1; $i <= $this->log; ++$i) {
			$this->update($p >> $i);
		}
	}

	/**
	 * a[p]を取得
	 * 計算量：O(1)
	 * @param int $p 配列aの添え字(0-indexed)
	 * @return mixed a[p]
	 */
	public function get(int $p)
	{
		assert(0 <= $p && $p < $this->n);
		return $this->data[$p + $this->size];
	}

	/**
	 * op(a[l],...,a[r-1])を取得
	 * l=rのときはe()を返す
	 * 計算量：O(log n)
	 * @param int $l 区間の左端
	 * @param int $r 区間の右端
	 * @return mixed 区間[l,r)の総積
	 */
	public function prod(int $l, int $r)
	{
		assert(0 <= $l && $l <= $r && $r <= $this->n);
		$sml = ($this->e)();
		$smr = ($this->e)();
		$l += $this->size;
		$r += $this->size;
		while ($l < $r) {
			if ($l & 1) {
				$sml = ($this->op)($sml, $this->data[$l]);
				++$l;
			}
			if ($r & 1) {
				--$r;
				$smr = ($this->op)($this->data[$r], $smr);
			}
			$l >>= 1;
			$r >>= 1;
		}
		return ($this->op)($sml, $smr);
	}

	/**
	 * op(a[0],...,a[n-1])を取得
	 * 計算量：O(1)
	 * @return mixed 全要素の総積
	 */
	public function all_prod()
	{
		return $this->data[1];
	}

	/**
	 * [二分探索]以下の条件を両方満たすrを1つ返す
	 * ・r=lもしくはf(op(a[l],...,a[r-1]))=true
	 * ・r=nもしくはf(op(a[l],...,a[r]))=false
	 * fが単調ならf(op(a[l],...,a[r-1]))=trueとなる最大のr
	 * @param int $l 区間の左端
	 * @param callable $f function($x): bool { }
	 * @return int 条件を満たすr
	 */
	public function max_right(int $l, callable $f): int
	{
		assert(0 <= $l && $l <= $this->n);
		assert($f(($this->e)()));
		if ($l === $this->n) return $this->n;
		$l += $this->size;
		$sm = ($this->e)();
		do {
			while ($l % 2 === 0) {
				$l >>= 1;
			}
			if (!$f(($this->op)($sm, $this->data[$l]))) {
				while ($l < $this->size) {
					$l <<= 1;
					if ($f(($this->op)($sm, $this->data[$l]))) {
						$sm = ($this->op)($sm, $this->data[$l]);
						++$l;
					}
				}
				return $l - $this->size;
			}
			$sm = ($this->op)($sm, $this->data[$l]);
			$l += 1;
		} while(($l & -$l) !== $l);
		return $this->n;
	}

	/**
	 * [二分探索]以下の条件を両方満たすlを1つ返す
	 * ・l=rもしくはf(op(a[l],...,a[r-1]))=true
	 * ・l=0もしくはf(op(a[l],...,a[r]))=false
	 * fが単調ならf(op(a[l-1],...,a[r-1]))=trueとなる最大のl
	 * @param int $r 区間の右端
	 * @param callable $f function($x): bool { }
	 * @return int 条件を満たすl
	 */
	public function min_left(int $r, callable $f): int
	{
		assert(0 <= $r && $r <= $this->n);
		assert($f(($this->e)()));
		if ($r === 0) return 0;
		$r += $this->size;
		$sm = ($this->e)();
		do {
			$r -= 1;
			while ($r > 1 && $r % 2 === 0) {
				$r >>= 1;
			}
			if (!$f(($this->op)($this->data[$r], $sm))) {
				while ($r < $this->size) {
					$r = 2 * $r + 1;
					if ($f(($this->op)($this->data[$r], $sm))) {
						$sm = ($this->op)($this->data[$r], $sm);
						$r -= 1;
					}
				}
				return $r + 1 - $this->size;
			}
			$sm = ($this->op)($this->data[$r], $sm);
		} while(($r & -$r) !== $r);
		return 0;
	}
}
