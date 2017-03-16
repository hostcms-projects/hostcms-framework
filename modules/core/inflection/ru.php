<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Russian inflection.
 *
 * @package HostCMS 6\Core\Inflection
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Core_Inflection_Ru extends Core_Inflection
{
	/**
	 * Array of irregular form singular => plural
	 * @var array
	 */
	static public $pluralIrregular = array();

	/**
	 * Array of irregular form plural => singular
	 * @var array
	 */
	static public $singularIrregular = array();

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		self::$singularIrregular = array_flip(self::$pluralIrregular);
	}

	/**
	 * Get plural form by singular
	 * @param string $word word
	 * @param int $count
	 * @return string
	 */
	protected function _getPlural($word, $count = NULL)
	{
		// Irregular words
		if (isset(self::$pluralIrregular[$word]))
		{
			return self::$pluralIrregular[$word];
		}

		if (is_null($count))
		{
			$word = $word . 'ы';
		}

		/*
		foreach (self::$rules as $pattern => $replacement)
		{
			$word = preg_replace($pattern, $replacement, $word, 1, $replaceCount);

            if ($replaceCount)
			{
                return $word;
            }
		}*/

		return $word;
	}
}