<?php

namespace Dashifen\Searchbar;

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
		
		// before we construct our search element, we want to get an ID that
		// we use for both the label and the actual input element.  then, we
		// can construct our label and find a default value for the input.
		
		$id = $this->getId("search", $for);
		$label = $this->getLabel($id, $label, $class);
		$value = $this->getValue($for);
		
		// now, we want to use those to build our search element.  this is
		// a fairly straightforward use of sprintf() and then we can add it
		// to this row within the elements property.
		
		$format = <<<FORMAT
			<li>
				%s
				<input type="text" id="%s" value="%s">
			</li>
FORMAT;

		$search = sprintf($format, $label, $id, $value);
		$this->elements[$this->index][] = $search;
	}
	
	protected function getId(string $type, string $for): string {
		return "$type-$for";
	}
	
	protected function getLabel(string $for, string $display, string $class): string {
		
		// all we do here is construct and HTML <label> element for use within
		// the form that is our searchbar.  the only thing that's different is
		// that our toggles have a different format than the ones for searches
		// and filters.
		
		return strpos($for, "toggle") !== false
			? $this->getToggleLabel($for, $display, $class)
			: $this->getOtherLabel($for, $display, $class);
	}
	
	protected function getToggleLabel(string $for, string $display, string $class): string {
		
		// a toggle's label is the bold-face text that comes after the checkbox.
		// so, we can simply arrange a <strong> tag as follows which we'll then
		// return.
		
		return sprintf('<strong data-for="%s" class="%s">%s</strong>', $for, $class, $display);
	}
	
	protected function getOtherLabel(string $for, string $display, string $class): string {
		
		// for the labels on searches and filters, we need a bit more to make
		// them valid HTML.  first, we'll see if it's a filter or a search as
		// this alters the $display we put in the label a bit.
		
		$type = strpos($for, "search") !== false ? "search" : "filter";
		
		$display = sprintf('<em>%s</em><span> %s</span>',
			($type === "search" ? "Search" : "Show"),
			($type === "search" ? "within $display" : $display)
		);
		
		return sprintf('<label for="%s" class="%s">%s</label>', $for, $class, $display);
	}
	
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
		
		// toggles are checkboxes, but they're constructed in mostly the same
		// way as search elements above.  we'll get our id, label, and value
		// and then use a specific format and sprintf() to build our actual
		// element.
		
		$id = $this->getId("toggle", $for);
		$label = $this->getLabel($id, $label, $class);
		
		// here's a difference from above:  our value isn't actually the
		// value of our checkbox but rather its checked state.  therefore
		// we'll want to default it to false using the method above.
		
		$checked = $this->getValue($for, false) !== false ? "checked" : "";
		
		$format = <<<FORMAT
			<li>
				<label class="toggle">
					<input type="checkbox" id="%s" %s> %s
				</label>
			</li>
FORMAT;

		$toggle = sprintf($format, $id, $checked, $label);
		$this->elements[$this->index][] = $toggle;
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
		
		// a filter is represented by a <select> element containing our
		// options.  however, options might be single or multidimensional
		// representing the possibility for option groups within the
		// element.  for now, we don't we only handle single and 2D arrays.
		
		$depth = $this->getArrayDepth($options);
		
		switch ($depth) {
			case 1:
				$this->addUngroupedFilter($label, $for, $options, $class, $defaultText);
				break;
				
			case 2:
				$this->addGroupedFilter($label, $for, $options, $class, $defaultText);
				break;
				
			default:
				throw new SearchbarException("Invalid Depth ($depth)");
				break;
		}
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
		
		return (int) ceil(($max_indentation - 1) / 2) + 1;
	}
	
	protected function addUngroupedFilter(string $label, string $for, array $options, string $class = "", string $defaultText = ""): void {
		
		// the complexity of <select> elements means that this one is easier
		// to do with output buffering instead of sprintf() as we did above.
		
		$id = $this->getId("filter", $for);
		$value = $this->getValue($for);
		ob_start(); ?>

		<li>
			<?= $this->getLabel($id, $label, $class); ?>
			<select id="<?= $id ?>">
				<option value="all"><?= empty($defaultText) ? "All $label" : $defaultText ?></option>
				
				<?php foreach ($options as $option_value => $option) {
					if (is_array($option)) {
						throw new SearchbarException("Cannot add groups to ungrouped filter.");
					} ?>
				
					<option value="<?= $option_value ?>" <?= $option_value === $value ? "selected" : ""?>><?= $option ?></option>
				<?php } ?>
			</select>
		</li>

		
		<?php $this->elements[$this->index][] = ob_get_clean();
	}
	
	protected function addGroupedFilter(string $label, string $for, array $options, string $class = "", string $defaultText = ""): void {
		
		// like the prior method, we're going to use output buffering here,
		// too.  especially because of the loops below, using sprintf would
		// be possible, but likely far more complex.
		
		$id = $this->getId("filter", $for);
		$value = $this->getValue($for);
		ob_start(); ?>

		<li>
			<?= $this->getLabel($id, $label, $class); ?>
			<select id="<?= $id ?>">
				<option value="all"><?= empty($defaultText) ? "All $label" : $defaultText ?></option>
				
				<?php foreach ($options as $group => $group_options) {
					if (!is_array($group_options)) {
						throw new SearchbarException("Must add groups to grouped filter.");
					} ?>
				
					<optgroup label="<?= $group ?>">
						<?php foreach ($group_options as $option_value => $option) {
							if (is_array($option)) {
								throw new SearchbarException("Grouped filters limited to two dimensions.");
							} ?>
							
							<option value="<?= $option_value ?>" <?= $option_value === $value ? "selected" : ""?>><?= $option ?></option>
						<?php } ?>
					</optgroup>
				<?php } ?>
			</select>
		</li>

		<?php $this->elements[$this->index][] = ob_get_clean();
	}
	
	/**
	 * @return string
	 */
	public function getBar(): string {
		if (sizeof($this->elements) === 0) {
			return "";
		}
		
		ob_start(); ?>

		<form class="searchbar">
		<fieldset data-searchbar="1">
		<legend class="visuallyhidden">
			<label>Use these fields to search within and/or filter the information below.</label>
		</legend>
		
		<?php foreach ($this->elements as $index => $elements) { ?>
		
			<ol>
				<?php foreach ($elements as $element) {
					echo $element;
				}
				
				if ($index === 0) { ?>
					<li>
						<button type="reset">
							<i class="fa fa-fw fa-undo" aria-hidden="true"></i>
							Reset
						</button>
					</li>
				<?php } ?>
			</ol>
		
		<?php } ?>
		
		</fieldset>
		</form>

		<?php return ob_get_clean();
	}
}
