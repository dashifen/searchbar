<?php

namespace Dashifen\Searchbar;

use Dashifen\Exception\Exception;

class SearchbarException extends Exception {
	public const MISSING_OPTION_TITLE = 1;
	public const MISSING_OPTION_TEXT = 2;
	public const VALUES_TOO_DEEP = 3;
}
