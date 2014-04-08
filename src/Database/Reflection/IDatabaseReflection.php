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
interface IDatabaseReflection extends Nette\Database\IReflection
{

	public function rebuild();

	public function isRebuilded();

}
