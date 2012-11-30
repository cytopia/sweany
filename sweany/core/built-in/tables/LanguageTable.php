<?php
class LanguageTable extends Table
{
	// TABLE
	public $table;
	public $alias	= 'Language';

	// FIELDS
	public $fields	= array(
		'id',
		'group',
		'text',
		'language',
	);

	public $order = array('`group`' => 'ASC');


	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->table = \Sweany\Settings::tblLang;
	}


	/************************************************** OVERRIDES **************************************************/

	public function loadBySection($language)
	{
		// TODO: need to know what language to translate to!!!
		$section	= \Sweany\Url::getController().'/'.\Sweany\Url::getMethod();

		$callback = function($row, &$data)
		{
			$orig		 = $row['orig'];
			$data[$orig] = $row['text'];
		};

		$query = array(
			'SELECT
				def.`text` AS orig,
				IFNULL(curr.`text`, def.`text`) AS `text`
			FROM
				`::table` AS def
			LEFT JOIN
				`::table` AS curr
			ON
				def.`group` = curr.`group` AND
				def.`language` = :def_lang AND
				curr.`language` = :curr_lang
			WHERE def.`group` IN (SELECT `group` FROM `::tbl_sec` WHERE url = :url)
			GROUP BY
				def.`group`',
			array(
				'::table'	=> $this->table,
				':def_lang' => 'def',
				':curr_lang'=> $language,
				'::tbl_sec'	=> \Sweany\Settings::tblLangSections,
				':url'		=> $section,
			),
		);

		$db		= \Sweany\Database::getInstance();
		$query	= $db->prepare($query);
		$result	= $db->select($query, $callback);
		return $result;
	}

	public function learn($text)
	{
		if ($text)
		{
			$db	= \Sweany\Database::getInstance();

			if ( $this->count() )
			{
				// This query only works if at least one record exists in the table
				$query = array(
					'INSERT INTO `::table`
					(`group`, `text`)
						SELECT
							(SELECT MAX(`group`)+1 FROM `::table`),
							:text
						FROM
							`::table`
						WHERE NOT EXISTS (
							SELECT * FROM `::table`
						  WHERE `text`=:text AND `language`="def"
						)
					LIMIT 1',
					array(
						'::table'	=> $this->table,
						':text'		=> $text
					)
				);

				$query	= $db->prepare($query);
				$result	= $db->select($query);
			}
			else
			{
				$this->save(array('group' => 1, 'text' => $text));
			}

			$group		= $this->getGroupNumber($text);
			$section	= \Sweany\Url::getController().'/'.\Sweany\Url::getMethod();

			if ( $db->count(\Sweany\Settings::tblLangSections, null) )
			{
				// This query only works if at least one record exists in the table
				$query = array(
					'INSERT INTO `::tbl_sec`
					(`group`, `url`)
						SELECT
							:group,
							:url
						FROM
							`::tbl_sec`
						WHERE NOT EXISTS (
							SELECT * FROM `::tbl_sec`
						  WHERE `group`=:group AND `url`=:url
						)
					LIMIT 1',
					array(
						'::tbl_sec'	=> \Sweany\Settings::tblLangSections,
						':group'	=> $group,
						':url'		=> $section
					)
				);

				$query	= $db->prepare($query);
				$result	= $db->select($query);
			}
			else
			{
				$db->insert(\Sweany\Settings::tblLangSections, array('group' => $group, 'url' => $section), null);
			}
		}
	}

	private function getGroupNumber($text)
	{
		$condition = array('
			`text` = :text AND
			`language` = :lang',
			array(':text' => $text, ':lang' => 'def')
		);
		return $this->fieldBy($condition, 'group');
	}


	public function loadforAminPanel()
	{
		$options = array(
			'condition'	=> array(
				'Language.language = \'def\''
			),
		);
		$languages = $this->find('all', $options);

		for ($i=0,$size=count($languages); $i<$size; $i++)
		{
			$group = $languages[$i]->group;
			$trans = $this->getTranslationByGroup($group);
			$languages[$i]->translation = new stdClass();
			$languages[$i]->translation = $trans;
			//$trans;
		}

		return $languages;
	}

	private function getTranslationByGroup($group)
	{
		$options = array(
			'condition'	=> array('
				Language.language <>\'def\' AND
				Language.`group` = '.$group
			),
		);
		$trans	= new stdClass();
		$tmp	= $this->find('all', $options);

		foreach ($tmp as $tran)
		{
			$lang = $tran->language;
			$trans->$lang = $tran->text;
		}
		return $trans;
	}

	public function saveTranslations($groups)
	{
		// Note:
		// This is not very performant yet,
		// but for the time being we don't care, as
		// it affects only the admin panel and not the normal site behaviour,
		// so the user is not affected by this performance issue
		foreach ($groups as $group => $translations)
		{
			foreach ($translations as $lang => $value)
			{
				$existCondition = array('
					`group` = :group AND
					`language` = :lang',
					array(':group' => $group, ':lang' => $lang),
				);

				// The data to be saved/updated
				$data = array(
					'group'		=> $group,
					'text'		=> $value,
					'language'	=> $lang,
				);

				// If an entry already exist, we will update it
				if ( $this->count($existCondition) )
				{
					// Update if there is actually a value
					if ($value) {
						$this->updateAll($existCondition, $data);
					} else { // delete that entry
						$this->deleteAll($existCondition);
					}
				}
				// This is a new entry, so we will insert it
				else
				{
					// Only save, if there is actually some text for the translation
					if ($value) {
						$this->save($data);
					}
				}
			}
		}
	}

	public function createFileCache()
	{

	}
}
