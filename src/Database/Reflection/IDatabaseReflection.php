<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Database\Reflection;

use Nette;


/**
 * Information about tables and columns structure.
 */
interface IDatabaseReflection
{

	public function getPrimary($table);

	public function getHasManyReference($table, $column = NULL);

	public function getBelongsToReference($table, $column = NULL);

	public function rebuild();

	public function isRebuilded();

}
