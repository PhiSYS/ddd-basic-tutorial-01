<?php

namespace PhiSYS\Tests\Unit\Domain\Model\Book;

use PhiSYS\Domain\DomainModel;
use PhiSYS\Domain\Model\Book\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function test_given_when_then()
    {
        $this->assertInstanceOf(DomainModel::class, new Book());
    }
}