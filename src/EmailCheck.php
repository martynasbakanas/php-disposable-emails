<?php

namespace MartynasBakanas\PHPDisposableEmails;

use InvalidArgumentException;

class EmailCheck
{
    protected string $blockedEmailsPath;

    public function __construct()
    {
        $this->blockedEmailsPath = dirname(__DIR__) . '/blacklist.conf';
    }

    public function isValid(?string $email, bool $throwException = false): bool
    {
        if (empty($email)) {
            if ($throwException) {
                throw new InvalidArgumentException('Email address is empty');
            }

            return false;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            if ($throwException) {
                throw new InvalidArgumentException('Invalid email address');
            }

            return false;
        }

        $domain = explode('@', $email)[1];

        if ($this->isBlacklisted($domain)) {
            if ($throwException) {
                throw new InvalidArgumentException(sprintf('Email address is blacklisted: %s', $email));
            }

            return false;
        }

        return true;
    }

    private function isBlacklisted(string $domain): bool
    {
        return in_array($domain, $this->getBlackListedDomains());
    }

    public function getBlackListedDomains(): array
    {
        return file($this->blockedEmailsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);;
    }

}
