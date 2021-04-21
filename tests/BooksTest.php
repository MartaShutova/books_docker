<?php
// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Books;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class BooksTest extends ApiTestCase
{
    // This trait provided by HautelookAliceBundle will take care of refreshing the database content to a known state before each test
    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/books');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one


        $this->assertJsonContains([
            '@context' => '/api/contexts/Books',
            '@id' => '/api/books',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id' => '/api/books?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/books?page=1',
                'hydra:last' => '/api/books?page=4',
                'hydra:next' => '/api/books?page=2',
            ],
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(30, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Books::class);
    }

    public function testCreateBooks(): void
    {
        $response = static::createClient()->request('POST', '/api/books', ['json' => [
            'isbn' => '0099740915',
            'title' => 'The Handmaid\'s Tale',
            'description' => 'Brilliantly conceived and executed, this powerful evocation of twenty-first century America gives full rein to Margaret Atwood\'s devastating irony, wit and astute perception.',
            'author' => 'Margaret Atwood',
            'year' => 1985,
            'price' => '11$',
            'url' => 'https://mk0meaningfullibmht6.kinstacdn.com/wp-content/uploads/2018/07/book-stack.jpg',
            'status' => 1,
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Books',
            '@type' => 'Books',
            'isbn' => '0099740915',
            'title' => 'The Handmaid\'s Tale',
            'description' => 'Brilliantly conceived and executed, this powerful evocation of twenty-first century America gives full rein to Margaret Atwood\'s devastating irony, wit and astute perception.',
            'author' => 'Margaret Atwood',
            'year' => 1985,
            'price' => '11$',
            'url' => 'https://mk0meaningfullibmht6.kinstacdn.com/wp-content/uploads/2018/07/book-stack.jpg',
            'status' => 1,
        ]);
        $this->assertRegExp('~^/api/books/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Books::class);
    }

    public function testUpdateBook(): void
    {
        $client = static::createClient();
        if ($iri = $this->findIriBy(Books::class, ['isbn' => '9798096412982'])) {
            $client->request('PUT', $iri, ['json' => [
                'title' => 'updated title',
            ]]);

            $this->assertResponseIsSuccessful();
            $this->assertJsonContains([
                '@id' => $iri,
                'isbn' => '9798096412982',
                'title' => 'updated title',
            ]);
        }
    }

    public function testDeleteBook(): void
    {
        $client = static::createClient();
        if ($iri = $this->findIriBy(Books::class, ['isbn' => '9798096412982'])) {
            $client->request('DELETE', $iri);

            $this->assertResponseStatusCodeSame(204);
            $this->assertNull(
                // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
                static::$container->get('doctrine')->getRepository(Books::class)->findOneBy(['isbn' => '9781344037075'])
            );
        }
    }
}