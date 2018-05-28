<?php

namespace Tests\Unit;

use App\{Post, User};
use Tests\TestCase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PolicyPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    function admins_can_update_post()
    {
        //Arrange

        $admin = $this->createAdmin();

        $this->be($admin);

        $post = factory(Post::class)->create();

        // Act

        $result = Gate::allows('update-post', $post);

        //Assert
        $this->assertTrue($result);
    }

    /** @test */

    function authors_can_update_post()
    {
        //Arrange

        $user = $this->createUser();

        $this->be($user);

        $post = factory(Post::class)->create([
            'user_id' => $user->id,
        ]);

        // Act

        $result = Gate::allows('update-post', $post);

        //Assert
        $this->assertTrue($result);
    }

    /** @test */

    function guests_cannot_update_post()
    {
        //Arrange



        $post = factory(Post::class)->create();

        // Act

        $result = Gate::allows('update-post', $post);

        //Assert
        $this->assertFalse($result);
    }

    /** @test */

    function unathorized_users_cannot_update_post()
    {
        //Arrange

        $user = $this->createUser();

        $post = factory(Post::class)->create();

        // Act

        $result = Gate::forUser($user)->allows('update-post', $post);

        //Assert
        $this->assertFalse($result);
    }

    /** @test */

    function authors_can_update_unplublished_post()
    {

        $user = $this->createUser();

        $post = factory(Post::class)->states('draft')->create([
            'user_id' => $user->id,
        ]);


        $this->assertTrue(Gate::forUser($user)->allows('update-post', $post));
    }

    /** @test */

    function authors_can_delete_unplublished_post()
    {

        $user = $this->createUser();

        $post = factory(Post::class)->states('draft')->create([
            'user_id' => $user->id,
        ]);


        $this->assertTrue(Gate::forUser($user)->allows('delete-post', $post));
    }

    /** @test */

    function admins_can_delete_plublished_post()
    {

        $admin = $this->createAdmin();

        $post = factory(Post::class)->states('published')->create();


        $this->assertTrue(Gate::forUser($admin)->allows('delete-post', $post));
    }

    /** @test */

    function authors_cannot_delete_plublished_post()
    {

        $user = $this->createUser();

        $post = factory(Post::class)->states('published')->create([
            'user_id' => $user->id,
        ]);


        $this->assertFalse(Gate::forUser($user)->allows('delete-post', $post));
    }

}
