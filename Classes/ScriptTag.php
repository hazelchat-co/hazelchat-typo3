<?php

declare(strict_types=1);

/*
 * This file is part of the Hazelchat extension for TYPO3 sites.
 *
 * (C) 2026 Hazelchat. License: GNU General Public License version 2 or
 * later; see LICENSE.txt.
 */

namespace Hazelchat\Typo3;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Set\SetRegistry;

/**
 * Adds the Hazelchat chat to the pages of every site that uses the
 * Hazelchat site set: one script from app.hazelchat.co with that site's
 * public widget key, rendered by TYPO3 before </body>.
 *
 * It listens to TYPO3's own script rendering instead of adding TypoScript,
 * so a TypoScript record with "Clear setup" (which TYPO3's installer ticks)
 * cannot drop it. The tag is part of the generated page and so of the page
 * cache. Nothing is sent from TYPO3 to Hazelchat, nothing is stored but the
 * two site settings, and the backend never loads the chat.
 */
#[AsEventListener('hazelchat/script-tag')]
final class ScriptTag
{
    public const SET = 'hazelchat/hazelchat';

    public function __construct(private readonly SetRegistry $setRegistry) {}

    public function __invoke(BeforeJavaScriptsRenderingEvent $event): void
    {
        // Once per page: the external, non-priority scripts.
        if ($event->isInline() || $event->isPriority()) {
            return;
        }
        // TYPO3's PageRenderer reads the request the same way.
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if (!$request instanceof ServerRequestInterface || !ApplicationType::fromRequest($request)->isFrontend()) {
            return;
        }
        // The site's HTML pages only: feeds, JSON and other page types are left alone.
        $routing = $request->getAttribute('routing');
        if (!$routing instanceof PageArguments || $routing->getPageType() !== '0') {
            return;
        }
        $site = $request->getAttribute('site');
        if (!$site instanceof Site || !$this->usesSet($site)) {
            return;
        }

        $settings = $site->getSettings();
        $attributes = Key::attributes(
            Key::fromInput($settings->get('hazelchat.installationCode', '')),
            (bool)$settings->get('hazelchat.waitForConsent', false)
        );
        if ($attributes === null) {
            return;
        }

        // No 'csp' (14.2+) or 'useNonce' option: on TYPO3 14 it makes the
        // site fetch the remote file to pin its hash, which is an outbound
        // request and breaks when the widget is updated. The extension's
        // Configuration/ContentSecurityPolicies.php allows the address
        // instead. An absolute URL gets no cache-busting query, so the
        // address stays exactly the one Hazelchat's website check looks for.
        // The fixed identifier keeps it to one tag.
        $event->getAssetCollector()->addJavaScript(Key::ASSET_ID, Key::SCRIPT_URL, $attributes);
    }

    /**
     * The set is the switch: settings left in settings.yaml after the set
     * is removed from the site print nothing. A site package may also
     * depend on the set.
     */
    private function usesSet(Site $site): bool
    {
        foreach ($this->setRegistry->getSets(...$site->getSets()) as $set) {
            if ($set->name === self::SET) {
                return true;
            }
        }

        return false;
    }
}
