<?php

namespace PhiSYS\Tests\Unit\Domain\Model\Author;

use PhiSYS\Domain\Model\Author\Event\AuthorWasCreated;
use PhiSYS\Domain\Model\Author\ValueObject\Name;
use PhiSYS\Shared\Domain\DomainModel;
use PhiSYS\Domain\Model\Author\Author;
use PhiSYS\Domain\Model\Author\ValueObject\AuthorId;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function test_given_author_values_when_author_created_then_contains_created_event()
    {
        $author = Author::create(
            $this->createMock(AuthorId::class),
            $this->createMock(Name::class),
        );

        $this->assertInstanceOf(DomainModel::class, $author);
        $this->assertIsArray($author->events());
        $this->assertCount(1, $author->events());
        $this->assertContainsOnlyInstancesOf(AuthorWasCreated::class, $author->events());
    }

    public function test_given_author_values_when_author_hydrated_then_has_no_events()
    {
        $author = Author::from(
            $this->createMock(AuthorId::class),
            $this->createMock(Name::class),
        );

        $this->assertInstanceOf(DomainModel::class, $author);
        $this->assertEmpty($author->events());
    }

    public function test_given_author_when_get_properties_then_return_expected_values()
    {
        $authorId = $this->createMock(AuthorId::class);
        $authorName = $this->createMock(Name::class);
        $author = Author::from($authorId, $authorName);

        $this->assertSame($authorId, $author->id());
        $this->assertSame($authorName, $author->name());
    }

    public function test_given_author_when_json_serialize_then_serializes_as_expected()
    {
        $authorId = $this->createMock(AuthorId::class);
        $authorName = $this->createMock(Name::class);

        $author = Author::from($authorId, $authorName);

        $this->assertEquals(
            [
                'id' => $authorId,
                'name' => $authorName,
            ],
            $author->jsonSerialize(),
        );
    }
}
