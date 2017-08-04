<?php

namespace App\Models;

use App\Exceptions\InvalidEmailFormatException;

/**
 * Class User
 * @package App\Models
 */
class User
{
    protected $firstName;
    protected $lastName;
    protected $email;

    /**
     * @param $firstName
     * @return User
     */
    public function setFirstName($firstName): User
    {
        $this->firstName = trim($firstName);

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param $lastname
     * @return User
     */
    public function setLastName($lastname): User
    {
        $this->lastName = trim($lastname);

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     * @throws InvalidEmailFormatException
     */
    public function setEmail($email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailFormatException("Email is not valid");
        }

        $this->email = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmailVariables(): array
    {
        return [
            'full_name' => $this->getFullName(),
            'email' => $this->getEmail(),
        ];
    }
}
