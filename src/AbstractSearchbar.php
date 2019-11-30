<?php

namespace Dashifen\Searchbar;

use Dashifen\Searchbar\Elements\Reset;
use Dashifen\Searchbar\Elements\Toggle;
use Dashifen\Searchbar\Elements\Filter;
use Dashifen\Searchbar\Elements\Search;
use Dashifen\Searchbar\Elements\ElementInterface;

abstract class AbstractSearchbar implements SearchbarInterface {
  /**
   * @var AbstractElement[] $elements
   */
  protected $elements = [[]];

  /**
   * @var int $index
   */
  protected $index = 0;

  /**
   * @param array $data
   *
   * @return string
   */
  abstract public function parse (array $data): string;

  /**
   * getElements
   *
   * Gets the elements property.
   *
   * @return array
   */
  public function getElements (): array {
    return $this->elements;
  }

  /**
   *
   */
  public function addRow (): void {
    ++$this->index;
  }

  /**
   * @param string $label
   * @param string $for
   * @param string $class
   */
  public function addSearch (string $label, string $for, string $class = ""): void {
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
   * @return mixed|string
   */
  protected function getValue (string $for, $default = "") {

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
   */
  public function addToggle (string $label, string $for, string $class = ""): void {
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
   */
  public function addFilter (string $label, string $for, array $options, string $class = "", string $defaultText = ""): void {
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
  public function getBar (): string {
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
    $item_open = "<$item class=\"%s\">";
    $item_close = "</$item>";

    $rows = "";
    foreach ($this->elements as $elements) {
      foreach ($elements as &$element) {
        /** @var ElementInterface $element */

        $element_open = sprintf($item_open, $element->getType());
        $element = $element_open . $element . $item_close;
      }

      $rows .= $list_open . join("", $elements) . $list_close;
    }

    return sprintf($this->getSearchbarFormat(), $rows);
  }

  /**
   * @return string
   */
  protected function getSearchbarRowContainer (): string {
    return "ol";
  }

  /**
   * @return string
   */
  protected function getSearchbarElementContainer (): string {
    return "li";
  }

  /**
   * @return string
   */
  protected function getSearchbarFormat (): string {
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
   */
  public function addReset (string $label) {
    $this->elements[$this->index][] = new Reset([
      "label" => $label
    ]);
  }
}
