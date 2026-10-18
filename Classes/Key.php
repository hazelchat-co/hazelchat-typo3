<?php

declare(strict_types=1);

/*
 * This file is part of the Hazelchat extension for TYPO3 sites.
 *
 * (C) 2026 Hazelchat. License: GNU General Public License version 2 or
 * later; see LICENSE.txt.
 */

namespace Hazelchat\Typo3;

/**
 * The public widget key and the one script tag, as plain functions with no
 * TYPO3 dependency, so they can be tested with PHP alone. The key is public:
 * it names an agent and grants no access to the Hazelchat account.
 */
final class Key
{
    /** The only address this extension ever prints. */
    public const SCRIPT_URL = 'https://app.hazelchat.co/widget.js';

    /** Hazelchat counts installations of this extension by this id. */
    public const SCRIPT_ID = 'hazelchat-typo3';

    /** The AssetCollector identifier: the same one always, so one tag per page. */
    public const ASSET_ID = 'hazelchat';

    /** Room for a pasted installation code; the key itself is 64 characters. */
    public const MAX_INPUT = 1000;

    public static function valid(mixed $key): bool
    {
        return \is_string($key) && preg_match('/\A[a-f0-9]{64}\z/', $key) === 1;
    }

    /**
     * The setting takes the whole installation code or just the key. A bare
     * 64-hex value is the key; otherwise the text must hold exactly one
     * data-agent attribute (outside HTML comments) whose value is 64
     * lowercase hex. Anything else gives null, and nothing is printed.
     */
    public static function fromInput(mixed $value): ?string
    {
        if (!\is_string($value)) {
            return null;
        }
        $value = trim($value);
        if ($value === '' || \strlen($value) > self::MAX_INPUT) {
            return null;
        }
        if (self::valid($value)) {
            return $value;
        }

        // A key that is only mentioned in a comment is not the code.
        $value = preg_replace('/<!--.*?-->/s', '', $value) ?? '';
        if (str_contains($value, '<!--')) {
            return null;
        }

        // Exactly one data-agent attribute; look-alikes such as
        // xdata-agent or data-agent-x are not it, and two are ambiguous.
        $attribute = '(?<![A-Za-z0-9_:-])data-agent(?![A-Za-z0-9_:-])\s*=';
        if (preg_match_all('/' . $attribute . '/i', $value) !== 1) {
            return null;
        }
        if (preg_match('/' . $attribute . '\s*(?:"([a-f0-9]{64})"|\'([a-f0-9]{64})\'|([a-f0-9]{64})(?=[\s>\/]|\z))/', $value, $match) !== 1) {
            return null;
        }

        $key = ($match[1] ?? '') . ($match[2] ?? '') . ($match[3] ?? '');

        return self::valid($key) ? $key : null;
    }

    /**
     * The attributes of the script tag, or null when nothing may be printed.
     * No inline code, no data-platform and no address but SCRIPT_URL.
     *
     * @return array<string, string>|null
     */
    public static function attributes(?string $key, bool $requireConsent): ?array
    {
        if (!self::valid($key)) {
            return null;
        }

        $attributes = [
            'id' => self::SCRIPT_ID,
            'data-agent' => $key,
            'defer' => 'defer',
        ];

        if ($requireConsent) {
            $attributes['data-require-consent'] = 'true';
        }

        return $attributes;
    }
}
