<?php

declare(strict_types=1);

/*
 * This file is part of the Hazelchat extension for TYPO3 sites.
 *
 * (C) 2026 Hazelchat. License: GNU General Public License version 2 or
 * later; see LICENSE.txt.
 */

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use TYPO3\CMS\Core\Type\Map;

// Only matters where the site's frontend Content-Security-Policy is on (it
// is off by default): the chat's script, its frame and its requests come
// from app.hazelchat.co. The backend policy is left alone.
$hazelchat = new UriValue('https://app.hazelchat.co');

return Map::fromEntries([
    Scope::frontend(),
    new MutationCollection(
        new Mutation(MutationMode::Extend, Directive::ScriptSrc, $hazelchat),
        new Mutation(MutationMode::Extend, Directive::FrameSrc, $hazelchat),
        new Mutation(MutationMode::Extend, Directive::ConnectSrc, $hazelchat),
    ),
]);
