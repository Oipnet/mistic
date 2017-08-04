<?php

use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 */
class UserTest extends TestCase
{

    private $user;

    public function setUp()
    {
        $this->user = new \App\Models\User;
    }

    /**
     * @test
     */
    public function that_we_can_get_first_name()
    {
        $this->user->setFirstName('John');

        $this->assertEquals($this->user->getFirstName(), 'John');
    }

    /**
     * @test
     */
    public function that_we_can_get_last_name()
    {
        $this->user->setLastName('Doe');

        $this->assertEquals($this->user->getLastName(), 'Doe');
    }

    /**
     * @test
     */
    public function that_we_can_get_full_name()
    {
        $this->user->setLastName('Doe');
        $this->user->setFirstName('John');

        $this->assertEquals($this->user->getFullName(), 'John Doe');
    }

    /**
     * @test
     */
    public function that_first_and_last_name_are_trim()
    {
        $this->user->setLastName('   Doe   ');
        $this->user->setFirstName('  John  ');

        $this->assertEquals($this->user->getFirstName(), 'John');
        $this->assertEquals($this->user->getLastName(), 'Doe');
    }

    /**
     * @test
     */
    public function that_we_can_get_email()
    {
        $this->user->setEmail('john@doe.com');

        $this->assertEquals($this->user->getEmail(), 'john@doe.com');
    }

    /**
     * @test
     */
    public function that_email_variables_contain_correct_values()
    {
        $this->user->setLastName('Doe');
        $this->user->setFirstName('John');
        $this->user->setEmail('john@doe.com');

        $emailVariables = $this->user->getEmailVariables();

        $this->assertArrayHasKey('full_name', $emailVariables);
        $this->assertArrayHasKey('email', $emailVariables);

        $this->assertEquals($emailVariables['full_name'], 'John Doe');
        $this->assertEquals($emailVariables['email'], 'john@doe.com');
    }

    /**
     * @test
     * @expectedException \App\Exceptions\InvalidEmailFormatException
     */
    public function that_email_should_be_valid()
    {
        $this->user->setEmail('john.doe.com');
    }
}