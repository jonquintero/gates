<?php

namespace Tests\Feature;

use App\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

   /** @test */

   function admins_can_create_posts()
   {
       $this->withExceptionHandling();

       $this->actingAs($admin = $this->createAdmin());

       $response = $this->post('admin/posts', [
           'title' => 'New post'
       ]);

       $response->assertStatus(201)->assertSee('Post created');

       $this->assertDatabaseHas('posts',[
           'title' => 'New post',
       ]);

      /* tap(Post::first(), function ($post){
           $this->assertNotNull($post);
           $this->assertSame('New post', $post->title);
       });*/

   }

    /** @test */

    function authors_can_create_posts()
    {
        $this->withExceptionHandling();

        $this->actingAs($user = $this->createUser(['role' => 'author']));

        $response = $this->post('admin/posts', [
            'title' => 'New post'
        ]);

        $response->assertStatus(201)->assertSee('Post created');

        $this->assertDatabaseHas('posts',[
            'title' => 'New post',
        ]);
    }

    /** @test */

    function unauthorized_users_cannot_create_posts()
    {
        $this->withExceptionHandling();

        $this->actingAs($user = $this->createUser(['role' => 'subscriber']));

        $response = $this->post('admin/posts', [
            'title' => 'New post'
        ]);

       /* $response->assertStatus(403)->assertSee('Post created');

        $this->assertDatabaseMissing('posts',[
            'title' => 'New post',
        ]);*/

       $this->assertDatabaseEmpty('posts');
    }
}
