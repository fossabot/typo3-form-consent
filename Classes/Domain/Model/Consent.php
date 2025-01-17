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

namespace EliasHaeussler\Typo3FormConsent\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Consent
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
class Consent extends AbstractEntity
{
    public const TABLE_NAME = 'tx_formconsent_domain_model_consent';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $data = '';

    /**
     * @var string
     */
    protected $formPersistenceIdentifier = '';

    /**
     * @var bool
     */
    protected $approved = false;

    /**
     * @var \DateTime|null
     */
    protected $validUntil;

    /**
     * @var \DateTime|null
     */
    protected $approvalDate;

    /**
     * @var string
     */
    protected $validationHash = '';

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDataArray(): array
    {
        return json_decode($this->data, true) ?: [];
    }

    /**
     * @param string|array<string, mixed> $data
     */
    public function setData($data): self
    {
        if (\is_array($data)) {
            $data = json_encode($data) ?: '';
        }
        $this->data = (string)$data;
        return $this;
    }

    public function getFormPersistenceIdentifier(): string
    {
        return $this->formPersistenceIdentifier;
    }

    public function setFormPersistenceIdentifier(string $formPersistenceIdentifier): self
    {
        $this->formPersistenceIdentifier = $formPersistenceIdentifier;
        return $this;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;
        return $this;
    }

    public function getValidUntil(): ?\DateTime
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTime $validUntil): self
    {
        $this->validUntil = $validUntil;
        return $this;
    }

    public function getApprovalDate(): ?\DateTime
    {
        return $this->approvalDate;
    }

    public function setApprovalDate(?\DateTime $approvalDate): self
    {
        $this->approvalDate = $approvalDate;
        return $this;
    }

    public function getValidationHash(): string
    {
        return $this->validationHash;
    }

    public function setValidationHash(string $validationHash): self
    {
        $this->validationHash = $validationHash;
        return $this;
    }
}
