<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cant_get_all_books()
    {
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

    /** @test */
    public function cant_get_one_books()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    public function cant_create_book()
    {
        $responseError = $this->postJson(route('books.store'), [
        ]);

        $responseError->assertJsonValidationErrorFor('title');

        $response = $this->postJson(route('books.store'), [
            'title' => 'Mi nuevo Libro'
        ]);

        $response->assertJsonFragment([
            'title' => 'Mi nuevo Libro'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Mi nuevo Libro'
        ]);
    }

    /** @test */
    public function cant_update_book()
    {
        $book = Book::factory()->create();

        $responseError = $this->patchJson(route('books.update', $book), [
        ]);

        $responseError->assertJsonValidationErrorFor('title');

        $response = $this->patchJson(route('books.update', $book), [
            'title' => 'Editando Libro'
        ]);

        $response->assertJsonFragment([
            'title' => 'Editando Libro'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Editando Libro'
        ]);
    }

    /** @test */
    public function cant_delete_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson(route('books.destroy', $book));

        $response->assertNoContent();

        $this->assertDatabaseCount('books',0);
    }
}
