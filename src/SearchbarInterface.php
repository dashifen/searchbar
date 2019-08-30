<?php

namespace Dashifen\Searchbar;

/**
 * Interface SearchbarInterface
 *
 * @package Dashifen\Searchbar
 */
interface SearchbarInterface {
  /**
   * getElements
   *
   * Gets the elements property.
   *
   * @return array
   */
  public function getElements(): array;

	/**
   * parse
   *
   * Given data, returns the HTML for a searchbar.
   *
	 * @param array $data
	 *
	 * @return string
	 */
	public function parse(array $data): string;
	
	/**
   * addRow
   *
   * Adds an additional row to this searchbar.
   *
	 * @return void;
	 */
	public function addRow(): void;
	
	/**
   * addSearch
   *
   * Adds a text-based search to this searchbar.
   *
	 * @param string $label
	 * @param string $for
	 * @param string $class
	 *
	 * @return void
	 */
	public function addSearch(string $label, string $for, string $class = ""): void;
	
	/**
   * addToggle
   *
   * Adds a toggle control to this searchbar in the form of a checkbox.
   *
	 * @param string $label
	 * @param string $for
	 * @param string $class
	 *
	 * @return void
	 */
	public function addToggle(string $label, string $for, string $class = ""): void;
	
	/**
   * addFilter
   *
   * Adds a filter element to this searchbar as a selection.
   *
	 * @param string $label
	 * @param string $for
	 * @param array  $options
	 * @param string $class
	 * @param string $defaultText
	 *
	 * @return void
	 * @throws SearchbarException
	 */
	public function addFilter(string $label, string $for, array $options, string $class = "", string $defaultText = ""): void;
	
	/**
   * addReset
   *
   * Adds a reset button to this searchbar.
   *
	 * @param string $label
	 *
	 * @return void
	 */
	public function addReset(string $label);
	
	/**
   * getBar
   *
   * Returns the HTML for this searchbar.
   *
	 * @return string
	 */
	public function getBar(): string;
}
