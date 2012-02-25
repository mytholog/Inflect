<?php
/**
 * @author Igor Gavrilov <igor.gavrilov@softline.ru>
 */
require_once __DIR__ . '/../Inflect.php';

class InflectTest extends PHPUnit_Framework_TestCase {

    protected $obj;

    protected function setUp() {
	$this->obj = new Inflect();
    }

    public function testShouldReturnGenderByMiddleName() {
	$this->assertEquals(Inflect::MALE, $this->obj->getGender('Кац Саша Иванович'));
	$this->assertEquals(Inflect::FEMALE, $this->obj->getGender('Кац Саша Ивановна'));
    }

    /**
     * @dataProvider genderMaleProvider
     */
    public function testShouldReturnMaleGender($a) {
	$this->assertEquals(Inflect::MALE, $this->obj->getGender($a));
    }

    /**
     * @dataProvider genderFemaleProvider
     */
    public function testShouldReturnFemaleGender($a) {
	$this->assertEquals(Inflect::FEMALE, $this->obj->getGender($a));
    }

    /**
     * @dataProvider caseProvider 
     */
    public function testShouldReturnGenitiveName($a, $b) {
	$this->assertEquals($b, $this->obj->getInflectName($a, 0));
    }

    public function caseProvider() {
	return $this->parseFile('case.csv');
    }

    public function genderMaleProvider() {
	return $this->parseFile('m_gender.csv');
    }

    public function genderFemaleProvider() {
	return $this->parseFile('f_gender.csv');
    }

    protected function parseFile($file) {
	$data = array();
	foreach (file(__DIR__ . '/_files/' . $file, FILE_SKIP_EMPTY_LINES) as $line) {
	    $data[] = explode(";", trim($line));
	}

	return $data;
    }

}
