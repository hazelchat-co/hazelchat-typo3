# Hazelchat for TYPO3 sites

Adds your [Hazelchat](https://hazelchat.co/?utm_source=typo3&utm_medium=extension&utm_campaign=readme) AI chat agent to a TYPO3 site from one pasted installation code. The agent answers visitors from the pages you approved, shows its sources and passes on their enquiries.

- TYPO3 13.4 LTS and 14.3 LTS, PHP 8.2 or later.
- Needs a Hazelchat account. The Free plan covers one website and stays free.
- Hosted service: visitors' browsers load the chat from `app.hazelchat.co`, and Hazelchat processes their questions and any enquiry details they send ([privacy notice](https://hazelchat.co/privacy/), [terms](https://hazelchat.co/terms/)). The extension itself sends nothing.

## Install

1. In Hazelchat, add your website and copy its installation code ([create a free account](https://app.hazelchat.co/signup?utm_source=typo3&utm_medium=extension&utm_campaign=readme-signup)).
2. Install the extension: `composer require hazelchat/typo3`, or install it from **Admin Tools > Extensions** (Classic mode).
3. Add the **Hazelchat** set under **Sets for this Site** (TYPO3 13.4: **Site Management > Sites**, edit the site; 14.3: **Sites > Setup**, **Edit site configuration**). Then open the site settings (13.4: **Site Management > Settings**; 14.3: **Sites > Setup**, **Edit site settings**), paste the installation code under **Hazelchat > Installation code** and save. Flush the frontend caches.
4. Back in Hazelchat, click **Publish and switch on**. Hazelchat finds the chat on your live page and verifies the website.

Editing site settings needs an administrator. If you have none, send these steps and your installation code to your agency.

## What it does

- Every page of a site that uses the set gets one tag before `</body>`:
  `<script id="hazelchat-typo3" data-agent="<key>" defer="defer" src="https://app.hazelchat.co/widget.js"></script>`
- **Installation code** takes the pasted code or just its 64-character key. Only a valid key ever reaches a page; anything else prints nothing.
- **Wait for my consent manager** (off) adds `data-require-consent="true"`: the chat starts only once your consent manager allows it. Only the chat waits: browsers still download `widget.js` from `app.hazelchat.co` straight away, so the visitor's IP address reaches Hazelchat before they agree. This is by design. If your site must prevent that too, use a consent tool that can block the script itself. See [Visitor consent](https://hazelchat.co/integrations/typo3/#consent).
- The script tag carries the id `hazelchat-typo3`. When Hazelchat checks your website, it reads this id to record that the chat was installed with this extension, and it counts the extension's installations that way.
- Nothing in the backend, on other page types (feeds, JSON), or on sites without the set. Each site of a multi-site installation uses its own code.
- If your site sends a Content-Security-Policy, the extension allows `https://app.hazelchat.co` in `script-src`, `frame-src` and `connect-src` for the frontend.
- No database tables, no backend module, no scheduler task, no requests from TYPO3 to Hazelchat, no telemetry.

## Remove

Remove the Hazelchat set from the site (or uninstall the extension) and flush the caches. The chat no longer appears on newly rendered pages.

## Deutsch

Hazelchat fügt Ihren KI-Chat-Agenten mit einem einzigen Installationscode in Ihre TYPO3-Site ein (TYPO3 13.4 und 14.3). Installieren Sie die Extension (`composer require hazelchat/typo3`), fügen Sie Ihrer Site das Set **Hazelchat** hinzu, fügen Sie in den Einstellungen der Site unter **Hazelchat > Installationscode** den Code aus Hazelchat ein und leeren Sie die Caches. Für die Site-Einstellungen brauchen Sie ein Administratorkonto oder Ihre Agentur. Die Browser Ihrer Besucher laden den Chat von `app.hazelchat.co`; deren Fragen und Anfragedaten verarbeitet Hazelchat. Wartet der Chat auf Ihren Consent-Manager, lädt der Browser `widget.js` trotzdem sofort, und die IP-Adresse des Besuchers erreicht Hazelchat schon vor der Einwilligung; nur der Chat wartet. Über die ID `hazelchat-typo3` im Skript-Tag zählt Hazelchat die Installationen dieser Extension. Sie brauchen ein Hazelchat-Konto; der Free-Tarif gilt für eine Website und bleibt kostenlos.

## Licence

GPL-2.0-or-later. TYPO3 is a registered trademark of the TYPO3 Association. Hazelchat is not affiliated with or endorsed by it.
