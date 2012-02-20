<?php
/**
 * @author Igor Gavrilov <igor.gavrilov@softline.ru>
 */

require_once dirname(__FILE__) . '/../Inflect.php';

class InflectTest extends PHPUnit_Framework_TestCase {

	protected $object;

	protected function setUp() {
		$this->object = new Inflect();
	}

	public function testShouldReturnGenderByMiddleName() {
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Иванов Иван Иванович'));
		$this->assertEquals(Inflect::FEMALE, $this->object->getGender('Иванова Ирина Ивановна'));
	}

	public function testShouldReturnGenderByLastName() {
		$this->assertEquals(Inflect::FEMALE, $this->object->getGender('Иванова Ирина'));
		$this->assertEquals(Inflect::FEMALE, $this->object->getGender('Гагарина Наталья'));
		$this->assertEquals(Inflect::FEMALE, $this->object->getGender('Васильева Наталья'));
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Иванов Илья'));
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Галицин Илья'));
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Васильев Алексей'));
	}

	public function testShouldReturnGenderByFirstName() {
		$this->assertEquals(Inflect::FEMALE, $this->object->getGender('Иванова Ирина'));
		$this->assertEquals(Inflect::FEMALE, $this->object->getGender('Иванова Мария'));
		$this->assertEquals(Inflect::FEMALE, $this->object->getGender('Грошь Ольга'));
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Иванов Игорь'));
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Иванов Виталий'));
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Иванов Александр'));
		$this->assertEquals(Inflect::MALE, $this->object->getGender('Грошь Кирилл'));
	}

	public function testShouldReturnGenitiveName() {
		$this->assertEquals('Иванова Ивана Ивановича', $this->object->getInflectName('Иванов Иван Иванович', 0));
		$this->assertEquals('Белой Марии Ивановны', $this->object->getInflectName('Белая Мария Ивановна', 0));
		$this->assertEquals('Ивановой Ольги Ивановны', $this->object->getInflectName('Иванова Ольга Ивановна', 0));
		$this->assertEquals('Иванова Петра Ивановича', $this->object->getInflectName('Иванов Пётр Иванович', 0));
		$this->assertEquals('Волковой Анны Павловны', $this->object->getInflectName('Волкова Анна Павловна', 0));
		$this->assertEquals('Соколовой Инны', $this->object->getInflectName('Соколова Инна', 0));
		$this->assertEquals('Кац Саши', $this->object->getInflectName('Кац Саша', 0));
		$this->assertEquals('Репка Ильи', $this->object->getInflectName('Репка Илья', 0));
		$this->assertEquals('Чайковского Петра Ильича', $this->object->getInflectName('Чайковский Пётр Ильич', 0));
		$this->assertEquals('Ильиных Романа', $this->object->getInflectName('Ильиных Роман', 0));
		$this->assertEquals('Ильина Эдуарда', $this->object->getInflectName('Ильин Эдуард', 0));
		$this->assertEquals('Гроша Кирилла', $this->object->getInflectName('Грош Кирилл', 0));
		$this->assertEquals('Грош Марии', $this->object->getInflectName('Грош Мария', 0));
	}

}

?>
