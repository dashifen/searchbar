<?php

namespace Dashifen\Searchbar;

use Dashifen\Searchbar\Elements\Search;
use Dashifen\Searchbar\Elements\Toggle;
use Dashifen\Searchbar\Elements\Filter;
use Dashifen\Searchbar\Elements\Reset;

abstract class AbstractSearchbar implements SearchbarInterface {
	/**
	 * @var array $elements
	 */
	protected $elements = [[]];
	
	/**
	 * @var int $index
	 */
	protected $index = 0;
	
	/**
	 * @param array $data
	 *
	 * the parse function should take an array of data and return a
	 * complete searchbar.  the way in which this happens is likely
	 * unique to each application that uses this object so we'll leave
	 * it abstract here.
	 *
	 * @return string
	 */
	abstract public function parse(array $data): string;
	
	/**
	 * @return void;
	 */
	public function addRow(): void {
		++$this->index;
	}
	
	/**
	 * @param string $label
	 * @param string $for
	 * @param string $class
	 *
	 * @return void
	 */
	public function addSearch(string $label, string $for, string $class = ""): void {
		$this->elements[$this->index][] = new Search([
			"id"    => $for,
			"value" => $this->getValue($for),
			"label" => $label,
			"class" => $class,
		]);
	}
	
	/**
	 * @param string $for
	 * @param string $default
	 *
	 * @return string
	 */
	protected function getValue(string $for, $default = "") {
		
		// by default, we just assume that the information we want to use
		// as a value for our element is on the query string.  but, since
		// applications may want to handle this differently, this function
		// can be overwritten to change that behavior.
		
		return $_GET[$for] ?? $default;
	}
	
	/**
	 * @param string $label
	 * @param string $for
	 * @param string $class
	 *
	 * @return void
	 */
	public function addToggle(string $label, string $for, string $class = ""): void {
		$this->elements[$this->index][] = new Toggle([
			"id"    => $for,
			"value" => $this->getValue($for, false),
			"label" => $label,
			"class" => $class,
		]);
	}
	
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
	public function addFilter(string $label, string $for, array $options, string $class = "", string $defaultText = ""): void {
		$this->elements[$this->index][] = new Filter([
			"id"          => $for,
			"defaultText" => $defaultText,
			"value"       => $this->getValue($for),
			"values"      => $options,
			"label"       => $label,
			"class"       => $class,
		]);
	}
	
	/**
	 * @return string
	 */
	public function getBar(): string {
		if (sizeof($this->elements) === 0) {
			return "";
		}
		
		// because our objects implementing ElementInterface all have to
		// define their __toString() method, we can simply join our elements
		// together here as follows.  we'll get a container using the method
		// below provided to make switching from <li> to another element
		// easier.  but, in case someone sends us the tag rather than the
		// name of the tag, we're going to remove non-word characters.
		
		$list = preg_replace("/\W+/", "", $this->getSearchbarRowContainer());
		$item = preg_replace("/\W+/", "", $this->getSearchbarElementContainer());
		
		$list_open = "<$list>";
		$list_close = "</$list>";
		$item_open = "<$item>";
		$item_close = "</$item>";
		
		$rows = "";
		foreach ($this->elements as $row => $elements) {
			$elements = $item_open . join($item_close . $item_open, $elements) . $item_close;
			$rows .= $list_open . $elements . $list_close;
		}
		
		return sprintf($this->getSearchbarFormat(), $rows);
	}
	
	protected function getSearchbarRowContainer(): string {
		return "ol";
	}
	
	protected function getSearchbarElementContainer(): string {
		return "li";
	}
	
	protected function getSearchbarFormat(): string {
		return <<<SEARCHBAR
			<form class="searchbar">
			<fieldset data-searchbar="1">
			<legend>
				<label>Use these fields to search within and/or filter the data below.</label>
			</legend>
			%s
			</fieldset>
			</form>
SEARCHBAR;
	}
	
	/**
	 * @param string $label
	 *
	 * @return void
	 */
	public function addReset(string $label) {
		$this->elements[$this->index][] = new Reset([
			"label" => $label
		]);
	}
}
