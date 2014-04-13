<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Database\Conventionals;


interface IConventionals
{

	function getPrimaryKey($table);

	function getHasManyReference($table, $column);

	function getBelongsToReference($table, $column);

}
