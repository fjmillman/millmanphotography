<?php

namespace MillmanPhotography\Tests\Unit\Validator;

use PHPUnit\Framework\TestCase;

use MillmanPhotography\Validator\RegistrationValidator;

class RegistrationValidatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testItPassesWhenNameUsernameAndPasswordAreValid()
    {
        $validator = new RegistrationValidator();
        $data = [
            'username' => 'username',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $this->assertTrue($validator->isValid($data));
        $this->assertEmpty($validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenPasswordIsNotIdentical()
    {
        $validator = new RegistrationValidator();
        $data = [
            'username' => 'username',
            'password' => 'password',
            'password_confirmation' => 'passwrd',
        ];
        $this->assertFalse($validator->isValid($data));
        $this->assertSame(['Passwords did not match!'], $validator->getErrors());
    }

    /**
     * @return void
     */
    public function testItFailsWhenPasswordIsTooShort()
    {
        $validator = new RegistrationValidator();
        $data = [
            'username' => 'username',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ];
        $this->assertFalse($validator->isValid($data));
        $this->assertSame(
            [
                'Password: not at least 7 characters',
                'Password Confirmation: not at least 7 characters'
            ],
            $validator->getErrors()
        );
    }
}