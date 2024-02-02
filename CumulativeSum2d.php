<?php

/**
 * 2次元累積和
 */
class CumulativeSum2d
{
	/** @var int */
	private $h;

	/** @var int */
	private $w;

	/** @var list<list<int>> */
	private $data;

	/**
	 * @param int $h
	 * @param int $w
	 */
	public function __construct(int $h, int $w)
	{
		$this->h = $h;
		$this->w = $w;
		$this->data = array_fill(0, $h + 1, array_fill(0, $w + 1, 0));
	}

	/**
	 * @param int $i 0-indexed
	 * @param int $j 0-indexed
	 * @param int $x
	 * @return void
	 */
	public function add(int $i, int $j, int $x): void
	{
		assert(0 <= $i && $i < $this->h);
		assert(0 <= $j && $j < $this->w);
		$this->data[$i + 1][$j + 1] += $x;
	}

	/**
	 * @return void
	 */
	public function build(): void
	{
		for ($i = 0; $i < $this->h; ++$i) {
			for ($j = 0; $j < $this->w; ++$j) {
				$this->data[$i + 1][$j + 1] += $this->data[$i + 1][$j] + $this->data[$i][$j + 1] - $this->data[$i][$j];
			}
		}
	}

	/**
	 * [$si, $gi), [$sj, $gj)
	 * @param int $si 0-indexed
	 * @param int $sj 0-indexed
	 * @param int $gi 0-indexed
	 * @param int $gj 0-indexed
	 * @return int
	 */
	public function sum(int $si, int $sj, int $gi, int $gj): int
	{
		assert(0 <= $si && $si <= $gi && $gi <= $this->h);
		assert(0 <= $sj && $sj <= $gj && $gj <= $this->w);
		return $this->data[$gi][$gj] - $this->data[$si][$gj] - $this->data[$gi][$sj] + $this->data[$si][$sj];
	}
}
