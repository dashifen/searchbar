<?php

namespace Dashifen\Searchbar;

/**
 * Interface SearchbarInterface
 *
 * @package Dashifen\Searchbar
 */
interface SearchbarInterface {
	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function parse(array $data): string;
	
	/**
	 * @return void;
	 */
	public function addRow(): void;
	
	/**
	 * @param string $label
	 * @param string $for
	 * @param string $class
	 *
	 * @return void
	 */
	public function addSearch(string $label, string $for, string $class = ""): void;
	
	/**
	 * @param string $label
	 * @param string $for
	 * @param string $class
	 *
	 * @return void
	 */
	public function addToggle(string $label, string $for, string $class = ""): void;
	
	/**
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
	 * @return string
	 */
	public function getBar(): string;
}
