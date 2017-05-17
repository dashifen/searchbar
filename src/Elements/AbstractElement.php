<?php

namespace Dashifen\Searchbar\Elements;

/**
 * Class AbstractElement
 *
 * @package Dashifen\Searchbar\Elements
 */
abstract class AbstractElement implements ElementInterface {
	/**
	 * @var string $id
	 */
	protected $id = "";
	
	/**
	 * @var string $label
	 */
	protected $label = "";
	
	/**
	 * @var string $value
	 */
	protected $value = "";
	
	/**
	 * @var array $values
	 */
	protected $values = [];
	
	/**
	 * @var string $classes
	 */
	protected $classes = "";
	
	/**
	 * @var string $defaultText
	 */
	protected $defaultText = "";
	
	/**
	 * @var string type
	 */
	protected $type = "unknown";
	
	/**
	 * AbstractElement constructor.
	 *
	 * @param array $options
	 */
	
	public function __construct(array $options = []) {
		foreach ($options as $field => $value) {
			if (property_exists($this, $field)) {
				
				// if $field is a property, then we have a setter function
				// for it.  we'll call those methods here using the $field
				// variable so that any work that needs to be done to value
				// can be done.  we'll assume that things are of the right
				// type, but if not, then PHP will throw a tantrum and we'll
				// fix it.
				
				$function = "set" . ucfirst($field);
				$this->{$function}($value);
			}
		}
	}
	
	/**
	 * @return string
	 */
	abstract public function __toString(): string;
	
	/**
	 * @return string
	 */
	abstract public function getFormat(): string;
	
	/**
	 * @return string
	 */
	abstract public function makeLabel(): string;
	
	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}
	
	/**
	 * @param string $type
	 *
	 * @return void
	 */
	public function setType(string $type): void {
		$this->type = $type;
	}
	
	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * @param string $id
	 *
	 * @return void
	 */
	public function setId(string $id): void {
		$this->id = sprintf("%s_%s", $id, $this->getType());
	}
	
	/**
	 * @return string
	 */
	public function getLabel(): string {
		return $this->label;
	}
	
	/**
	 * @param string $label
	 *
	 * @return void
	 */
	public function setLabel(string $label): void {
		$this->label = $label;
	}
	
	/**
	 * @return string
	 */
	public function getValue(): string {
		return $this->value;
	}
	
	/**
	 * @param string $value
	 *
	 * @return void
	 */
	public function setValue(string $value): void {
		$this->value = $value;
	}
	
	/**
	 * @return array
	 */
	public function getValues(): array {
		return $this->values;
	}
	
	/**
	 * @param array $values
	 *
	 * @return void
	 */
	public function setValues(array $values): void {
		$this->values = $values;
	}
	
	/**
	 * @return string
	 */
	public function getClasses(): string {
		return $this->classes;
	}
	
	/**
	 * @param string $classes
	 *
	 * @return void
	 */
	public function setClasses(string $classes): void {
		$this->classes = $classes;
	}
	
	/**
	 * @return string
	 */
	public function getDefaultText(): string {
		return $this->defaultText;
	}
	
	/**
	 * @param string $defaultText
	 *
	 * @return void
	 */
	public function setDefaultText(string $defaultText): void {
		$this->defaultText = $defaultText;
	}
}
