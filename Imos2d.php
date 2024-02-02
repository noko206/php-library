<?php

/**
 * 2次元いもす法
 */
class Imos2d
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
	 * [$si, $gi), [$sj, $gj)
	 * @param int $si 0-indexed
	 * @param int $sj 0-indexed
	 * @param int $gi 0-indexed
	 * @param int $gj 0-indexed
	 * @param int $x
	 * @return void
	 */
	public function add(int $si, int $sj, int $gi, int $gj, int $x): void
	{
		assert(0 <= $si && $si <= $gi && $gi <= $this->h);
		assert(0 <= $sj && $sj <= $gj && $gj <= $this->w);
		$this->data[$si][$sj] += $x;
		$this->data[$si][$gj] -= $x;
		$this->data[$gi][$sj] -= $x;
		$this->data[$gi][$gj] += $x;
	}

	/**
	 * @return void
	 */
	public function build(): void
	{
		for ($i = 0; $i < $this->h; ++$i) {
			for ($j = 0; $j < $this->w; ++$j) {
				$this->data[$i + 1][$j] += $this->data[$i][$j];
			}
		}
		for ($j = 0; $j < $this->w; ++$j) {
			for ($i = 0; $i < $this->h; ++$i) {
				$this->data[$i][$j + 1] += $this->data[$i][$j];
			}
		}
	}

	/**
	 * @param int $i 0-indexed
	 * @param int $j 0-indexed
	 * @return int
	 */
	public function get(int $i, int $j): int
	{
		assert(0 <= $i && $i < $this->h);
        assert(0 <= $j && $j < $this->w);
        return $this->data[$i][$j];
	}
}
