<?php

namespace Dashifen\Searchbar\Elements;

class Search extends AbstractElement {
	/**
	 * @var string
	 */
	protected $type = "search";
	
	/**
	 * Search constructor.
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		parent::__construct($options);
	}
	
	/**
	 * @return string
	 */
	public function __toString(): string {
		return sprintf($this->getFormat(),
			$this->makeLabel(),
			$this->id,
			$this->value
		);
	}
	
	/**
	 * @return string
	 */
	public function getFormat(): string {
		
		// search fields are inputs with labels as follows.  the order
		// of our sprintf() parameters are the label, the id, and then
		// the value.
		
		return '%s <input type="text" id="%s" value="%s">';
	}
	
	/**
	 * @return string
	 */
	public function makeLabel(): string {
		return sprintf($this->getLabelFormat(), $this->id, $this->classes, $this->label);
	}
	
	/**
	 * @return string
	 */
	protected function getLabelFormat(): string {
		return '<label for="%s" class="%s"><em>Search</em><span> within %s</span></label>';
	}
}
