<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural rules for Arabic language
 * 
 * Locales: ar
 * 
 * Languages:
 * - Arabic (ar)
 * 
 * Rules:
 * 	zero → n is 0;
 * 	one → n is 1;
 * 	two → n is 2;
 * 	few → n mod 100 in 3..10;
 * 	many → n mod 100 in 11..99;
 * 	other → everything else
 * 
 * Reference CLDR Version 21 (2012-03-01 03:27:30 GMT)
 * @see  http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html
 * @see  http://unicode.org/repos/cldr/trunk/common/supplemental/plurals.xml
 * @see  plurals.xml (local copy)
 * 
 * @package    I18n_Plural
 * @category   Plural Rules
 * @author     Korney Czukowski
 * @copyright  (c) 2012 Korney Czukowski
 * @license    MIT License
 */
class I18n_Plural_Arabic implements I18n_Plural_Interface
{
	public function plural_category($count)
	{
		if ($count == 0)
		{
			return 'zero';
		}
		elseif ($count == 1)
		{
			return 'one';
		}
		elseif ($count == 2)
		{
			return 'two';
		}
		elseif (is_int($count) AND ($i = $count % 100) >= 3 AND $i <= 10)
		{
			return 'few';
		}
		elseif (is_int($count) AND ($i = $count % 100) >= 11 AND $i <= 99)
		{
			return 'many';
		}
		else
		{
			return 'other';
        }
	}
}