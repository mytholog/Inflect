<?php
/**
 * Russian names inflection library 
 * 
 * @author Igor Gavrilov <mytholog@yandex.ru>
 * @link git://github.com/mytholog/Inflect.git
 * @version 0.0.1
 */
class Inflect {
	
	const MALE	= 'male';
	const FEMALE	= 'female';
	const NEUTER	= 'neuter';

	private $firstName;
	private $lastName;
	private $middleName;
	private $gender;

	/**
	 * Выбранный падеж
	 * @var string
	 */
	private $case;

	/*
	 * [part of the name]	=>	[suffix]	=> [genitive, dative, accusative, instrumentative, prepositional]
	 * [часть имени]	=>	[окончание]	=> [родительный, дательный, винительный, творительный, предложный]
	 */
	private $map = array(
		'middle'	=> array(
			'на'			=> array('ны', 'не', 'ну', 'ной', 'не'),
			'ич'			=> array('ича', 'ичу', 'ича', 'ичем', 'иче'),
			'ыч'			=> array('ыча', 'ычу', 'ыча', 'ычем', 'ыче')
		),
		'first'		=> array(
			'ия'			=> array('ии', 'ии', 'ию', 'ией', 'ие'),
			'([гжйкхчшщ])а'		=> array('$1и', '$1е', '$1у', '$1ой', '$1е'),
			'а'			=> array('ы', 'е', 'у', 'ой', 'е'),
			'мя'			=> array('мени', 'мени', 'мя', 'менем', 'мени'),
			'я'			=> array('и', 'е', 'ю', 'ей', 'е'),
			'й'			=> array('я', 'ю', 'я', 'ем', 'е'),
		),
		'first_exp'		=> array(
			'Лев'			=> array('Льва', 'Льву', 'Льва', 'Львом', 'Льве'),
			'Зоя'			=> array('Зои', 'Зое', 'Зою', 'Зоей', 'Зое'),
		),
		'last'		=> array(
			'(ин|ын|ев|ёв|ов)а'	=> array('$1ой', '$1ой', '$1у', '$1ой', '$1ой'),
			'(ин|ын|ев|ёв|ов)'	=> array('$1а', '$1у', '$1а', '$1ым', '$1е'),
			'ая'			=> array('ой', 'ой', 'ую', 'ой', 'ой'),
			'яя'			=> array('ей', 'ей', 'юю', 'ей', 'ей'),
			'кий'			=> array('кого', 'кому', 'кого', 'ким', 'ком'),
			'ий'			=> array('его', 'ему', 'его', 'им', 'ем'),
			'ый'			=> array('ого', 'ому', 'ого', 'ым', 'ом'),
			'ой'			=> array('ого', 'ому', 'ого', 'ым', 'ом'),
		),
		'last_m'	=> array(
			'а'			=> array('ы', 'е', 'у', 'ой', 'е'),
			'мя'			=> array('мени', 'мени', 'мя', 'менем', 'мени'),
			'я'			=> array('и', 'е', 'ю', 'ёй', 'е'),
			'й'			=> array('я', 'ю', 'й', 'ем', 'е'),
			'ь'			=> array('я', 'ю', 'я', 'ем', 'е'),
		)
	);

	private $nounEndingsMap = array(
		self::MALE => array(
			'(к|т)'			=> array('$1а',	'$1у',	'$1а',	'$1ом',	'$1е'),	// техник
			'(ч|он|р|им)'		=> array('$1а',	'$1у',	'$1',	'$1ом',	'$1е'),	// топор
			'(ый)'			=> array('ого',	'ому',	'ого',	'ым',	'ом'),	// учёный
			'(ец)'			=> array('ца',	'цу',	'ца',	'цом',	'це'),	// певец
			'(н|р)ь'		=> array('$1я',	'$1ю',	'$1я',	'$1ем',	'$1е'),	// конь
		),
		self::FEMALE => array(
			'(а)'			=> array('ы',	'е',	'у',	'ой',	'е'),	// машина
			'(я)'			=> array('и',	'е',	'ю',	'ей',	'и'),	// станция
			'(сть)'			=> array('сти',	'сте',	'сть',	'стью',	'сти'),	// пряность
			'(ь)'			=> array('и',	'е',	'ь',	'ью',	'е'),	// мелочь
		),
		self::NEUTER => array(
			'(o)'			=> array('а',	'у',	'о',	'ом',	'е'),	// молоко
			'(ще)'			=> array('ща',	'щу',	'ще',	'щем',	'ще'),	// чудовище
			'(ё)'			=> array('я',	'ю',	'ё',	'ём',	'е'),	// копьё
			'(ие)'			=> array('ия',	'ию',	'ие',	'ием',	'ии'),	// смирение
			'(я)'			=> array('яни',	'яне',	'я',	'енем',	'мени'),// вымя
		),
	);

	public function __construct() {
		mb_internal_encoding("UTF-8");
	}

	/**
	 * Возвращает просклоненное имя в выбранном падеже
	 * 
	 * @param string $fullName Фамилия Имя Отчество
	 * @param int $case Падеж (0 - genitive, 1 - dative, 2 - accusative, 3 - instrumentative, 4 - prepositional)
	 * @return string
	 */
	public function getInflectName($fullName, $case) {
		if (empty($fullName)) {
			return;
		}

		$this->explodeName($fullName);
		$this->gender = $this->getGender();
		$this->case = $case;
		
		$this->processingMiddleName();
		$this->processingFirstName();
		$this->processingLastName();

		 return sprintf(
				'%s%s%s',
				$this->lastName,
				empty($this->firstName) ? '' : ' ' .$this->firstName,
				empty($this->middleName) ? '' : ' ' .$this->middleName
			);
	}

	/**
	 * Возвращает просклоненное существительное в выбранном падеже
	 * 
	 * @param string $noun Существительное
	 * @param int $case Падеж (0 - genitive, 1 - dative, 2 - accusative, 3 - instrumentative, 4 - prepositional)
	 * @return string
	 */
	public function getInflectNoun($noun, $case) {
		if (empty($noun)) {
			return;
		}

		$this->case = $case;
		$this->noun = $noun;
		$this->gender = $this->getNounGender();
		$this->processingNoun();

		return $this->noun;
	}

	/**
	 * Определение пола
	 *
	 * @param string $fullName OPTIONAL Фамилия Имя Отчество
	 * @return string|null 
	 */
	public function getNounGender() {
		switch (true) {
			case preg_match('/(к|ч|он|ый|ст|р|ец|нь|рь|рт|им)$/u', $this->noun):
				return self::MALE;
				break;
			case preg_match('/(а|я|сть|чь)$/u', $this->noun):
				return self::FEMALE;
				break;
			case preg_match('/(о|ще|ё|ие|мя|е)$/u', $this->noun):
				return self::NEUTER;
				break;
			default:
				return self::MALE;
		}
		return null;
	}

	/**
	 * Определение пола
	 *
	 * @param string $fullName OPTIONAL Фамилия Имя Отчество
	 * @return string|null 
	 */
	public function getGender($fullName = null) {
		if (!is_null($fullName)) {
			$this->explodeName($fullName);
		}
		//by MiddleName
		if (isset($this->middleName)) {
			return mb_substr($this->middleName, -2) == 'на' ? self::FEMALE : self::MALE;
		}

		switch (true) {
			//by LastName
			case preg_match('/(ев|ин|ын|ёв|ов)а$/u', $this->lastName):
			case preg_match('/(ая|яя)$/u', $this->lastName):
				return self::FEMALE;
				break;
			case preg_match('/(ев|ин|ын|ёв|ов)$/u', $this->lastName):
			case preg_match('/(ий|ый)$/u', $this->lastName):
				return self::MALE;
				break;
			//by FirstName
			case preg_match('/[ая]$/u', $this->firstName):
				return self::FEMALE;
				break;
			case preg_match('/[^аеёиоуыэюя]$/u', $this->firstName):
				return self::MALE;
				break;
		}
		return null;
	}

	/**
	 * Обработка множеств (склонение существительных после числительных)
	 *
	 * @param array $titles	Варианты слова (час, часа, часов)
	 * @param int $number Число, которое нужно перевести
	 * @param bool $full Если true, то возвращать вместе с цифрой
	 * @return string
	 */
	public function getPlural(array $titles, $number, $full = false) {
	    $result = $titles[(($number % 10 == 1) && ($number % 100 != 11)) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2)];
	    return $full ? $number . ' ' . $result : $result;
	}

	protected function processingLastName() {
		if (!is_null($this->lastName) && !is_null($this->gender)) {
			switch (true) {
				case preg_match('/[еёиоуыэю]$/u', $this->lastName):
				case preg_match('/[аеёиоуыэюя]а$/u', $this->lastName):
				case preg_match('/[ёоуыэю]я$/u', $this->lastName):
				case preg_match('/[иы]х$/u', $this->lastName):
				case $this->replaceProcessing('last', 'lastName'):
				case $this->gender == self::MALE && $this->replaceProcessing('last_m', 'lastName'):
					break;
				case $this->gender == self::MALE:
					$value = array('а', 'у', 'а', 'ом', 'е');
					$this->lastName .= $value[$this->case];
					break;
			}
		}
		return $this;
	}

	protected function processingFirstName() {
		if (!is_null($this->firstName)) {
			$this->firstName = preg_replace('/Пётр$/u', 'Петр', $this->firstName);

			switch (true) {
				case $this->replaceProcessing('first_exp', 'firstName'):
					break;
				case preg_match('/[еёиоуыэю]$/u', $this->firstName):
				case preg_match('/[аеёиоуыэюя]а$/u', $this->firstName):
				case preg_match('/[аёоуыэюя]я$/u', $this->firstName):
				case $this->gender == self::FEMALE && preg_match('/[бвгджзклмнйпрстфхцчшщ]$/u', $this->firstName):
					break;
				case $this->gender == self::MALE && preg_match('/ь$/u', $this->firstName):
					$value = array('я', 'ю', 'я', 'ем', 'е');
					$this->firstName = preg_replace('/ь$/u', $value[$this->case], $this->firstName);
					break;
				case $this->gender == self::FEMALE && preg_match('/ь$/u', $this->firstName):
					$value = array('и', 'и', 'ь', 'ью', 'и');
					$this->firstName = preg_replace('/ь$/u', $value[$this->case], $this->firstName);
					break;
				case $this->replaceProcessing('first', 'firstName'):
				    break;
				default:
					$value = array('а', 'у', 'а', 'ом', 'е');
					$this->firstName .= $value[$this->case];
					break;
			}
		}
		return $this;
	}

	protected function processingMiddleName() {
		if (!is_null($this->middleName)) {
			if($this->replaceProcessing('middle', 'middleName')) {
				return $this;
			}
			$this->middleName = preg_replace('/(Иль|Кузьм|Фом)ичем$/u', '$1ичом', $this->middleName);
		}
		return $this;
	}

	protected function processingNoun() {
		$nounEndingsMap = $this->nounEndingsMap[$this->gender];

		foreach($nounEndingsMap as $pattern => $replacement_array) {
			$count = 0;
			$this->noun = preg_replace('/'.$pattern.'$/u', $replacement_array[$this->case], $this->noun, 1, $count);
			if ($count)
				break;
		}

		return $this;
	}

	/**
	 * @param string $ruleGroup
	 * @param string $field
	 * @return boolean 
	 */
	protected function replaceProcessing($ruleGroup, $field) {
		foreach ($this->map[$ruleGroup] as $pattern => $value) {
			$pattern = '/'.$pattern.'$/u';
			if (preg_match($pattern, $this->{$field})) {
				$this->{$field} = preg_replace($pattern, $value[$this->case], $this->{$field});
				return true;
			}
		}
		return false;
	}

	private function explodeName($fullName) {
		list($this->lastName, $this->firstName, $this->middleName) = explode(' ', ucwords(trim($fullName)));
	}

}
