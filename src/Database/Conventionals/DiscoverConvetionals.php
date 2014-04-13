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

	function getHasManyReference($table, $column)
	{
		try {
			$candidates = $columnCandidates = array();

			$tableTargetPairs = $this->databaseReflection->getHasManyReference($table);

			foreach ($tableTargetPairs as $targetPair) {
				list($targetColumn, $targetTable) = $targetPair;
				if (stripos($targetTable, $column) === FALSE) {
					continue;
				}

				if (stripos($targetColumn, $table) !== FALSE) {
					$columnCandidates[] = $candidate = array($targetTable, $targetColumn);
					if (strtolower($targetTable) === strtolower($column)) {
						return $candidate;
					}
				}

				$candidates[] = array($targetTable, $targetColumn);
			}

			if (count($columnCandidates) === 1) {
				return reset($columnCandidates);
			} elseif (count($candidates) === 1) {
				return reset($candidates);
			}

			foreach ($candidates as $candidate) {
				if (strtolower($candidate[0]) === strtolower($column)) {
					return $candidate;
				}
			}

			if (empty($candidates)) {
				throw new Reflection\MissingReferenceException("No reference found for \${$table}->related({$column}).");
			} else {
				throw new Reflection\AmbiguousReferenceKeyException('Ambiguous joining column in related call.');
			}
		} catch (Reflection\MissingReferenceException $e) {
			if (!$this->databaseReflection->isRebuilded()) {
				$this->databaseReflection->rebuild();

				return $this->getHasManyReference($table, $column);
			} else {
				throw $e;
			}
		}
	}

	function getBelongsToReference($table, $column)
	{
		try {
			$tableColumns = $this->databaseReflection->getBelongsToReference($table);

			foreach ($tableColumns as $column => $targetTable) {
				if (stripos($column, $column) !== FALSE) {
					return array($targetTable, $column);
				}
			}

			throw new Reflection\MissingReferenceException("No reference found for \${$table}->{$column}.");
		} catch (Reflection\MissingReferenceException $e) {
			if (!$this->databaseReflection->isRebuilded()) {
				$this->databaseReflection->rebuild();

				return $this->getBelongsToReference($table, $column);
			} else {
				throw $e;
			}
		}
	}

}
