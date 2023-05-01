<?php

/**
 * 無向グラフに対して、以下のことがならしO(α(n))でできる
 * ・辺の追加
 * ・2頂点が連結かの判定
 * ・連結成分の頂点数の取得
 */
class Union_Find
{
	private const MIN_N = 1;
	private const MAX_N = 100000000;

	/** @var int $n 頂点数 */
	private $n;
	/** @var int[] $par 親ノードを指す配列 */
	private $par;
	/** @var int[] $siz 自身が属する連結成分の頂点数 (参照されるのは代表元のみ) */
	private $siz;

	/**
	 * プロパティの初期化
	 * 計算量：O(n)
	 * @param int $n 頂点数
	 */
	public function __construct(int $n)
	{
		assert(self::MIN_N <= $n && $n <= self::MAX_N);
		$this->n = $n;
		$this->par = range(0, $n - 1); // 全てのノードを親として初期化
		$this->siz = array_fill(0, $n, 1); // 最初のサイズは1
	}

	/**
	 * 頂点xが属する連結成分の代表元を取得
	 * 計算量：ならしO(α(n))
	 * @param int $x 代表元を取得したい連結成分の頂点
	 * @return int 頂点xが属する連結成分の代表元
	 */
	public function leader(int $x): int
	{
		assert(0 <= $x && $x < $this->n);

		// 自分が親のとき、自分を返す
		if ($this->par[$x] === $x) return $x;
		// 親ノードを更新しつつ、再帰的に代表元を探す
		return $this->par[$x] = $this->leader($this->par[$x]);
	}

	/**
	 * 2つの頂点がx,yが属する連結成分を結合
	 * 計算量：ならしO(α(n))
	 * @param $x 結合したい連結成分の頂点
	 * @param $y 結合したい連結成分の頂点
	 * @return bool true:既に結合済み｜false：結合成功
	 */
	public function merge(int $x, int $y): bool
	{
		assert(0 <= $x && $x < $this->n);
		assert(0 <= $y && $y < $this->n);

		// 代表元を取得
		$rx = $this->leader($x);
		$ry = $this->leader($y);

		// 既に結合している場合は何もしない
		if ($rx === $ry) return false;

		// サイズが大きい方をrxとする
		if ($this->siz[$rx] < $this->siz[$ry]) {
			list($rx, $ry) = [$ry, $rx];
		}

		// サイズの更新
		$this->siz[$rx] += $this->siz[$ry];
		// 親ノードの更新
		$this->par[$ry] = $rx;

		return true;
	}

	/**
	 * 2つの頂点x,yが同じ連結成分に属するかを取得
	 * 計算量：ならしO(α(n))
	 * @param int $x 判定したい連結成分の頂点
	 * @param int $y 判定したい連結成分の頂点
	 * @return bool true:同じ連結成分に属する｜false：同じ連結成分に属さない
	 */
	public function same(int $x, int $y): bool
	{
		assert(0 <= $x && $x < $this->n);
		assert(0 <= $y && $y < $this->n);

		// 代表元が同じかどうかで判定
		return $this->leader($x) === $this->leader($y);
	}

	/**
	 * 頂点xが属する連結成分の頂点数を取得
	 * 計算量：ならしO(α(n))
	 * @param int $x 頂点数を取得したい連結成分の頂点
	 * @return int 連結成分の頂点数
	 */
	public function size(int $x): int
	{
		assert(0 <= $x && $x < $this->n);

		// 代表元のサイズを取得
		return $this->siz[$this->leader($x)];
	}
}
