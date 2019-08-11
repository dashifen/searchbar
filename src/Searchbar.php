<?php

namespace Dashifen\Searchbar;

class Searchbar extends AbstractSearchbar {
  /**
   * parse
   *
   * This concrete version of our AbstractSearchbar simply throws an exception
   * for this parse.  Useful for situations in which we don't parse data but
   * rather construct a Searchbar using its other methods.
   *
   * @param array $data
   *
   * @return string
   * @throws SearchbarException
   */
  public function parse (array $data): string {
    throw new SearchbarException("Invalid use of parse.", SearchbarException::INVALID_PARSE);
  }

}