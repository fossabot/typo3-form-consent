<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "form_consent".
 *
 * Copyright (C) 2022 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace EliasHaeussler\Typo3FormConsent\Tests\Unit\Domain\Model;

use EliasHaeussler\Typo3FormConsent\Domain\Model\Consent;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * ConsentTest
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
class ConsentTest extends UnitTestCase
{
    /**
     * @var Consent
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Consent();
    }

    /**
     * @test
     */
    public function setEmailStoresEmailCorrectly(): void
    {
        $this->subject->setEmail('foo@baz.com');
        $expected = 'foo@baz.com';
        self::assertSame($expected, $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function setDateStoresCreationDateCorrectly(): void
    {
        $date = new \DateTime();
        $this->subject->setDate($date);
        self::assertSame($date, $this->subject->getDate());
    }

    /**
     * @test
     */
    public function setDataStoresUserDataCorrectly(): void
    {
        $expectedJson = '{"foo":"baz"}';
        $expectedArray = json_decode($expectedJson, true);

        $this->subject->setData('{"foo":"baz"}');
        self::assertSame($expectedJson, $this->subject->getData());
        self::assertSame($expectedArray, $this->subject->getDataArray());

        $this->subject->setData(['foo' => 'baz']);
        self::assertSame($expectedJson, $this->subject->getData());
        self::assertSame($expectedArray, $this->subject->getDataArray());
    }

    /**
     * @test
     */
    public function setFormPersistenceIdentifierStoresFormPersistenceIdentifierCorrectly(): void
    {
        $this->subject->setFormPersistenceIdentifier('foo');
        self::assertSame('foo', $this->subject->getFormPersistenceIdentifier());
    }

    /**
     * @test
     */
    public function setApprovedStoresApprovalCorrectly(): void
    {
        $this->subject->setApproved(true);
        self::assertTrue($this->subject->isApproved());
    }

    /**
     * @test
     */
    public function getValidUntilReturnsNullOnInitialObject(): void
    {
        self::assertNull($this->subject->getValidUntil());
    }

    /**
     * @test
     */
    public function setValidUntilStoresLastPossibleApprovalDateCorrectly(): void
    {
        $date = \DateTime::createFromFormat('U', (string)(time() + 86400));
        self::assertInstanceOf(\DateTime::class, $date);
        $this->subject->setValidUntil($date);
        self::assertSame($date, $this->subject->getValidUntil());
    }

    /**
     * @test
     */
    public function setApprovalDateStoresApprovalDateCorrectly(): void
    {
        $date = new \DateTime();
        $this->subject->setApprovalDate($date);
        self::assertSame($date, $this->subject->getApprovalDate());
    }

    /**
     * @test
     */
    public function setValidationHashStoresValidationHashCorrectly(): void
    {
        $this->subject->setValidationHash('dummy');
        $expected = 'dummy';
        self::assertSame($expected, $this->subject->getValidationHash());
    }
}
