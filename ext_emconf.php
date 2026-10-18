<?php

/*
 * This file is part of the Hazelchat extension for TYPO3 sites.
 *
 * (C) 2026 Hazelchat. License: GNU General Public License version 2 or
 * later; see LICENSE.txt.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Hazelchat',
    'description' => 'Adds the Hazelchat AI chat agent to TYPO3 sites from one pasted installation code. Needs a Hazelchat account; the Free plan covers one website.',
    'category' => 'fe',
    'author' => 'Hazelchat',
    'author_email' => 'hello@hazelchat.co',
    'author_company' => 'Hazelchat',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.3.99',
            'php' => '8.2.0-8.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Hazelchat\\Typo3\\' => 'Classes/',
        ],
    ],
];
