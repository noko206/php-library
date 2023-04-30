<?php

/**
 * 組み合わせの計算を行うクラス
 * 依存クラス：Modint
 */
class Combination
{
	/** @var int 前処理を行う最大のi */
	private $n;
	/** @var Modint mod計算を行うクラス */
	private $modint;
	/** @var int[] 各i(0≦i≦n)の階乗 */
	private $fact;
	/** @var int[] 各i(0≦i≦n)の階乗の逆元 */
	private $ifact;

	/**
	 * プロパティの初期化｜計算量：O(nlog mod)
	 * @param int $n 前処理を行う最大のi
	 * @param int $mod mod
	 */
	public function __construct(int $n, int $mod)
	{
		$this->n = $n;
		$this->modint = new Modint($mod);
		$this->fact = $this->init_fact($n);
		$this->ifact = $this->init_ifact($n);
	}

	/**
	 * 各i(0≦i≦n)の階乗を計算｜計算量：O(n)
	 * @return int[] 計算結果
	 */
	private function init_fact(): array
	{
		$fact = [];
		$fact[0] = 1; // 0!は1とする
		for ($i = 1; $i <= $this->n; ++$i) {
			$fact[$i] = $this->modint->mul($fact[$i - 1], $i);
		}
		return $fact;
	}

	/**
	 * 各i(0≦i≦n)の階乗の逆元を計算｜計算量：O(n+log mod)
	 * @return int[] 計算結果
	 */
	private function init_ifact(): array
	{
		$ifact = [];
		$ifact[$this->n] = $this->modint->inv($this->fact[$this->n]); // (n!)^(-1)
		for ($i = $this->n; $i > 0; --$i) {
			$ifact[$i - 1] = $this->modint->mul($ifact[$i], $i); // (i!)^(-1)*iで計算
		}
		return $ifact;
	}

	/**
	 * 階乗｜計算量：O(1)
	 * @return int 計算結果
	 */
	public function fact(int $val): int
	{
		assert(0 <= $val && $val <= $this->n);
		return $this->fact[$val];
	}

	/**
	 * 階乗の逆元｜計算量：O(1)
	 * @return int 計算結果
	 */
	public function ifact(int $val): int
	{
		assert(0 <= $val && $val <= $this->n);
		return $this->ifact[$val];
	}

	/**
	 * n個からk個を選ぶ組み合わせの数｜計算量：O(1)
	 * @param int $n nCkのn
	 * @param int $k nCkのk
	 * @return int 計算結果
	 */
	public function nCk(int $n, int $k): int
	{
		assert(0 <= $k && $k <= $n && $n <= $this->n);
		return $this->modint->mul($this->fact[$n], $this->ifact[$k], $this->ifact[$n - $k]);
	}

	/**
	 * n個から重複を許してk個を選ぶ組み合わせの数｜計算量：O(1)
	 * @param int $n nHkのn
	 * @param int $k nHkのk
	 * @return int 計算結果
	 */
	public function nHk(int $n, int $k): int
	{
		return $this->nCk($n - 1 + $k, $n - 1);
	}
}
