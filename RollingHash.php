<?php

/**
 * ローリングハッシュ
 * 依存ライブラリ:powMod
 */
class RollingHash
{
	/** @var int */
	private $base;

	/** @var int */
	private $mod;

	/** @var list<int> */
	private $hash;

	/**
	 * @param string $s
	 * @param int $base
	 * @param int $mod
	 */
	public function __construct(string $s, int $base = 100, int $mod = 998244353)
	{
		$this->base = $base;
		$this->mod = $mod;
		$this->hash = array_fill(0, strlen($s) + 1, 0);
		$this->build($s);
	}

	/**
	 * [$l, $r)
	 *
	 * @param int $l 0-indexed
	 * @param int $r 0-indexed
	 * @return int
	 */
	public function query(int $l, int $r): int
	{
		assert(0 <= $l && $l <= $r && $r < count($this->hash));
		$ans = $this->hash[$r] - ($this->hash[$l] * powMod($this->base, $r - $l, $this->mod));
		$ans %= $this->mod;
		if ($ans < 0) {
			$ans += $this->mod;
		}
		return $ans;
	}

	/**
	 * @param string $s
	 * @return void
	 */
	private function build(string $s): void
	{
		$n = strlen($s);
		$this->hash[0] = 0;
		for ($i = 0; $i < $n; ++$i) {
			$this->hash[$i + 1] = (($this->hash[$i] * $this->base) % $this->mod + ord($s[$i])) % $this->mod;
		}
	}
}
