<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Database\Conventionals;

use Nette\Database\Reflection;


class DiscoverConventionals implements IConventionals
{

	protected $databaseReflection;

	public function __construct(Reflection\IDatabaseReflection $databaseReflection)
	{
		$this->databaseReflection = $databaseReflection;
	}

	function getPrimaryKey($table)
	{
		return $this->databaseReflection->getPrimary($table);
	}

	function getHasManyReference($table, $key)
	{
		try {
			return $this->databaseReflection->getHasManyReference($table, $key);
		} catch (Reflection\MissingReferenceException $e) {
			if (!$this->databaseReflection->isRebuilded()) {
				$this->databaseReflection->rebuild();

				return $this->getHasManyReference($table, $key);
			} else {
				throw $e;
			}
		}
	}

	function getBelongsToReference($table, $key)
	{
		try {
			return $this->databaseReflection->getBelongsToReference($table, $key);
		} catch (Reflection\MissingReferenceException $e) {
			if (!$this->databaseReflection->isRebuilded()) {
				$this->databaseReflection->rebuild();

				return $this->getBelongsToReference($table, $key);
			} else {
				throw $e;
			}
		}
	}

}
