<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase as TestsTestCase;

class UserTest extends TestsTestCase
{
    use RefreshDatabase;

    /**
     * Test to create new user
     *
     * @return void
     */
    public function testCreateUser()
    {
        $userToBeCreated = [
            "first_name"    => "Ken",
            "middle_name"   => "Silas",
            "last_name"     => "Ozordi",
            "email"         => "kennyozordi@gmail.com",
            "phone_number"  => "09021930000",
            "password"      => "Password12345",
        ];

        $user = User::create($userToBeCreated);

        // assert if details was saved to database
        $this->assertDatabaseHas('users', $userToBeCreated);
    }

    /**
     * Test to update user
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $userToBeCreated = [
            "first_name"    => "Ken",
            "middle_name"   => "Silas",
            "last_name"     => "Ozordi",
            "email"         => "kennyozordi@gmail.com",
            "phone_number"  => "09021930000",
            "password"      => "Password12345",
        ];

        $user = User::create($userToBeCreated);

        // update the first_name and lastname
        $dataToUpdate = [
            "first_name" => "Newname",
            "email"     => "kennyozordi@test.com"
        ];

        $user->update($dataToUpdate);
        $updatedUser = User::find($user->id);

        //assert if user details was updated
        $this->assertNotEquals($updatedUser->first_name, $userToBeCreated['first_name']);
        $this->assertNotEquals($updatedUser->email, $userToBeCreated['email']);

        //assert if updated values are in database
        $this->assertDatabaseHas('users', $dataToUpdate);
    }
}
