<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserAPITest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test get all users from database
     *
     * @return void
     */
    public function testGetAllUsers()
    {
        // create users
        $usersToCreate = 3;
        User::factory()->count($usersToCreate)->create();

        $response = $this->get('api/users');
        $response->assertStatus(200);

        $content = json_decode($response->getContent());
        $this->assertEquals("OK", $content->status);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => []
        ]);

        $this->assertEquals($usersToCreate, count($content->data));
    }

    /**
     * Test get one users from database
     *
     * @return void
     */
    public function testGetOneUser()
    {
        // create users
        $user = User::factory()->create();

        $response = $this->get('api/users/' . $user->id);
        $response->assertStatus(200);

        $content = json_decode($response->getContent());
        $this->assertEquals("OK", $content->status);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => []
        ]);
    }

    /**
     * Test store new user
     *
     * @return void
     */
    public function testStoreNewUser()
    {
        // create user, without persisting to DB
        $user = $factoryUser = User::factory()->make()->toArray();
        $user["password"] = "Password12345";

        $response = $this->post('api/users', $user);
        $response->assertStatus(200);

        $content = json_decode($response->getContent());
        $this->assertEquals("OK", $content->status);

        // we need to assert without the password key because password is changed (encrypted) on saving to the DB
        // $factoryUser dosen't contain the password key, 
        $this->assertDatabaseHas('users', $factoryUser);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'phone_number',
                'updated_at',
                'created_at',
            ]
        ]);
    }

    /**
     * Test update user
     *
     * @return void
     */
    public function testUpdateUser()
    {
        // create user
        $user = $factoryUser = User::factory()->create();

        // update the first_name and email
        $dataToUpdate = [
            "first_name" => "Newname",
            "email"     => "kennyozordi@gmail.com"
        ];
        $response = $this->put('api/users/' . $user->id, $dataToUpdate);

        $response->assertStatus(200);

        $content = json_decode($response->getContent());
        $this->assertEquals("OK", $content->status);

        //assert if user details was updated
        $this->assertNotEquals($user->first_name, $content->data->first_name);
        $this->assertNotEquals($user->email, $content->data->email);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'phone_number',
                'updated_at',
                'created_at',
            ]
        ]);
    }

    /**
     * Test toggle user
     *
     * @return void
     */
    public function testToggleUser()
    {
        // create user
        $user = User::factory()->create();

        // toggle the user
        $response = $this->post('api/users/toggle/' . $user->id);

        $response->assertStatus(200);

        $content = json_decode($response->getContent());
        $this->assertEquals("OK", $content->status);

        //assert if toggle works
        $this->assertNotEquals($user->is_disabled, $content->data->is_disabled);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'phone_number',
                'updated_at',
                'created_at',
            ]
        ]);
    }
}
