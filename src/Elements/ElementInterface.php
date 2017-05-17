<?php

namespace Dashifen\Searchbar\Elements;

/**
 * Interface ElementInterface
 *
 * @package Dashifen\Searchbar\Elements
 */
interface  ElementInterface {
	/**
	 * @return string
	 */
	public function getType(): string;
	
	/**
	 * @param string $type
	 *
	 * @return void
	 */
	public function setType(string $type): void;
	
	/**
	 * @return string
	 */
	public function getId(): string;
	
	/**
	 * @param string $id
	 *
	 * @return void
	 */
	public function setId(string $id): void;
	
	/**
	 * @return string
	 */
	public function getLabel(): string;
	
	/**
	 * @param string $label
	 *
	 * @return void
	 */
	public function setLabel(string $label): void;
	
	/**
	 * @return string
	 */
	public function makeLabel(): string;
	
	/**
	 * @return string
	 */
	public function getValue(): string;
	
	/**
	 * @param string $value
	 *
	 * @return void
	 */
	public function setValue(string $value): void;
	
	/**
	 * @return array
	 */
	public function getValues(): array;
	
	/**
	 * @param array $values
	 *
	 * @return void
	 */
	public function setValues(array $values): void;
	
	/**
	 * @return string
	 */
	public function getClasses(): string;
	
	/**
	 * @param string $classes
	 *
	 * @return void
	 */
	public function setClasses(string $classes): void;
	
	/**
	 * @return string
	 */
	public function getDefaultText(): string;
	
	/**
	 * @param string $defaultText
	 *
	 * @return void
	 */
	public function setDefaultText(string $defaultText): void;
	
	/**
	 * @return string
	 */
	public function getFormat(): string;
	
	/**
	 * @return string
	 */
	public function __toString(): string;
}
