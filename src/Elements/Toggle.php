<?php

namespace Dashifen\Searchbar\Elements;

class Toggle extends AbstractElement {
	/**
	 * @var string
	 */
	protected $type = "toggle";
	
	/**
	 * @var bool $checked
	 */
	protected $checked = false;
	
	/**
	 * Toggle constructor.
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		parent::__construct($options);
		$this->setType("toggle");
	}
	
	/**
	 * @param string $value
	 */
	public function setValue(string $value): void {
		$this->checked = (bool)$value;
		parent::setValue($value);
	}
	
	/**
	 * @return string
	 */
	public function __toString(): string {
		$toggle = sprintf($this->getFormat(), $this->id, $this->label);
		
		// PHPStorm doesn't like it when an sprintf() variable hangs out
		// in a way that doesn't appear to a part of an HTML attribute.
		// thus, to avoid the warning, we'll use str_replace() to add
		// the checked property
		
		if ($this->checked) {
			$toggle = str_replace("input", "input checked", $toggle);
		}
		
		return $toggle;
	}
	
	/**
	 * @return string
	 */
	public function getFormat(): string {
		return '<label class="toggle"><input type="checkbox" id="%s"> %s</label>';
	}
	
	/**
	 * @return string
	 */
	public function makeLabel(): string {
		
		// a toggle's label should appear after its checkbox (see above).
		// and it's contained within a label element already, so here we
		// can just use a <strong> tag like our default label format sends
		// us.
		
		$display = !empty($this->defaultText) ? $this->defaultText : $this->label;
		
		return sprintf($this->getLabelFormat(),
			$this->id,
			$this->classes,
			$display
		);
	}
	
	protected function getLabelFormat(): string {
		return '<strong data-for="%s" class="%s">%s</strong>';
	}
}
