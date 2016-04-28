<?php

/**
 * @group regression
 * @covers Database::escape
 * User: dinies
 * Date: 13/04/16
 * Time: 18.00
 */
class EscapeTest extends AbstractTest
{
    protected $reflector;
    protected $property;
    /**
     * @var Database
     */
    protected $alfa_instance;
    protected $sql_create;
    protected $sql_read;
    protected $sql_drop;

    public function setUp()
    {

        $this->reflectedClass = Database::obtain();
        $this->reflector = new ReflectionClass($this->reflectedClass);
        $this->property = $this->reflector->getProperty('instance');
        $this->reflectedClass->close();
        $this->property->setAccessible(true);
        $this->property->setValue($this->reflectedClass, null);
        $this->alfa_instance = $this->reflectedClass->obtain("localhost", "unt_matecat_user", "unt_matecat_user", "unittest_matecat_local");

        $this->sql_create = "CREATE TABLE Phrases( Piece VARCHAR(255) )";
        $this->sql_drop = "DROP TABLE Phrases";
        $this->sql_read = "SELECT * FROM Phrases";



    }

    public function tearDown()
    {

        $this->reflectedClass = Database::obtain("localhost", "unt_matecat_user", "unt_matecat_user", "unittest_matecat_local");
        $this->reflectedClass->close();
        startConnection();
    }

    /**
     * @group regression
     * @covers Database::escape
     * User: dinies
     */
    public function test_escape_with_simple_string()
    {


        $source=<<<LABEL
a wolf isn't a "dog"
LABEL;
        $actual= $this->alfa_instance->escape($source);

        $expected=<<<LABEL
a wolf isn\'t a \"dog\"
LABEL;

        $this->assertEquals($expected,$actual);
    }

    /**
     * @group regression
     * @covers Database::escape
     * User: dinies
     */
    public function test_escape_and_insert_the_result_in_db()
    {

        $source=<<<LABEL
a w''olf "i"sn'''t a' "dog"
LABEL;
        $actual= $this->alfa_instance->escape($source);
        $sql_insert_source_value = "INSERT INTO Phrases (Piece) VALUES ('$actual')";
        $expected_string=<<<LABEL
a w\'\'olf \"i\"sn\'\'\'t a\' \"dog\"
LABEL;
        $this->assertEquals($expected_string,$actual);

        $expected=(array(0 => array("Piece" => $source)));
        $this->alfa_instance->query($this->sql_create);
        $this->alfa_instance->query($sql_insert_source_value);
        $read_result = $this->alfa_instance->query($this->sql_read)->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEquals($expected, $read_result);
        
        $this->alfa_instance->query($this->sql_drop);

    }

}