<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CreatesApplication;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function testStoreSuccessfulResponse(): void
    {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->call('POST', 'api/article/', [
            "title" => "testTitle",
            "content" => "testContent",
            "image" => $file,
            "enabled" => true
        ])
            ->assertStatus(200)
            ->assertJson([
                "title" => "testTitle",
                "content" => "testContent",
                "enabled" => true,
                "image" => time() . ".jpg",
                "created_at" => now(),
                "updated_at" => now()
            ]);

        $this->assertDatabaseHas('articles', [
            "title" => "testTitle",
            "content" => "testContent",
            "enabled" => true,
            "image" => time() . ".jpg",
            "created_at" => now(),
            "updated_at" => now()
        ]);
    }

    public function testShowSuccessfulResponse(): void
    {
        $fake = Article::factory()->create();
        Article::factory()->create();

        $this->call('GET', 'api/articles/' . $fake->id)
            ->assertStatus(200)
            ->assertJson([
                "id" => $fake->id,
                "title" => $fake->title,
                "content" => $fake->content,
                "image" => $fake->image,
                "enabled" => $fake->enabled,
                "created_at" => $fake->created_at,
                "updated_at" => $fake->updated_at
            ]);
    }

    public function testDeleteSuccessfulResponse(): void
    {
        $fake = Article::factory()->create();
        $this->assertDatabaseHas('articles', ["id" => $fake->id]);

        $this->call('DELETE', 'api/articles/' . $fake->id)
            ->assertStatus(200)
            ->assertJson([
                "msg" => "Article deleted successfully."
            ]);
        $this->assertDatabaseMissing('articles', ["id" => $fake->id]);
    }

    public function testUpdateSuccessfulResponse(): void
    {
        Carbon::setTestNow(Carbon::create(2022, 01, 01));
        $fake = Article::factory()->create();
        $this->assertDatabaseHas('articles', ["id" => $fake->id]);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        Carbon::setTestNow();
        $this->call('POST', 'api/articles/' . $fake->id, [
            "title" => "testTitle123",
            "content" => "testContent123",
            "image" => $file,
            "enabled" => false
        ])
            ->assertStatus(200)
            ->assertJson([
                "msg" => "Article updated successfully."
            ]);

        $this->assertDatabaseHas('articles', [
            "title" => "testTitle123",
            "content" => "testContent123",
            "enabled" => false,
            "image" => time() . ".jpg",
            "created_at" => $fake->created_at,
            "updated_at" => now()
        ]);
    }

    public function testShowMoreSuccessfulResponse(): void
    {
        $fake = Article::factory()
            ->count(5)
            ->sequence(
                ['title' => '1', 'content' => 'aaa', 'enabled' => true],
                ['title' => '2', 'content' => 'bbb', 'enabled' => true],
                ['title' => '3', 'content' => 'ccc', 'enabled' => true],
                ['title' => '4', 'content' => 'banana', 'enabled' => true],
                ['title' => '5', 'content' => 'cat', 'enabled' => false],
            )
            ->create();

        $first = $fake->where('title', '4')->first();
        $second = $fake->where('title', '1')->first();
        $this->call('GET', 'api/articles/', [
            "search_word" => 'a',
            "sort" => 'title',
            "order" => "desc",
            "page" => 1,
            "per_page" => 3
        ])
            ->assertJson([
                [
                    "id" => $first->id,
                    "title" => $first->title,
                    "content" => $first->content,
                    "image" => $first->image,
                    "enabled" => $first->enabled,
                    "created_at" => Carbon::parse($first->created_at)->format("Y-m-d H:i:s"),
                    "updated_at" => Carbon::parse($first->updated_at)->format("Y-m-d H:i:s")
                ],
                [
                    "id" => $second->id,
                    "title" => $second->title,
                    "content" => $second->content,
                    "image" => $second->image,
                    "enabled" => $second->enabled,
                    "created_at" => Carbon::parse($second->created_at)->format("Y-m-d H:i:s"),
                    "updated_at" => Carbon::parse($second->updated_at)->format("Y-m-d H:i:s")
                ]
            ]);
    }
}
