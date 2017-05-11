<?php

namespace Dashifen\Searchbar\Elements;

class Reset extends AbstractElement {
	/**
	 * Reset constructor.
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		parent::__construct($options);
		$this->type = "reset";
	}
	
	/**
	 * @return string
	 */
	public function __toString(): string {
		return sprintf($this->getFormat(), $this->makeLabel());
	}
	
	/**
	 * @return string
	 */
	public function getFormat(): string {
		return '<button type="reset">%s</button>';
	}
	
	/**
	 * @return string
	 */
	public function makeLabel(): string {
		return !empty($this->label) ? $this->label : "Reset";
	}
}
