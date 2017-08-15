<?php

namespace Dashifen\Searchbar\Elements;

use Dashifen\Searchbar\SearchbarException;

class Filter extends AbstractElement {
	/**
	 * @var string
	 */
	protected $type = "filter";
	
	/**
	 * @var bool grouped
	 */
	protected $grouped = false;
	
	/**
	 * Filter constructor.
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		$this->setType("filter");
		parent::__construct($options);
	}
	
	/**
	 * @param array $values
	 *
	 * @return void
	 * @throws SearchbarException
	 */
	public function setValues(array $values): void {
		
		// when the values are set for our filter, we need to be sure that
		// they're either a 1d or 2d array.  anything with more depth than
		// 2d is a problem as we don't handle nested option groups here
		// at this time.
		
		$depth = $this->getArrayDepth($values);
		
		if ($depth !== 1 && $depth !== 2) {
			throw new SearchbarException('Values too "deep" for filtering',
				SearchbarException::VALUES_TOO_DEEP);
		}
		
		$this->grouped = $depth === 2;
		parent::setValues($values);
	}
	
	protected function getArrayDepth(array $array): int {
		// source: http://stackoverflow.com/a/263621
		
		$max_indentation = 1;
		
		$array_str = print_r($array, true);
		$lines = explode("\n", $array_str);
		
		foreach ($lines as $line) {
			$indentation = (strlen($line) - strlen(ltrim($line))) / 4;
			
			if ($indentation > $max_indentation) {
				$max_indentation = $indentation;
			}
		}
		
		return (int)ceil(($max_indentation - 1) / 2) + 1;
	}
	
	/**
	 * @return string
	 */
	public function __toString(): string {
		
		// this is the most complex of our elements since it has to handle
		// both ungrouped sets of options for our <select> element and
		// ones that are organized into groups.
		
		$allText = !empty($this->defaultText) ? $this->defaultText : $this->label;
		
		return sprintf($this->getFormat(),
			$this->makeLabel(),
			$this->id,
			$allText,
			$this->getOptions()
		);
	}
	
	/**
	 * @return string
	 */
	public function getFormat(): string {
		
		// despite there being the possible need for option groups within
		// our <select> element, the format for both grouped and ungrouped
		// filters is the same.  the difference is in what the sprintf()
		// function used in __toString() crams into the <select>
		
		return '%s <select id="%s"><option value="all">%s</option>%s</select>';
	}
	
	/**
	 * @return string
	 */
	public function makeLabel(): string {
		
		// our label is made up of various properties as follows.  the only
		// real complication is if we have default text that we should use
		// it.  we've added some format filters below to help people that
		// extend this object to change the way its label is made.
		
		$display = sprintf($this->getLabelDisplayFormat(), $this->label);
		return sprintf($this->getLabelFormat(), $this->id, $this->classes, $display);
	}
	
	protected function getLabelDisplayFormat(): string {
		return '<em>Show</em><span> %s</span>';
	}
	
	protected function getLabelFormat(): string {
		return '<label for="%s" class="%s">%s</label>';
	}
	
	protected function getOptions(): string {
		
		// our filter has either grouped or ungrouped options based on
		// the the state of our $this->grouped property.  we'll call one
		// of the two methods below to help keep this one as clean as
		// possible.
		
		return !$this->grouped
			? $this->getUngroupedOptions()
			: $this->getGroupedOptions();
	}
	
	protected function getUngroupedOptions(array $options = []): string {
		
		// ungrouped options are easy:  they're just <option> elements
		// though we should check to see which one is selected based on
		// the $value property inherited from our parent.  the $options
		// argument is optional because this function may be used for
		// completely ungrouped options or to create teh list of options
		// within a group.  so, if we haven't been passed a set of
		// grouped options from the next method, then we'll just begin
		// with our complete set of values.
		
		if (sizeof($options) === 0) {
			$options = $this->values;
		}
		
		$temp = [];
		$format = $this->getOptionFormat();
		foreach ($options as $value => $display) {
			list($title, $text) = $this->getOptionDisplay($display);
			$option = sprintf($format, $value, $title, $text);
			
			// if the value for this option matches the value that should
			// be selected at this time, then we'll want to add the selected
			// property to our $option.  because PHPStorm doesn't like when
			// we have a random %s hanging out in our $format, we'll just do
			// a string replace here as follows to help keep our IDE happy.
			
			if ($value === $this->value) {
				$option = str_replace("value", "selected value", $option);
			}
			
			$temp[] = $option;
		}
		
		return join($this->getOptionSeparator(), $temp);
	}
	
	protected function getOptionFormat(): string {
		return '<option value="%s" title="%s">%s</option>';
	}
	
	/**
	 * @param string $display
	 *
	 * @return array
	 * @throws SearchbarException
	 */
	protected function getOptionDisplay(string $display): array {
		
		// usually, we just have the text for our option in $display.
		// but, sometimes we get a JSON string with a title and text for
		// it.  here we determine which is which.
		
		$temp = json_decode($display, true);
		if (json_last_error() === JSON_ERROR_NONE && !is_numeric($display)) {
			
			// then we'd better have a title and text within our $temp
			// array.  if not, we'll throw Exceptions.
			
			if (!isset($temp["title"])) {
				throw new SearchbarException("Option title missing",
					SearchbarException::MISSING_OPTION_TITLE);
			}
			
			if (!isset($temp["text"])) {
				throw new SearchbarException("Option text missing",
					SearchbarException::MISSING_OPTION_TEXT);
			}
		} else {
			
			// if we didn't have JSON, then we'll create an array with
			// the information we did get here.  this, then, matches what
			// we would have created via JSON so that our return statement
			// is easy.
			
			$temp = [
				"text"  => $display,
				"title" => "",
			];
		}
		
		return [ $temp["title"], $temp["text"] ];
	}
	
	protected function getOptionSeparator(): string {
		
		// by default, our options should simply be <option> elements
		// next to each other.  but just in case we, someday, don't use
		// a <select> element to do our work here, we want to make it
		// easy to alter this separator.
		
		return "";
	}
	
	protected function getGroupedOptions(): string {
		
		// for grouped options, the outer most indices in our $values
		// property are the labels of our groups.  then, the array at those
		// indices are the actual options.  we can use this method in
		// conjunction with the one above to set up our groups.
		
		$groups = [];
		$format = $this->getOptionGroupFormat();
		foreach ($this->values as $groupLabel => $options) {
			$options = $this->getUngroupedOptions($options);
			$groups[] = sprintf($format, $groupLabel, $options);
		}
		
		return join($this->getOptionGroupSeparator(), $groups);
	}
	
	protected function getOptionGroupFormat(): string {
		
		// option groups are simple: a label and a space for the options
		// they contain:
		
		return '<optgroup label="%s">%s</optgroup>';
	}
	
	protected function getOptionGroupSeparator(): string {
		
		// like the option separator function above, this one is here
		// so that future development that doesn't use a <select> element
		// for filters can alter this in a simple way.
		
		return '';
	}
}
