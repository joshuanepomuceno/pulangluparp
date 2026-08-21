<?php
/**
 * Template Name: Rules Page
 *
 * Data-driven rulebook for Pulang Lupa Roleplay.
 * The full ruleset lives in the $plrp_intro / $plrp_chapters arrays below and
 * is rendered into both the sidebar table of contents and the main content,
 * so the two can never drift out of sync.
 */
get_header();

/* -------------------------------------------------------------------------
   RULE DATA
   Block types used inside each rule's "blocks" array:
     ['p',    'paragraph text']
     ['lead', 'emphasised opening line']
     ['h',    'sub-heading']
     ['sub',  'Card title', 'card description']            (injury-style card)
     ['note', 'highlighted call-out text']
     ['list', 'Intro line:', 'must|mustnot|neutral', [items…]]
------------------------------------------------------------------------- */

$plrp_intro = [
    'lead' => 'Pulang Lupa RP is a Serious Roleplay RedM server inspired by Philippine history, culture, and folklore, set in the fictional nation of Pulang Lupa in 1890. As the nation stands at the crossroads of political change, economic ambition, and social unrest, every citizen has the opportunity to shape its future through meaningful roleplay and immersive storytelling.',
    'body' => 'Build a life as a lawman, rancher, entrepreneur, doctor, politician, outlaw, or ordinary settler while navigating a world defined by consequence, opportunity, and human connection. Every reputation is earned, every conflict has lasting effects, and every story becomes part of Pulang Lupa\'s history.',
    'discretion' => [
        'While this handbook aims to cover the majority of situations players may encounter, no document can anticipate every circumstance. Pulang Lupa RP staff reserve the right to interpret, enforce, and apply these rules in a manner that best supports fairness, immersion, and the long-term health of the community.',
        'Decisions will always consider the context, intent, severity, and impact of a player\'s actions. Deliberate attempts to exploit loopholes or technicalities within the rules may still result in administrative action, even if the behavior is not explicitly listed.',
        'If you are ever unsure whether an action is permitted, ask a staff member before proceeding.',
    ],
];

$plrp_chapters = [
    /* ── REFERENCE (Glossary / Commands / Controls) ──────────── */
    [
        'id'       => 'reference',
        'numeral'  => '§',
        'label'    => 'Reference',
        'title'    => 'Glossary, Commands & Controls',
        'desc'     => 'Terms, commands, and controls used throughout this handbook.',
        'rules'    => [
            [
                'id' => 'glossary-administrative', 'num' => '§', 'title' => 'Administrative Terms',
                'blocks' => [
                    ['sub', 'Administration', 'The team responsible for enforcing server rules, resolving disputes, and maintaining the overall health of the community.'],
                    ['sub', 'Appeal', 'A formal request asking Administration to review a previous administrative decision.'],
                    ['sub', 'Faction Management', 'The administrative team responsible for reviewing, approving, and overseeing official organizations.'],
                    ['sub', 'Ticket', 'A support request submitted through the server\'s designated support system.'],
                    ['sub', 'Retcon (Cut RP)', 'An administrative action that invalidates or reverses part or all of a roleplay scene.'],
                ],
            ],
            [
                'id' => 'glossary-roleplay', 'num' => '§', 'title' => 'Roleplay Terms',
                'blocks' => [
                    ['sub', 'In-Character (IC)', 'Everything that exists within the roleplay world, including your character\'s actions, knowledge, and interactions.'],
                    ['sub', 'Out-of-Character (OOC)', 'Everything outside the roleplay world, including Discord conversations, personal communication, and administrative matters.'],
                    ['sub', 'Roleplay (RP)', 'Collaborative storytelling where players portray believable characters within the world of Pulang Lupa.'],
                    ['sub', 'Serious Roleplay (SRP)', 'A style of roleplay that prioritizes realism, immersion, character development, and collaborative storytelling.'],
                ],
            ],
            [
                'id' => 'glossary-rule-terminology', 'num' => '§', 'title' => 'Rule Terminology',
                'blocks' => [
                    ['sub', 'Metagaming (MG)', 'Using Out-of-Character information to influence In-Character decisions or gain an unfair advantage.'],
                    ['sub', 'Powergaming (PG)', 'Forcing actions or outcomes on another player without allowing a fair opportunity to react.'],
                    ['sub', 'Random Deathmatch (RDM)', 'Attacking or killing another player without sufficient in-character justification.'],
                    ['sub', 'Random Vehicle Deathmatch (RVDM)', 'Using a vehicle or horse primarily as a weapon to harm another player without proper roleplay.'],
                    ['sub', 'Revenge Killing (RK)', 'Re-engaging in violence solely to seek revenge for a previous encounter without a new in-character justification.'],
                    ['sub', 'New Life Rule (NLR)', 'The rule governing your character\'s memory and involvement after being incapacitated or respawning.'],
                    ['sub', 'Fear Roleplay (Fear RP)', 'Roleplaying realistic concern for your character\'s life, safety, and freedom when faced with danger.'],
                    ['sub', 'Non-Value of Life (NVL)', 'Acting with unrealistic disregard for your character\'s own safety or survival.'],
                    ['sub', 'Non-Value of Fear (NVF)', 'Ignoring realistic fear or consequences in situations where a reasonable person would fear death, injury, or imprisonment.'],
                ],
            ],
            [
                'id' => 'glossary-character', 'num' => '§', 'title' => 'Character Terms',
                'blocks' => [
                    ['sub', 'Character Kill (CK)', 'The permanent conclusion of a character\'s story. A Character Kill permanently retires the character from roleplay.'],
                    ['sub', 'Staff Character Kill (Staff CK)', 'A Character Kill issued by Administration due to severe rule violations or exceptional circumstances.'],
                    ['sub', 'Player Kill (PK)', 'A non-permanent death or incapacitation where the character survives and continues their story while roleplaying the appropriate injuries and consequences.'],
                    ['sub', 'Consent', 'An explicit Out-of-Character agreement between players before engaging in roleplay involving permanent consequences, torture, or other sensitive themes.'],
                ],
            ],
            [
                'id' => 'glossary-organization', 'num' => '§', 'title' => 'Organization Terms',
                'blocks' => [
                    ['sub', 'Official Organization', 'A player organization approved and recognized by Faction Management.'],
                    ['sub', 'Area of Influence', 'A location where an organization has established a significant presence through roleplay. Influence does not grant ownership or exclusive control.'],
                    ['sub', 'Organization War', 'A staff-notified conflict between two or more official organizations conducted under the Organization Handbook.'],
                ],
            ],
            [
                'id' => 'glossary-occupation', 'num' => '§', 'title' => 'Occupation Terms',
                'blocks' => [
                    ['sub', 'Whitelisted Job', 'A position requiring approval, recruitment, or appointment by the appropriate department or management team.'],
                ],
            ],
            [
                'id' => 'commands-list', 'num' => '§', 'title' => 'Commands List',
                'blocks' => [
                    ['sub', '/report', 'Report in-game concerns to admins.'],
                    ['sub', '/ooc', 'Provide OOC information in-game without breaking immersion.'],
                    ['sub', '/me', 'Provide RP immersion by narrating your character\'s internal state or minor actions for other players to see.'],
                    ['sub', '/do', 'Same concept as /me but for verbs/actions. Used to describe an action when unable to use an emote.'],
                    ['sub', '/rc', 'Refresh character. Only use in appropriate instances.'],
                ],
            ],
            [
                'id' => 'controls', 'num' => '§', 'title' => 'Controls',
                'blocks' => [
                    ['sub', 'F3 or L', 'Emote Menu'],
                    ['sub', 'T', 'Command Bar'],
                    ['sub', 'I', 'Open Inventory'],
                    ['sub', 'Tab', 'Weapon Tab'],
                    ['sub', 'PageUp', 'Cycle Voice Proximity'],
                    ['sub', 'V', 'Cycle POV'],
                    ['sub', 'Long Press V', 'Cinematic Camera'],
                    ['sub', 'Z', 'Ragdoll'],
                    ['sub', 'E', 'Ride Vehicle'],
                ],
            ],
        ],
    ],

    /* ── CHAPTER 1 ─────────────────────────────────────────── */
    [
        'id'       => 'chapter-1',
        'numeral'  => 'I',
        'label'    => 'Chapter 1',
        'title'    => 'General Rules',
        'desc'     => 'The community standards every member agrees to on joining.',
        'rules'    => [
            [
                'id' => 'welcome', 'num' => 1, 'title' => 'Welcome',
                'blocks' => [
                    ['lead', 'Welcome to Pulang Lupa RP.'],
                    ['p', 'These rules exist to provide a fair, immersive, and enjoyable roleplay experience for everyone. By joining the server, you agree to follow these rules and any reasonable instructions given by the staff team.'],
                    ['p', 'Players are expected to use common sense at all times. While this rulebook covers the vast majority of situations, no document can account for every possible scenario. The purpose of these rules is to preserve fairness, immersion, and collaborative storytelling — not to provide loopholes or technicalities that may be exploited.'],
                ],
            ],
            [
                'id' => 'rule-interpretation', 'num' => 2, 'title' => 'Rule Interpretation',
                'blocks' => [
                    ['p', 'These rules are intended to be interpreted according to their purpose and spirit, not solely by their literal wording.'],
                    ['p', 'Attempting to exploit loopholes, ambiguous wording, omissions, or technicalities to gain an unfair advantage or avoid consequences is considered a rule violation.'],
                    ['p', 'Staff retain the authority to interpret and enforce these rules based on the best interests of the server, its community, and the quality of roleplay. Staff decisions are made in good faith and with the intent of maintaining a fair and enjoyable environment for everyone.'],
                    ['note', 'If you are ever unsure whether something is allowed, ask a staff member before proceeding.'],
                ],
            ],
            [
                'id' => 'community-conduct', 'num' => 3, 'title' => 'Community Conduct',
                'blocks' => [
                    ['p', 'Pulang Lupa is an 18+ roleplay server. Mature themes may naturally arise through roleplay; however, every player is expected to behave with maturity, respect, and common courtesy. Treat every player with respect. There is always a real person behind every character.'],
                    ['list', 'The following are strictly prohibited:', 'mustnot', [
                        'Harassment, discrimination, or stereotyping based on race, ethnicity, nationality, religion, gender, sexual orientation, disability, or any other protected characteristic.',
                        'Hate speech, threats, targeted abuse, or sexually aggressive behavior.',
                        'Public OOC arguments, personal attacks, or attempts to create a hostile environment.',
                        'Behavior intended to disrupt the community or diminish the experience of other players.',
                    ]],
                    ['list', 'Players are reminded that these are also not allowed:', 'mustnot', [
                        'Fail RP — actions that break realism or the established RP environment.',
                        'Combat Logging — disconnecting from the server during an ongoing scenario.',
                        'Breaking Character — connected to Fail RP, this is when a player breaks away from their character and starts to bring OOC decisions and actions into the game.',
                        'RP Evasion — deliberately avoiding or interrupting valid roleplay or its consequences through OOC actions, game mechanics, or technical loopholes (such as disconnecting during an active RP, abusing safe zones or interiors, exploiting mechanics to escape, intentionally stalling, or using OOC communication to avoid consequences). Legitimate escapes through roleplay, such as fleeing, hiding, or negotiating, are not considered RP Evasion.',
                        'Deliberate Rule-Breaking — deliberately violating server rules for personal gain, to avoid in-character consequences, or to test, challenge, or determine the limits of the Administration\'s enforcement, verdicts, or rules. If it is established that a player knowingly and intentionally committed an infraction despite being aware that the conduct was prohibited, such intent may be considered an aggravating circumstance and may result in a more severe sanction, with the final penalty determined by the Administration.',
                    ]],
                ],
            ],
            [
                'id' => 'discord-guidelines', 'num' => 4, 'title' => 'Discord Guidelines',
                'blocks' => [
                    ['p', 'Players must remain in the official Pulang Lupa Discord while actively playing on the server. The same standards of conduct that apply in-game also apply throughout the Discord server.'],
                    ['list', 'Players must:', 'must', [
                        'Follow Discord\'s Terms of Service.',
                        'Use channels for their intended purpose.',
                        'Keep discussions respectful and constructive.',
                        'Use their primary in-character name as their server nickname (an OOC name may be included).',
                        'Follow any instructions provided by staff.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Advertise or promote other communities without prior approval.',
                        'Spam, flood, or raid Discord channels.',
                        'Leave the official Discord while actively playing, as doing so prevents important announcements, ticket communication, and administrative contact.',
                    ]],
                ],
            ],
            [
                'id' => 'account-responsibility', 'num' => 5, 'title' => 'Account Responsibility',
                'blocks' => [
                    ['lead', 'Your account is your responsibility.'],
                    ['list', 'Players must:', 'must', [
                        'Be at least 18 years of age to play on Pulang Lupa.',
                        'Keep their account secure.',
                        'Never share their account with another person.',
                        'Never evade disciplinary action through alternate accounts.',
                    ]],
                    ['note', 'Any action performed on your account is considered your responsibility.'],
                ],
            ],
            [
                'id' => 'language-communication', 'num' => 6, 'title' => 'Language & Communication',
                'blocks' => [
                    ['p', 'Clear communication is essential to quality roleplay. Players are expected to communicate clearly and respectfully at all times.'],
                    ['list', 'Requirements:', 'neutral', [
                        'English and Tagalog are the primary roleplay languages.',
                        'Other languages may be used sparingly for immersion where appropriate.',
                        'A clear microphone with minimal background noise is required.',
                        'Voice changers are permitted only if they sound natural and immersive.',
                        'Players with speech disabilities may request approval to play a mute character through a support ticket.',
                    ]],
                ],
            ],
            [
                'id' => 'cross-server', 'num' => 7, 'title' => 'Cross-Server Roleplay',
                'blocks' => [
                    ['p', 'Pulang Lupa is a standalone roleplay environment. Characters must develop their stories within Pulang Lupa. Ongoing roleplay from other servers may not be continued here. Completed roleplay from another server may be referenced as part of a character\'s background, provided it remains part of their history and is not used to continue unresolved storylines or create new roleplay based on events that occurred elsewhere.'],
                    ['list', 'The following ongoing roleplay may continue:', 'must', [
                        'Established family relationships.',
                        'Established romantic relationships.',
                    ]],
                    ['list', 'The following may not continue:', 'mustnot', [
                        'Ongoing gang conflicts.',
                        'Criminal investigations.',
                        'Revenge storylines.',
                        'Political conflicts.',
                        'Business disputes.',
                        'Active rivalries.',
                        'Any unresolved storyline originating from another server.',
                    ]],
                    ['p', 'Characters may reference completed events from their past, but all new stories should begin and develop within Pulang Lupa.'],
                ],
            ],
            [
                'id' => 'prohibited-content', 'num' => 8, 'title' => 'Prohibited Content',
                'blocks' => [
                    ['list', 'The following content is prohibited within Pulang Lupa:', 'mustnot', [
                        'Sexual violence or coercion.',
                        'Self-harm or suicide roleplay.',
                        'Harm involving minors.',
                        'Excessively graphic or grotesque roleplay.',
                        'Content intended primarily to shock, disgust, or disturb other players.',
                        'Using R18 emotes in public. These are only permitted in private places, such as your own home or hotel rooms in other game dimensions/instances.',
                    ]],
                    ['note', 'If you are uncertain whether a particular storyline or scene is appropriate, consult a staff member before proceeding.'],
                ],
            ],
        ],
    ],

    /* ── CHAPTER 2 ─────────────────────────────────────────── */
    [
        'id'       => 'chapter-2',
        'numeral'  => 'II',
        'label'    => 'Chapter 2',
        'title'    => 'Character Rules',
        'desc'     => 'Creating and developing believable people, not game builds.',
        'rules'    => [
            [
                'id' => 'character-creation', 'num' => 9, 'title' => 'Character Creation',
                'blocks' => [
                    ['p', 'Every story in Pulang Lupa begins with a character. Character creation is the foundation of your roleplay experience and should be approached with creativity, realism, and a willingness to grow.'],
                    ['p', 'Players are expected to create original, believable characters that naturally develop through roleplay. Rather than beginning as accomplished experts or influential figures, characters should earn their reputation, skills, and relationships through their experiences within the world.'],
                    ['p', 'Whether you choose to portray a lawman, doctor, merchant, rancher, outlaw, politician, or laborer, your character should contribute to the shared story through meaningful interactions, personal growth, and believable choices.'],
                    ['note', 'Remember: you are creating an actual person with flaws, not a game build or a collection of skills.'],
                ],
            ],
            [
                'id' => 'character-standards', 'num' => 10, 'title' => 'Character Standards',
                'blocks' => [
                    ['p', 'All characters created on Pulang Lupa must meet the following standards.'],
                    ['list', 'Players must:', 'must', [
                        'Create original and believable characters appropriate for the setting.',
                        'Ensure every character is 18 years of age or older.',
                        'Give each character a distinct identity, personality, and background.',
                        'Keep each of their characters completely independent from one another.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Use names based on historical figures, celebrities, religious deities, or existing fictional characters.',
                        'Create characters related to any of their own alternate characters.',
                        'Share knowledge, assets, relationships, or affiliations between their own characters.',
                        'Create characters solely to gain mechanical or roleplay advantages.',
                    ]],
                    ['p', 'Every character should possess believable strengths, weaknesses, ambitions, fears, and limitations. Imperfection creates meaningful stories.'],
                ],
            ],
            [
                'id' => 'character-development', 'num' => 11, 'title' => 'Character Development',
                'blocks' => [
                    ['lead', 'Strong characters are defined by their growth, not by their perfection.'],
                    ['p', 'Your character should evolve naturally through the relationships they build, the hardships they endure, and the decisions they make throughout their journey. Success, failure, injury, loss, and personal conflict all contribute to meaningful character development.'],
                    ['list', 'Examples of strong character concepts:', 'must', [
                        'A wandering preacher struggling with their faith.',
                        'A former outlaw attempting to leave their criminal past behind.',
                        'A young immigrant hiding their true identity while searching for a new beginning.',
                    ]],
                    ['list', 'Examples of weak character concepts:', 'mustnot', [
                        'A master gunslinger with no meaningful background or flaws.',
                        'A newly arrived doctor possessing complete medical expertise without experience.',
                        'A lawman who immediately knows every criminal in the region.',
                    ]],
                    ['note', 'The most memorable characters are those who struggle, adapt, and grow over time.'],
                ],
            ],
            [
                'id' => 'character-progression', 'num' => 12, 'title' => 'Character Progression',
                'blocks' => [
                    ['lead', 'Status, influence, and responsibility must be earned through roleplay.'],
                    ['p', 'Characters should begin as ordinary individuals and gradually establish themselves through meaningful interactions, consistent roleplay, and sustained character development. Positions of authority, professional expertise, and community reputation should reflect a character\'s experiences — not simply their backstory.'],
                    ['list', 'Players may not:', 'mustnot', [
                        'Begin as prominent political leaders, senior government officials, renowned professionals, or infamous criminals.',
                        'Claim accomplishments or reputations earned on other servers.',
                        'Skip progression through unsupported backstories or off-screen achievements.',
                    ]],
                    ['list', 'Players are encouraged to:', 'must', [
                        'Allow their character to learn through experience.',
                        'Embrace setbacks, failures, and personal challenges.',
                        'Earn promotions, influence, and recognition through roleplay.',
                    ]],
                    ['note', 'Progression is part of the story and should never be rushed.'],
                ],
            ],
            [
                'id' => 'child-characters', 'num' => 13, 'title' => 'Child Characters',
                'blocks' => [
                    ['p', 'All child characters are subject to approval by the Administration. Any plans to introduce a Child Character to the city are subject to admin review — please open a ticket for further details.'],
                    ['list', 'Child characters:', 'mustnot', [
                        'Are not allowed to have weapons of any type, aside from lassos and knives.',
                        'Are not allowed to participate in PvP.',
                        'Are not allowed to initiate involvement in conflicts.',
                    ]],
                ],
            ],
            [
                'id' => 'injury-roleplay', 'num' => 14, 'title' => 'Injury Roleplay',
                'blocks' => [
                    ['p', 'Injuries are an important part of immersive roleplay and should be reflected realistically. Recovery takes time, and characters are expected to acknowledge the physical and emotional effects of their injuries until they have reasonably healed.'],
                    ['p', 'Injuries generally fall into four categories:'],
                    ['sub', 'Minor Injuries', 'Bruises, scrapes, shallow cuts, and other minor wounds. These should be acknowledged through roleplay but generally require little recovery time.'],
                    ['sub', 'Moderate Injuries', 'Deep cuts, fractures, or gunshot wounds to non-critical areas. Medical treatment is strongly encouraged. Recovery should be roleplayed over several days, including the possibility of infection or reduced physical ability.'],
                    ['sub', 'Major Injuries', 'Serious trauma such as gunshot wounds to vital areas, broken bones, or prolonged unconsciousness. Characters are expected to seek medical treatment and continue roleplaying the recovery process through follow-up care, rehabilitation, and lasting physical effects.'],
                    ['sub', 'Life-Threatening Injuries', 'Severe blood loss, organ damage, coma, or other near-fatal injuries. These injuries require extensive medical roleplay and long-term recovery. Characters must continue portraying the effects until they have been reasonably resolved through in-character roleplay.'],
                    ['note', 'Being revived does not mean your character has fully recovered. Lasting injuries should continue to affect your character until appropriate treatment and recovery have occurred.'],
                    ['h', 'Doctor RP'],
                    ['p', 'Players are expected to respect Doctor RP when seeking or receiving medical treatment. Doctors should be given reasonable opportunity to assess, treat, and roleplay the patient\'s condition without having their RP dismissed or overridden.'],
                    ['p', 'Players should not dictate their own treatment or recovery solely to avoid the consequences of an injury — Doctor RP should be treated as part of the ongoing scenario and respected accordingly. That said, instructions or medical advice given by a doctor for the purpose of ensuring a character\'s well-being should be respected. Ignoring or immediately roleplaying recovery against a doctor\'s instructions may be considered Fail RP and subject to the appropriate sanctions.'],
                    ['p', 'However, being placed under medical recovery does not provide a player with immunity from consequences or special protection from other roleplay. Players who choose to antagonize, provoke, or otherwise engage with others while recovering remain subject to all applicable rules, including existing NVF and NVL provisions.'],
                    ['note', 'Doctors are also prohibited from abusing the recovery system by placing players on excessively long recovery periods that unnecessarily prevent them from participating in roleplay or disrupt their ability to play. For serious injuries, the maximum recovery period that may be imposed is 30 minutes. Recovery times should remain reasonable and proportionate to the severity of the injury, and should not be used as a means of restricting or punishing a player outside of legitimate medical RP.'],
                ],
            ],
            [
                'id' => 'death-ck', 'num' => 15, 'title' => 'Death & Character Kill (CK)',
                'blocks' => [
                    ['p', 'Death represents the conclusion of a character\'s story and should always carry significant narrative weight.'],
                    ['p', 'A Character Kill (CK) permanently ends a character\'s journey. Whether caused by violence, illness, old age, or another legitimate in-character event, a CK should serve as the natural conclusion of meaningful roleplay rather than a mechanical outcome.'],
                    ['p', 'Participation in CK-focused factions or storylines is entirely voluntary. By consenting to a CK clause or participating in a CK scene, players acknowledge and accept the risks associated with those storylines.'],
                    ['list', 'CK scenes are expected to be:', 'must', [
                        'Well-developed and story-driven.',
                        'Supported by meaningful roleplay.',
                        'Respectful of every participant\'s contribution.',
                        'Conducted fairly and in accordance with server rules.',
                    ]],
                    ['note', 'Staff reserve the right to invalidate any CK that involves rule violations, poor roleplay, or insufficient narrative justification.'],
                ],
            ],
            [
                'id' => 'pregnancy-roleplay', 'num' => 16, 'title' => 'Pregnancy Roleplay',
                'blocks' => [
                    ['p', 'Pregnancy roleplay is permitted but must always prioritize realism, player comfort, and mutual consent.'],
                    ['p', 'Before beginning a pregnancy storyline, all participating players must reach an Out-of-Character agreement. The roleplay should develop naturally over time and remain appropriate for both the setting and the community.'],
                    ['list', 'Pregnancy roleplay must:', 'must', [
                        'Be consensual between all participating players.',
                        'Progress at a realistic pace.',
                        'Avoid graphic or excessively uncomfortable content.',
                        'Reflect the physical limitations and risks associated with pregnancy.',
                        'Follow an appropriate timeline, with a full-term pregnancy lasting at least 4 real-life weeks.',
                    ]],
                    ['list', 'The following are prohibited:', 'mustnot', [
                        'Pregnancy involving minors.',
                        'Roleplaying pregnancy loss.',
                        'Using children as active participants in roleplay before adulthood.',
                        'Using pregnancy or children to gain roleplay immunity or mechanical advantages.',
                    ]],
                    ['note', 'Players planning complex pregnancy storylines, such as multiple births, should consult staff before proceeding.'],
                ],
            ],
        ],
        'violations' => 'Violations of the Character Rules may result in administrative action, including character edits, forced redesigns, character retirement, warnings, temporary suspensions, or permanent bans depending on the severity, intent, and repetition of the violation.',
    ],

    /* ── CHAPTER 3 ─────────────────────────────────────────── */
    [
        'id'       => 'chapter-3',
        'numeral'  => 'III',
        'label'    => 'Chapter 3',
        'title'    => 'Roleplay Standards',
        'desc'     => 'Fair, immersive, and collaborative storytelling above all.',
        'rules'    => [
            [
                'id' => 'metagaming', 'num' => 17, 'title' => 'Metagaming',
                'blocks' => [
                    ['p', 'Metagaming is the use of Out-of-Character (OOC) information to influence In-Character (IC) decisions, actions, or roleplay. Characters should only know what they have personally witnessed, experienced, or been told through legitimate in-character interactions.'],
                    ['p', 'Separating IC knowledge from OOC knowledge is fundamental to fair and immersive roleplay. Using information gained through streams, Discord, voice chats, or other external sources undermines storytelling and gives players an unfair advantage.'],
                    ['list', 'Players must:', 'must', [
                        'Keep IC and OOC knowledge completely separate.',
                        'Learn information through roleplay rather than external sources.',
                        'Allow their characters to discover information naturally.',
                        'Keep track of what each of their characters knows independently.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Watch another player\'s livestream to gain information your character would not reasonably know.',
                        'Share sensitive IC information through Discord messages or other OOC platforms.',
                        'Use OOC conversations to influence IC decisions.',
                        'Transfer knowledge between your own characters.',
                    ]],
                    ['note', 'Metagaming, whether intentional or accidental, damages immersion and may result in administrative action.'],
                    ['h', 'Voice Recognition & Disguises'],
                    ['p', 'You may recognize another character by their voice only when no reasonable effort has been made to conceal their identity. If a character is wearing a mask, face paint, bandana, large hat, or any other significant form of disguise, the disguise must be respected and the character should not be identified through voice, regardless of any OOC knowledge of the player.'],
                    ['p', 'A significant and deliberate effort to alter or conceal one\'s voice, including the use of realistic voice changers or voice modulation, may also be considered part of the disguise when used alongside another layer of physical disguise. The voice alteration should be reasonably convincing and consistent with the character\'s attempt to conceal their identity.'],
                    ['p', 'Players who choose to disguise their identity should still leave reasonable traces or clues of their true identity that other players may discover through roleplay. These clues are not required to be obvious, nor are players obligated to reveal them, but they should be properly roleplayed to allow others the opportunity to investigate, identify, and develop further RP from the disguise.'],
                ],
            ],
            [
                'id' => 'powergaming', 'num' => 18, 'title' => 'Powergaming',
                'blocks' => [
                    ['p', 'Powergaming is the act of forcing outcomes, performing unrealistic actions, or abusing game mechanics to gain an unfair advantage or remove another player\'s ability to respond through roleplay.'],
                    ['p', 'Roleplay should always remain collaborative. Every participant deserves the opportunity to react, resist, and influence the outcome of a scene.'],
                    ['list', 'Powergaming includes, but is not limited to:', 'mustnot', [
                        'Forcing actions or injuries onto another character.',
                        'Performing actions that are physically or logically impossible.',
                        'Abusing scripts, game mechanics, or user interface elements to gain an advantage.',
                        'Wearing metal helmets, armor, or other clothing that causes bullets, melee weapons, or arrows to be deflected.',
                        'Hoarding weapons or items from a whitelisted job and funneling or favoring distribution toward specific members of the community.',
                        'Ignoring realistic limitations or roleplay consequences.',
                    ]],
                    ['list', 'Players must:', 'must', [
                        'Leave room for other players to respond.',
                        'Use open-ended roleplay commands (e.g., "attempts to…") instead of dictating outcomes.',
                        'Respect the physical limitations of their character.',
                        'Use game mechanics only as they were intended.',
                    ]],
                    ['note', 'Powergaming prioritizes "winning" over collaborative storytelling and will not be tolerated.'],
                    ['h', 'Whitelisted Job Item Distribution'],
                    ['note', 'Players working under Whitelisted Jobs are prohibited from hoarding or intentionally restricting access to items essential for everyday survival and basic living. These items must remain reasonably accessible and may not be gatekept to benefit specific players, factions, or organizations. This will be counted as severe powergaming and may result in a revocation of your whitelist job privileges.'],
                    ['p', 'Specialized or non-essential items may be distributed through legitimate in-character requirements such as reputation, trade, services, or meaningful relationships, provided these requirements are reasonable and RP-driven.'],
                    ['p', 'For example, specialized Katutubo items such as Fire Arrows and Poison Arrows may be distributed based on established reputation or relationships with the tribe.'],
                ],
            ],
            [
                'id' => 'exploiting', 'num' => 19, 'title' => 'Exploiting',
                'blocks' => [
                    ['p', 'Exploiting is the intentional abuse of bugs, unintended mechanics, script oversights, or external tools to gain an unfair advantage. Exploiting is considered a zero-tolerance offense.'],
                    ['p', 'Players are expected to report bugs and unintended behavior rather than using them for personal gain.'],
                    ['list', 'Examples of exploiting include:', 'mustnot', [
                        'Duplicating items or currency through bugs.',
                        'Abusing script errors for financial or gameplay advantages.',
                        'Using external software such as modified game packs, crosshairs, overlays, macros, or similar tools.',
                        'Exploiting combat mechanics, inventory systems, or synchronization issues.',
                        'Farming mechanics in ways clearly unintended by server design.',
                    ]],
                    ['list', 'If you discover a bug or exploit:', 'must', [
                        'Do not continue using it.',
                        'Gather evidence if possible.',
                        'Report it to staff through the appropriate channels.',
                    ]],
                    ['note', 'Knowingly abusing an exploit, even if others are doing so, is still considered exploiting.'],
                ],
            ],
            [
                'id' => 'immersion-realism', 'num' => 20, 'title' => 'Immersion & Realism',
                'blocks' => [
                    ['p', 'Pulang Lupa is built around immersive storytelling. Every action should reflect the world, setting, and limitations of the year 1890. Characters should behave as believable people rather than video game protagonists. Their actions, movement, speech, and decision-making should reflect the risks and realities of the world around them.'],
                    ['list', 'Examples of immersion-breaking behavior include:', 'mustnot', [
                        'Performing physically impossible actions.',
                        'Carrying or producing equipment without appropriate visual representation.',
                        'Ignoring realistic injuries or environmental hazards.',
                        'Using modern language, technology, or cultural references while in character.',
                        'Sprinting through populated towns without a legitimate in-character reason.',
                    ]],
                    ['note', 'Always ask yourself: "Would this reasonably happen in the world of Pulang Lupa?" If the answer is no, reconsider your actions.'],
                ],
            ],
            [
                'id' => 'value-of-life', 'num' => 21, 'title' => 'Value of Life (NVL)',
                'blocks' => [
                    ['p', 'Value of Life (NVL) requires players to roleplay as though their character genuinely fears death, serious injury, and imprisonment.'],
                    ['p', 'Your character does not need to be a coward, but they should value their own survival. Even the bravest individuals experience fear, hesitation, and self-preservation when faced with overwhelming danger.'],
                    ['list', 'Examples of NVL violations include:', 'mustnot', [
                        'Drawing a weapon while already held at gunpoint.',
                        'Charging an armed opponent with little chance of survival.',
                        'Refusing reasonable demands while completely outnumbered.',
                        'Entering an active gunfight without a legitimate reason.',
                        'Recklessly risking death purely for personal pride or "winning".',
                    ]],
                    ['note', 'Good roleplay often involves surrender, retreat, negotiation, or accepting defeat when circumstances call for it. Failure can create better stories than victory.'],
                ],
            ],
            [
                'id' => 'value-of-fear', 'num' => 22, 'title' => 'Value of Fear (NVF)',
                'blocks' => [
                    ['p', 'Characters should respond realistically to intimidation, injury, violence, and imprisonment.'],
                    ['p', 'Fear influences decision-making. Whether confronted by armed criminals, threatened with execution, or facing a lengthy prison sentence, your character should display believable emotional and physical reactions.'],
                    ['p', 'This does not mean every character must immediately comply with every demand. Brave, stubborn, or defiant characters are welcome, provided they still demonstrate concern for the consequences of their actions.'],
                    ['note', 'Ignoring obvious danger or acting as though your character has nothing to lose may be considered a violation of NVF.'],
                ],
            ],
            [
                'id' => 'story-over-profit', 'num' => 23, 'title' => 'Story Over Profit',
                'blocks' => [
                    ['lead', 'Roleplay should always take priority over mechanical progression, wealth, or personal gain.'],
                    ['p', 'Criminal activity, business ventures, political conflict, and everyday interactions should exist because they create compelling stories — not because they provide the fastest route to money or rewards.'],
                    ['list', 'Players should avoid:', 'mustnot', [
                        'Treating other players as opportunities for profit.',
                        'Initiating conflict solely to obtain valuables.',
                        'Repeating identical roleplay purely for financial gain.',
                        'Prioritizing server mechanics over meaningful storytelling.',
                    ]],
                    ['note', 'Instead, focus on creating memorable scenes that involve everyone fairly. The best stories are not defined by who wins, but by the experiences shared along the way.'],
                ],
            ],
        ],
        'violations' => 'Violations of the Roleplay Standards may result in administrative action, including warnings, scene reversals, character edits, temporary suspensions, permanent bans, or other measures deemed appropriate based on the severity, intent, and repetition of the violation.',
    ],

    /* ── CHAPTER 4 ─────────────────────────────────────────── */
    [
        'id'       => 'chapter-4',
        'numeral'  => 'IV',
        'label'    => 'Chapter 4',
        'title'    => 'Conflict & Combat',
        'desc'     => 'When and how violence is allowed to enter a story.',
        'rules'    => [
            [
                'id' => 'pvp', 'num' => 24, 'title' => 'Player vs. Player (PvP)',
                'blocks' => [
                    ['p', 'Player versus Player (PvP) roleplay is an important part of Pulang Lupa\'s storytelling. Whether conflict takes the form of a heated argument, fistfight, robbery, or shootout, it must always be driven by meaningful roleplay and believable in-character motivations.'],
                    ['p', 'Violence should never be the first solution to a problem. Players are expected to build tension through dialogue, roleplay commands, negotiation, intimidation, or other forms of escalation before resorting to physical conflict.'],
                    ['list', 'Players must:', 'must', [
                        'Have a valid in-character reason before initiating violence.',
                        'Allow conflict to develop naturally through roleplay.',
                        'Prioritize storytelling over mechanical victory.',
                        'Respect the outcome of every encounter.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Initiate violence solely for profit or entertainment.',
                        'Rush directly into combat without roleplay.',
                        'Treat combat as a competitive shooter rather than collaborative storytelling.',
                    ]],
                    ['note', 'Every act of violence should move a story forward — not end it.'],
                ],
            ],
            [
                'id' => 'conflict-escalation', 'num' => 25, 'title' => 'Conflict Escalation',
                'blocks' => [
                    ['note', 'Players are not allowed to initiate conflicts if they have less than 24 hours of playtime. However, if they are placed under conflict by a different party, they are required to roleplay it out — the restriction applies only to initiating conflict.'],
                    ['lead', 'Conflict should develop through believable progression rather than immediate violence.'],
                    ['p', 'Before drawing a weapon or initiating deadly force, players are expected to establish the scene through dialogue, roleplay commands, body language, threats, or other forms of interaction. Moments of hesitation, negotiation, and intimidation often create more compelling stories than immediate combat.'],
                    ['list', 'Players are encouraged to:', 'must', [
                        'Use voice roleplay, emotes, /me, and /do commands to build tension.',
                        'Show hesitation, fear, anger, or desperation through roleplay.',
                        'Give opposing players opportunities to react.',
                        'Allow scenes to breathe before escalating further.',
                    ]],
                    ['note', 'Escalation should feel earned. Random or impulsive violence without meaningful buildup is considered poor roleplay.'],
                    ['h', 'Equal Force Rule'],
                    ['p', 'Players are expected to use a level of force proportionate to the threat they reasonably perceive, rather than simply matching the exact force initially used against them. The appropriate response should be determined by the circumstances of the encounter, including the presence and type of weapons, number of attackers, existing injuries, available escape routes, and any explicit threats of serious harm or death.'],
                    ['note', 'Lethal force may only be used when a player reasonably perceives an imminent threat of death or serious bodily harm. Simply being attacked or provoked does not, by itself, justify the use of lethal force.'],
                    ['sub', 'Fistfight', 'A player engaged in a fistfight should generally respond with melee force. Drawing a firearm and using lethal force against an unarmed opponent would generally be disproportionate unless circumstances create a reasonable and imminent threat of serious bodily harm or death.'],
                    ['sub', 'Armed Threat', 'If an opponent draws or uses a firearm in a manner that presents an imminent threat to life, responding with lethal force may be justified.'],
                    ['sub', 'Multiple Attackers', 'Being outnumbered may increase the perceived threat and may justify a greater level of force where the number of attackers creates a reasonable threat of serious bodily harm or death.'],
                    ['sub', 'Mounted Attackers', 'A mounted attacker may present a substantially greater threat due to the combination of mobility, height, and potential weapons. The circumstances should be considered when determining whether a higher level of force is proportionate.'],
                    ['sub', 'Defending a Downed Person', 'A player may use force necessary to protect a downed or incapacitated person when there is a reasonable and imminent threat of death or serious bodily harm to that person.'],
                    ['p', 'Players should consider whether they have reasonable alternatives, including retreat or escape, where practical. Proportionality is determined by the circumstances as reasonably perceived at the time of the encounter, not solely by the weapon or force used by the opposing player.'],
                    ['h', 'Kill on Sight (KOS)'],
                    ['p', 'Kill on Sight refers to intentionally attempting to kill another character when you see them. That said, players are expected to establish a legitimate reason for lethal force through roleplay and should not treat previous encounters, faction affiliations, or OOC information as automatic justification for killing another character.'],
                    ['sub', 'Previous Conflicts', 'Being involved in a previous shootout, robbery, feud, or other conflict does not give a player permanent permission to kill the other party whenever they encounter them again. Further escalation should be supported by the ongoing RP. Refer to Rule 31 (Revenge Rules).'],
                    ['sub', 'Faction Membership', 'A conflict between two factions does not automatically make every member of either faction a valid target unless the conflict has legitimately escalated into faction-wide hostilities under the applicable faction rules. For more information, file a ticket.'],
                    ['sub', 'Reputation', 'Knowing that someone is an outlaw, criminal, bad cop, or member of a hostile organization does not, by itself, justify killing them. However, exceptions may apply in cases involving notorious or repeated criminal activity, or when a character has an established and legitimate wanted status. Continued involvement in banditry or criminal activity may instead result in properly initiated roleplay and enforcement actions from Law Enforcement.'],
                    ['sub', 'Guilt by Association', 'A character may not be killed simply because they are a friend, relative, employee, associate, or member of the same organization as someone involved in a conflict, unless they have personally become involved in the situation.'],
                    ['sub', 'OOC Information', 'Players may not use information obtained through Discord, streams, voice recognition, metagaming, or other OOC means to identify or locate a character for the purpose of killing them.'],
                    ['sub', 'Identification Must Be Reasonable', 'If a character has made a legitimate effort to conceal their identity, players must respect the disguise and may not use OOC knowledge to identify them.'],
                    ['sub', 'Proportionality', 'The reason for an attack should be reasonable and proportionate to the circumstances. Minor disputes, insults, disagreements, or ordinary criminal encounters should not automatically escalate into an attempt to kill another character.'],
                    ['sub', 'Self-Defense', 'Players may defend themselves or others when faced with an immediate and credible threat of serious harm. This includes, but is not limited to, private properties such as ranches and gang bases, where unknown individuals unlawfully trespass without making a reasonable attempt to locate or speak with the occupants before entering further onto the property. Any response should remain reasonable and appropriate to the circumstances.'],
                    ['sub', 'Ambushes & Planned Killings', 'Being able to surprise another character does not remove the requirement for a legitimate IC reason to attempt to kill them. Be creative, and always consider the experience of everyone involved.'],
                    ['list', 'Restricted KOS — requires stronger RP justification:', 'mustnot', [
                        'Personal vendettas',
                        'Faction disputes',
                        'Bounties',
                        'Revenge RPs',
                        'Criminal contracts',
                        'Political conflicts',
                        'Territory disputes',
                    ]],
                    ['list', 'Legitimate Lethal Escalation — potentially permissible when supported by RP:', 'must', [
                        'Immediate self-defense',
                        'Defense of others',
                        'Active armed conflict',
                        'Clearly established faction warfare',
                        'Execution following legitimate RP circumstances',
                        'A properly established assassination/contract scenario',
                    ]],
                    ['note', 'KOS rules are intended to prevent meaningless or unjustified killing, not to prevent legitimate conflict. Characters may develop rivalries, vendettas, faction conflicts, and other situations that can eventually lead to lethal consequences, provided these are properly established and supported through roleplay.'],
                ],
            ],
            [
                'id' => 'rdm', 'num' => 26, 'title' => 'Random Deathmatch (RDM)',
                'blocks' => [
                    ['p', 'Random Deathmatch (RDM) is the act of attacking or killing another player without sufficient in-character justification or meaningful roleplay.'],
                    ['p', 'Every act of violence must be supported by believable narrative context.'],
                    ['list', 'Examples of valid motivations include:', 'must', [
                        'Long-standing personal or faction conflicts.',
                        'Betrayal or deception revealed through roleplay.',
                        'Criminal activity with logical consequences.',
                        'Escalated disputes that have naturally progressed.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Attack or kill another player without roleplay context.',
                        'Invent weak excuses solely to justify violence.',
                        'Initiate conflict simply because another player is nearby.',
                        'Rob or kill players purely for material gain.',
                    ]],
                    ['note', 'If you are uncertain whether a situation justifies violence, choose roleplay first or seek staff clarification.'],
                ],
            ],
            [
                'id' => 'shootouts', 'num' => 27, 'title' => 'Shootouts',
                'blocks' => [
                    ['lead', 'Shootouts should feel cinematic, dangerous, and emotionally driven — not mechanical.'],
                    ['p', 'Characters are expected to value their lives throughout every firefight. Fear, hesitation, injury, and uncertainty should remain central to the experience.'],
                    ['list', 'Players should:', 'must', [
                        'Take cover and preserve their character\'s life.',
                        'Roleplay injuries throughout and after the encounter.',
                        'Use voice and roleplay commands to portray fear, pain, exhaustion, or hesitation.',
                        'Bandage and seek treatment through roleplay whenever appropriate.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Ignore injuries sustained during combat.',
                        'Rush enemies without regard for personal safety.',
                        'Treat shootouts as deathmatches or competitions.',
                        'Continue fighting as though unaffected after severe injuries.',
                    ]],
                    ['note', 'The most memorable shootouts are those remembered for their story — not their kill count.'],
                ],
            ],
            [
                'id' => 'duels', 'num' => 28, 'title' => 'Duels',
                'blocks' => [
                    ['p', 'Duels are formal agreements between two participants to resolve conflict through combat.'],
                    ['p', 'By participating in a duel, both players acknowledge the serious risks involved. Characters who survive should roleplay severe, lasting injuries appropriate to the outcome of the duel. Depending on the circumstances and prior agreement, a Character Kill (CK) may also occur.'],
                    ['note', 'Players uncertain about duel procedures or potential CK implications should contact staff before proceeding.'],
                ],
            ],
            [
                'id' => 'torture', 'num' => 29, 'title' => 'Torture & Permanent Harm',
                'blocks' => [
                    ['p', 'Roleplay involving torture, permanent injury, disfigurement, lasting psychological trauma, or Character Kill (CK) requires fresh and explicit Out-of-Character (OOC) consent from all affected participants for every individual scene. Consent given for one scene does not constitute consent for any future scene.'],
                    ['p', 'Consent must be freely given and may be withdrawn at any time before or during the scene.'],
                    ['list', 'Players must:', 'must', [
                        'Obtain clear OOC consent using the /ooc command before initiating any of the following: torture roleplay, permanent injury, disfigurement, lasting psychological trauma, or Character Kill (CK).',
                        'Receive an affirmative response through the /ooc command before proceeding.',
                        'Obtain fresh consent for each separate scene, even if the same characters have previously consented to similar roleplay.',
                        'Respect another player\'s decision to decline or withdraw consent.',
                        'Prioritize player comfort throughout the scene.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Force or initiate torture, permanent injury, disfigurement, psychological trauma, or CK roleplay without the required OOC consent.',
                        'Use torture or permanent harm solely as a means to obtain a CK without explicit consent.',
                        'Bypass or manipulate the consent requirement through technicalities, roleplay pressure, threats, or IC circumstances.',
                        'Treat previous consent as continuing consent for future scenes.',
                        'Use the /ooc command solely to evade RP. If a player declines the requested permanent-harm RP, they are still expected to continue the applicable RP and honor existing NVF rules.',
                    ]],
                    ['note', 'IC retaliation, revenge, prior actions, or other in-character circumstances never replace or override the requirement for explicit OOC consent. A player does not lose their right to refuse permanent-harm or CK roleplay simply because their character has previously harmed, threatened, or retaliated against another character.'],
                    ['h', 'Torture vs. Coercive Acts'],
                    ['p', 'For the purposes of this rule, torture refers to deliberate acts intended to inflict prolonged or severe physical or psychological suffering, particularly when the suffering itself is the focus of the roleplay.'],
                    ['p', 'Ordinary threatening or coercive acts during captivity do not automatically constitute torture. Actions such as pointing a firearm at a captive, threatening them with harm, choking them briefly, punching them, or other acts of intimidation may be performed as part of normal RP, provided they do not escalate into torture, permanent injury, disfigurement, lasting psychological trauma, or CK.'],
                    ['note', 'The distinction is based on the nature, severity, duration, and intent of the act. Players must not use ordinary threatening acts as a loophole to conduct prolonged or excessive torture without the required OOC consent.'],
                ],
            ],
            [
                'id' => 'body-disposal', 'num' => 30, 'title' => 'Body Disposal',
                'blocks' => [
                    ['p', 'Moving or concealing a downed character\'s body should serve the story rather than personal convenience.'],
                    ['p', 'Characters who have committed violent acts would generally seek to avoid drawing unnecessary attention to themselves. Likewise, allowing victims to be discovered often creates further investigative and medical roleplay.'],
                    ['list', 'If a body is moved:', 'must', [
                        'It must remain in a location that is reasonably discoverable.',
                        'Dumping bodies beyond the map boundaries or into inaccessible areas is prohibited.',
                        'Players should leave the area once the scene has concluded rather than remaining nearby.',
                    ]],
                    ['note', 'Body disposal should create additional roleplay opportunities — not remove them.'],
                ],
            ],
            [
                'id' => 'revenge-nlr', 'num' => 31, 'title' => 'Revenge Rules (New Life Rule)',
                'blocks' => [
                    ['p', 'Once a violent encounter has concluded, players may not immediately resume hostilities for the same reason.'],
                    ['list', 'After recovering from a conflict, players may not:', 'mustnot', [
                        'Return to continue the same fight.',
                        'Provoke another player solely to create a new excuse for violence.',
                        'Resume combat after respawning.',
                    ]],
                    ['p', 'Conflict may still continue in RP through different means. Your character may not use the previous conflict as personal justification to initiate another violent confrontation. However, the actions and consequences of the original incident may continue through separate roleplay.'],
                    ['list', 'Allowed:', 'must', [
                        'You are downed during a brawl, receive medical treatment, and remember who attacked you. You may discuss the incident, report it, or allow it to influence your character\'s future roleplay.',
                        'The person responsible for your injuries may still face consequences through law enforcement, witnesses, medical reports, or other legitimate roleplay.',
                    ]],
                    ['list', 'Not Allowed:', 'mustnot', [
                        'You are treated by a doctor, remember who attacked you, and immediately seek them out to start another fight solely because of the previous incident.',
                        'Using the incident as an excuse to repeatedly restart the same conflict after it has already concluded.',
                    ]],
                    ['note', 'Every conflict should have consequences. Allow stories to evolve instead of repeating the same encounter. This is to provide cooldown towards PvP and prevent unnecessary revenge killing that may lead to unintended violations. Additionally, this is to give way and respect the Medical RP that would be initiated by the doctors who will treat you.'],
                    ['h', 'Respawning ("See the Light")'],
                    ['p', 'If a player decides to See the Light (the in-game respawn mechanic), the player acknowledges the following rules. The player is obligated by the server to adhere to the following:'],
                    ['list', '', 'must', [
                        'Your character forgets the conflict and the reason why you were downed.',
                        'If someone reminds you of the incident, you may bypass the amnesia rule and use the previous scenario for lore-building, but not as a reason to retaliate.',
                        'You are not allowed to retaliate and use the initial conflict you were in to initiate another one.',
                        'You are not allowed to return to the area where you died for one whole hour.',
                        'Choosing to respawn is already an acknowledgement that you will move on from the scenario. If no medical personnel are available, players may use /ooc (e.g., "wala pong doktor, rule 31 po kami respawn and act as if we were treated") to acknowledge the situation and roleplay the character as having been treated by a doctor before continuing.',
                    ]],
                ],
            ],
            [
                'id' => 'public-conflict-town-safety', 'num' => 32, 'title' => 'Public Conflict & Town Safety',
                'blocks' => [
                    ['p', 'Towns and settlements are considered active and populated areas at all times. Players are expected to acknowledge the presence and activity of the town even when no other players are visibly present.'],
                    ['p', 'Players who are not directly involved in an ongoing conflict should practice Fear of Life/Value of Life and should not unnecessarily involve themselves in active gunfights or dangerous situations.'],
                    ['p', 'Those who choose to initiate a gunfight or violent conflict within a town must understand the additional risks that come with doing so. Violence in populated areas may reasonably attract witnesses, law enforcement, civilians, or other characters who may become involved in the resulting roleplay.'],
                    ['p', 'The absence of nearby players does not remove the expectation of treating a town as an active environment. Players should consider the location and its role within the world when deciding to engage in violence.'],
                    ['p', 'This same principle applies to banditry, robberies, and heists conducted within or around populated areas. Players should account for the possibility of witnesses, law enforcement, and other consequences when carrying out criminal activity in public.'],
                    ['note', 'The number of players currently present does not determine how populated a location is for roleplay purposes.'],
                    ['list', 'Guidelines:', 'neutral', [
                        'Factions who decide to visit towns for roleplay purposes are allowed to have a maximum of 12 people. This is in consideration of RedM\'s limitation on entities within an area.',
                        'These factions/groups are expected to roleplay and exit the area after the roleplay concludes.',
                        'Specific rules on heists that occur within town boundaries will be implemented if a group intends to commit one.',
                        'Value of Life/Value of Fear rules apply accordingly — roleplay it out, and do not treat the situation recklessly just because you feel you can handle yourself.',
                        'Local Law Enforcement in that town are expected to roleplay accordingly and may impose their respective penal codes. However, consequences may vary, and compliance is not the utmost priority when a significant number of faction/group members are present — be smart.',
                    ]],
                ],
            ],
            [
                'id' => 'looting-guidelines', 'num' => 33, 'title' => 'Looting Guidelines',
                'blocks' => [
                    ['p', 'Players who choose to loot another character are expected to approach the situation with consideration for the roleplay and effort invested by others in acquiring their possessions.'],
                    ['p', 'Robberies should be conducted for meaningful roleplay and storytelling rather than solely for obtaining items, money, or assets. Players should avoid treating other characters as sources of loot and should prioritize creating an engaging scenario for everyone involved.'],
                    ['note', 'Robbery should create roleplay, not simply provide rewards. Pocket-wiping is not allowed.'],
                    ['note', 'Players may only be looted once after a scenario. A cooldown of 1 hour is placed before a player may be looted again.'],
                    ['list', 'Allowed looting threshold:', 'neutral', [
                        'Maximum of 50% of a player\'s money in their inventory.',
                        'Maximum of 50% of a player\'s gold bars in their inventory. (If there\'s only one, you may loot it as is.)',
                        'One high-caliber weapon (e.g., repeaters, shotguns, rifles, etc.).',
                        'Either one low-caliber weapon or one melee weapon (e.g., swords, spears, revolvers, bows, etc.).',
                        'Half the amount of any consumable stack in a player\'s pocket (e.g., if a player has 10 adobo, you are only allowed to loot 5).',
                        'Half of an item stack in a player\'s inventory (e.g., a player has 20 wood, you may get 10).',
                    ]],
                    ['list', 'Items not allowed for looting:', 'mustnot', [
                        'Bags and pouches.',
                        'Cosmetic items and props.',
                        'Donated props.',
                        'Items bought from the Furniture Store (Mwebles).',
                        'Special RP items.',
                    ]],
                ],
            ],
            [
                'id' => 'redzone-pvp-areas', 'num' => 34, 'title' => 'Redzone/PvP Areas',
                'blocks' => [
                    ['p', 'Certain areas within Pulang Lupa may be designated as Redzones or PvP Areas, where players may engage in combat under the specific rules established for that location or ongoing RPs within the area.'],
                    ['note', 'Players who enter these areas are expected to understand and accept the increased risk of PvP. Entering a designated Redzone does not remove the requirement to maintain proper roleplay, follow combat rules, or respect other applicable server rules.'],
                    ['h', 'Alamat'],
                    ['p', 'Hunting Alamat (endangered species) within their designated areas will trigger an alert to the relevant Tribes. This alert serves as an in-character indication that an Alamat is being hunted, and authorizes the Tribes to respond through legitimate roleplay and PvP.'],
                    ['h', 'Alamat Rules'],
                    ['sub', 'Alert', 'The Alamat alert is sent exclusively to the relevant Tribes. The alert confirms that an Alamat hunting incident is taking place within the designated trigger area.'],
                    ['sub', 'Targetability', 'Alamat hunting areas are considered killzones. Once an Alamat alert is triggered, players participating in the hunting party may be engaged by the responding Tribe. Players who enter or participate in the encounter may also become involved in the resulting PvP. The alert does not, however, exempt participants from the general Immersion & Realism rules.'],
                    ['sub', 'Warning', 'No verbal warning or prior engagement is required. Responding Tribes may immediately initiate hostile action against the hunting party upon responding to an Alamat alert.'],
                    ['sub', 'Force & Numbers', 'A maximum of 4 Tribal members from each faction-approved tribe may participate as interceptors in response to an Alamat alert. The hunting party may consist of up to 6 members, representing the hunters and those assisting or protecting the party from the Alamat itself. No more than 4 Tribal members may actively participate in the interception.'],
                    ['sub', 'Zone & Pursuit', 'The Alamat trigger zone determines where the initial incident occurs but does not impose a fixed distance or duration on the resulting engagement. Horse chases and foot chases are permitted, and the pursuit may continue beyond the original Alamat area.'],
                    ['sub', 'Pursuit Limitations', 'Players should not intentionally route a chase through towns or populated areas in a manner that unnecessarily involves uninvolved civilians who are already outside the Alamat trigger zone. Players are not allowed to involve civilians outside the Alamat trigger zone in the ongoing chase and gunfight, and must make reasonable efforts to keep the pursuit within areas where the encounter can continue without unnecessarily involving unrelated players.'],
                    ['sub', 'Ordinary Rules', 'The Alamat-specific exceptions remain in effect for the duration of the active encounter and pursuit. Once the gunfight or active pursuit has ended, normal PvP, initiation, and other server rules immediately resume.'],
                    ['sub', 'Disengagement', 'A player may disengage once the active gunfight or pursuit has ended. Once both sides have ceased active engagement and the encounter has concluded, the Alamat exception no longer applies, and any subsequent attempt to re-engage must comply with the server\'s ordinary PvP and initiation rules.'],
                    ['sub', 'Crossfire', 'Uninvolved players who are caught in the crossfire will be governed by Rule 20 (Immersion & Realism). The Alamat rules do not provide unrestricted permission to intentionally involve civilians or unrelated players in the encounter.'],
                    ['sub', 'Rule Compliance', 'The Alamat exception exists specifically to facilitate RP surrounding the protection and hunting of endangered species. It must not be used as a means to circumvent other server rules outside of the specific exceptions stated above.'],
                ],
            ],
        ],
        'violations' => 'Violations of the Conflict & Combat rules may result in administrative action, including scene reversals, warnings, temporary suspensions, Character Kills where appropriate, permanent bans, or other disciplinary measures depending on the severity, intent, and repetition of the violation.',
    ],

    /* ── CHAPTER 5 ─────────────────────────────────────────── */
    [
        'id'       => 'chapter-5',
        'numeral'  => 'V',
        'label'    => 'Chapter 5',
        'title'    => 'Economy & Assets',
        'desc'     => 'Ownership, inheritance, and keeping the economy fair.',
        'rules'    => [
            [
                'id' => 'asset-transfer', 'num' => 35, 'title' => 'Asset Transfer',
                'blocks' => [
                    ['p', 'Characters are expected to acquire, manage, and transfer their assets through legitimate in-character roleplay. Ownership should always reflect the actions and decisions made within Pulang Lupa rather than Out-of-Character arrangements.'],
                    ['list', 'Players may not:', 'mustnot', [
                        'Transfer money, property, businesses, or valuables between their own characters.',
                        'Circumvent progression by using alternate characters or third parties.',
                        'Transfer assets through OOC agreements or external platforms.',
                        'Bypass server systems intended to regulate ownership.',
                    ]],
                    ['note', 'All significant transfers should occur through legitimate in-character means whenever possible.'],
                ],
            ],
            [
                'id' => 'wills', 'num' => 36, 'title' => 'In-Character Wills',
                'blocks' => [
                    ['p', 'Characters may prepare a legal Last Will and Testament through an in-character process with a licensed lawyer. Wills allow characters to pass on limited personal assets after their death while preserving fairness and progression.'],
                    ['p', 'The following limitations apply:'],
                    ['h', 'Eligible Assets'],
                    ['list', 'A will may include:', 'must', [
                        'Up to three (3) horses, with a maximum of one horse per beneficiary.',
                        'Up to five (5) individual items, excluding personal journals, letters, and written documents.',
                    ]],
                    ['h', 'Restricted Assets'],
                    ['list', 'The following may not be transferred through a will:', 'mustnot', [
                        'Money or bank balances.',
                        'Businesses or business ownership.',
                        'General property ownership unless otherwise permitted by these rules.',
                    ]],
                    ['h', 'Property Transfers'],
                    ['list', 'Real Estate property may only be inherited if:', 'neutral', [
                        'The beneficiary is officially registered as a co-owner through the Real Estate Records; or',
                        'The beneficiary is the deceased\'s legal spouse, and the marriage has existed for at least 30 OOC days.',
                    ]],
                    ['h', 'Validity Requirements'],
                    ['list', 'To be considered valid:', 'neutral', [
                        'Wills must be completed and filed at least 14 days before the character\'s death.',
                        'Beneficiaries must be active player characters.',
                        'Wills may only name individual characters and may not designate factions or organizations as beneficiaries.',
                    ]],
                    ['p', 'Players with older wills that no longer comply with these policies should update them through roleplay before they are executed.'],
                    ['note', 'Using group banking systems or similar mechanics to transfer wealth after a Character Kill is prohibited.'],
                ],
            ],
            [
                'id' => 'whitelisted-jobs', 'num' => 37, 'title' => 'Whitelisted Jobs',
                'blocks' => [
                    ['p', 'Whitelisted jobs represent trusted professions that help maintain the continuity, realism, and stability of Pulang Lupa. Players are expected to treat these positions as long-term character commitments rather than temporary gameplay opportunities.'],
                    ['h', 'Cooldown'],
                    ['p', 'Characters who voluntarily resign from or are removed from a whitelist job may not join another whitelist job for 7 OOC days.'],
                    ['note', 'This cooldown encourages meaningful career progression and prevents unrealistic movement between organizations. Players should pursue positions through roleplay, demonstrate commitment to their organization, and respect the responsibilities associated with their role.'],
                    ['h', 'Types of Whitelisted Jobs'],
                    ['p', 'There are two types of Whitelisted Jobs: Salary Whitelisted Jobs and Crafting (Blue-Collar) Whitelisted Jobs.'],
                    ['sub', 'Salary Whitelisted Jobs', 'Government Officials, Law Enforcement, and Doctors. These positions operate under a salary or paycheck system and do not require crafting. Salary Whitelisted employees may be dismissed at the discretion of their respective Leaders, who are responsible for supervising and managing their respective trades.'],
                    ['sub', 'Crafting (Blue-Collar) Whitelisted Jobs', 'Mataderos, Pandays, Ensayadors, Herbolarios, Katutubos, and Armeros. These positions do not operate under a salary system and instead rely on the worker\'s acquired skills to produce goods for the community.'],
                    ['p', 'Unlike Salary Whitelisted Jobs, Crafting Whitelisted Jobs are considered specialized skills. Once a worker has learned the craft, they are not to be arbitrarily removed from the trade. The responsibility of the Pinuno is instead to coordinate with the respective Heads of State regarding the management and access of crafting tables throughout the country. Crafting access should not be revoked without legitimate reason, as the Pinuno\'s role is more closely aligned with that of a labor leader.'],
                    ['p', 'Workers who are formally recognized by their respective Pinuno may receive additional benefits, perks, bonuses, or equipment provided by the organization, such as horses, carriages, or other job-related assets.'],
                    ['note', 'All server-provided assets issued to Whitelisted Job members, including horses, carriages, equipment, or other benefits, must be returned when the employee resigns, is dismissed, or otherwise leaves the Whitelisted Job. This applies to both Salary and Crafting Whitelisted Jobs. Failure to return server-provided assets may be subject to administrative action.'],
                    ['p', 'Workers who choose to separate themselves from or no longer recognize the authority of their respective Pinuno may lose access to organizational benefits. Any organization-provided benefits or assets must be returned in accordance with the rules above.'],
                    ['h', 'Maximum Number of Whitelisted Employees per Job Category'],
                    ['sub', 'Government Systems', '5 — access to 1 Official State Carriage and 1 Official Horse for the Head of State.'],
                    ['sub', 'Law Enforcement', '20 — access to 1 Supply Wagon and horses for the employees.'],
                    ['sub', 'Doctors', '20 — access to horses for the employees.'],
                    ['sub', 'Matadero', 'No limit — access to Supply Wagons for established members in RP.'],
                    ['sub', 'Panday', 'No limit — access to Supply Wagons for established members in RP.'],
                    ['sub', 'Ensayador', 'No limit — access to Supply Wagons for established members in RP.'],
                    ['sub', 'Herbolario', 'No limit — access to Supply Wagons for established members in RP.'],
                    ['sub', 'Katutubo', 'No limit — access to Supply Wagons for established members in RP.'],
                    ['h', 'Law Enforcement Limitations'],
                    ['p', 'As part of maintaining fairness to gameplay, the server does not allow Dirty Law Enforcement/Dirty Cop RPs from Law Enforcement jobs (Militia, Sibil, Kawal) — for example, stashing weapons to distribute to non-whitelisted players, or using functions such as "kapkap" and other functions exclusive to Government Factions to gain an advantage over other players.'],
                    ['note', 'These actions count as Powergaming and will be dealt with under the same punishment as Rule 18.'],
                    ['p', 'However, for extensive immersion, Bad Cop RPs such as corruption and bribery are allowed, as long as they don\'t involve whitelist-restricted functions.'],
                    ['list', 'Sentencing limits:', 'neutral', [
                        'The maximum jail sentence that may be imposed is 6 hours of OOC time. Players may reduce their sentence through applicable RP or in-game mechanics.',
                        'If a state\'s penal code would result in a sentence exceeding this limit, the sentence must still be capped at 6 hours OOC time. Imposing or enforcing a sentence beyond this maximum will be considered Powergaming.',
                        'The maximum amount imposed for fines is ₱1,000, regardless of the penal code of the state.',
                    ]],
                ],
            ],
            [
                'id' => 'business-limits', 'num' => 38, 'title' => 'Business Limits',
                'blocks' => [
                    ['p', 'Businesses exist to create compelling stories, not to dominate the server\'s economy or accumulate unrestricted wealth.'],
                    ['p', 'Business ownership is subject to administrative oversight and may be limited to preserve economic balance, encourage competition, and support diverse roleplay opportunities across the community.'],
                    ['list', 'Players may not:', 'mustnot', [
                        'Create shell businesses solely for mechanical advantage.',
                        'Circumvent ownership limitations through alternate characters or proxy ownership.',
                        'Hoard their own items for personal gain.',
                    ]],
                    ['note', 'Businesses should always contribute to roleplay first and function as believable parts of the world rather than purely financial assets.'],
                ],
            ],
        ],
        'violations' => 'Violations of the Economy & Asset rules may result in administrative action, including asset removal, ownership reversal, confiscation of improperly obtained property, character adjustments, warnings, temporary suspensions, permanent bans, or other measures deemed appropriate based on the severity, intent, and repetition of the violation.',
    ],

    /* ── CHAPTER 6 ─────────────────────────────────────────── */
    [
        'id'       => 'chapter-6',
        'numeral'  => 'VI',
        'label'    => 'Chapter 6',
        'title'    => 'Miscellaneous Guidelines',
        'desc'     => 'Cultural respect and responsible use of audio.',
        'rules'    => [
            [
                'id' => 'native-roleplay', 'num' => 39, 'title' => 'Native Roleplay',
                'blocks' => [
                    ['p', 'Pulang Lupa is inspired by Philippine history and culture. Players portraying native or indigenous characters are expected to do so respectfully and in a manner that supports immersive storytelling.'],
                    ['p', 'Native cultures should not be reduced to stereotypes, caricatures, or exaggerated portrayals for entertainment. Players are encouraged to research the customs, traditions, and values appropriate to their chosen background whenever possible.'],
                    ['list', 'When portraying native characters, players must:', 'must', [
                        'Maintain respectful and believable roleplay.',
                        'Avoid offensive stereotypes or exaggerated behavior.',
                        'Represent cultural traditions in good faith.',
                        'Remain consistent with the historical setting of 1890.',
                    ]],
                    ['note', 'Roleplay that intentionally mocks, misrepresents, or disrespects indigenous cultures may result in administrative action.'],
                ],
            ],
            [
                'id' => 'music-audio', 'num' => 40, 'title' => 'Music & Audio Policy',
                'blocks' => [
                    ['p', 'Music and ambient audio can greatly enhance immersion when used appropriately. Players are expected to use audio responsibly and ensure it contributes positively to the roleplay experience.'],
                    ['list', 'Players may:', 'must', [
                        'Play era-appropriate music during roleplay.',
                        'Use ambient sounds that enhance immersion.',
                        'Perform music in-character through appropriate roleplay.',
                    ]],
                    ['list', 'Players may not:', 'mustnot', [
                        'Play modern music during active roleplay unless specifically appropriate to the scene.',
                        'Use excessively loud, distorted, or disruptive audio.',
                        'Play sounds intended to distract, annoy, or gain an unfair advantage.',
                        'Broadcast offensive, explicit, or discriminatory content.',
                    ]],
                    ['note', 'Always be mindful of the experience of those around you. Audio should complement a scene — not dominate it.'],
                ],
            ],
            [
                'id' => 'streamer-etiquette', 'num' => 41, 'title' => 'Streamer Etiquette',
                'blocks' => [
                    ['p', 'Pulang Lupa RP welcomes content creators and encourages players to share their experiences with the community. Streamers are expected to uphold the same standards of professionalism, immersion, and sportsmanship as every other player.'],
                    ['list', 'Players who choose to stream should:', 'must', [
                        'Avoid using live chat or viewer interaction to influence in-character decisions.',
                        'Refrain from encouraging harassment, witch hunts, or targeted negativity toward players, staff, or the community.',
                        'Respect ongoing roleplay and avoid using their platform to intentionally disrupt or undermine another player\'s experience.',
                        'Avoid using their platform for metagaming.',
                        'Understand that streaming does not exempt them from any Server Rules or administrative action.',
                    ]],
                    ['note', 'Viewers should never be used as a source of in-character information. Any attempt to gain an advantage through a live broadcast may be treated as Metagaming or Stream Sniping where applicable.'],
                    ['p', 'Players who do not stream are expected to respect those who do. Stream sniping, harassment, or intentionally disrupting another player\'s broadcast is strictly prohibited.'],
                    ['p', 'Administration may periodically review publicly available streams and recorded broadcasts to help assess player behavior, investigate reports, verify potential rule violations, or ensure that server policies are being followed. While streamed footage may be considered during investigations, it does not automatically determine the outcome of a case. All reports and evidence are evaluated fairly and in their proper context.'],
                ],
            ],
        ],
        'violations' => 'Violations of the Miscellaneous Guidelines may result in warnings, temporary suspensions, permanent bans, or other administrative action depending on the severity, intent, and repetition of the violation.',
    ],

    /* ── CHAPTER 7 ─────────────────────────────────────────── */
    [
        'id'       => 'chapter-7',
        'numeral'  => 'VII',
        'label'    => 'Chapter 7',
        'title'    => 'Support & Administration',
        'desc'     => 'How to get help, report violations, and appeal decisions.',
        'rules'    => [
            [
                'id' => 'ticketing', 'num' => 42, 'title' => 'Ticketing',
                'blocks' => [
                    ['p', 'The ticket system exists to provide support and resolve server-related matters efficiently.'],
                    ['list', 'When creating a ticket:', 'must', [
                        'Use the appropriate ticket category.',
                        'Clearly explain the issue and provide relevant evidence whenever possible.',
                        'Do not create duplicate tickets for the same matter.',
                        'Be patient and allow staff reasonable time to respond.',
                    ]],
                    ['p', 'Management may request additional evidence, including video clips or recordings, if they are relevant to the situation. Providing these materials can help staff better understand the context of an incident, but does not guarantee a favorable outcome.'],
                    ['p', 'Submitting a ticket for review does not automatically retcon or invalidate the roleplay in question. Any decision to retcon a scene rests solely with the Administration. If you believe a retcon is appropriate, you may request one within the same ticket.'],
                    ['note', 'False, misleading, or malicious tickets may result in administrative action.'],
                    ['h', 'Filing Timeframes'],
                    ['p', 'Players are only allowed to file tickets for specific types of violations within specific timeframes:'],
                    ['sub', 'Item Duping, Exploits, Bug Abuse', 'No limit.'],
                    ['sub', 'Racism, Sexism, and Other Forms of Bigotry', 'No limit.'],
                    ['sub', 'Scenario-Based / RP Violations', '24 hours after the scenario concluded.'],
                    ['note', 'The server reserves the right to enforce rules applicable during the time when a violation occurred, regardless of rule changes that followed.'],
                    ['note', 'Players who leak or share ticket contents with individuals not involved in the ticket will be sanctioned severely.'],
                    ['h', 'Ban Duration Reference Chart'],
                    ['sub', 'Minor', '1–3 days or 50–100 community service — minor rule violations or low-impact misconduct.'],
                    ['sub', 'Moderate', '3–7 days or 100–200 community service — clear violations with limited impact on RP.'],
                    ['sub', 'Serious', '7–14 days or 200–300 community service — significant RP violations or repeated misconduct.'],
                    ['sub', 'Major', '14–30 days or 300–500 community service — serious violations causing substantial disruption.'],
                    ['sub', 'Severe', '30–90 days or 500–1,000 community service — major misconduct and repeated serious violations.'],
                    ['sub', 'Bug Exploitation', 'Permanent — abusing bugs, glitches, or unintended game mechanics.'],
                    ['sub', 'Cheating', 'Permanent — use of cheats, scripts, injectors, or other unauthorized software.'],
                    ['sub', 'Permanent-Level', 'Permanent — conduct serious enough for permanent removal from the server.'],
                    ['h', 'Repeat Offender Escalation'],
                    ['sub', 'First Offense', 'Apply the standard duration.'],
                    ['sub', 'Second Offense', 'Increase the standard duration.'],
                    ['sub', 'Third Offense', 'Significant increase; consider 30+ days.'],
                    ['sub', 'Repeated / Persistent Offenses', '60–90 days or permanent.'],
                    ['sub', 'Repeat of a Previously Banned Offense', 'Consider substantially increasing the penalty.'],
                    ['sub', 'Bug Exploitation / Cheating', 'Permanent, regardless of prior history.'],
                    ['note', 'Admin discretion: these durations are reference points rather than strict automatic sentences. The final punishment will always be decided by the Administration.'],
                ],
            ],
            [
                'id' => 'reporting-violations', 'num' => 43, 'title' => 'Reporting Rule Violations',
                'blocks' => [
                    ['p', 'Players are encouraged to report rule violations that negatively affect the server.'],
                    ['p', 'Reports should be truthful, supported by evidence where possible, and submitted in good faith. Reports made to harass, retaliate in bad faith, or inconvenience another player are prohibited.'],
                    ['note', 'Do not publicly discuss or argue active reports. Failure to do so may result in sanctions.'],
                ],
            ],
            [
                'id' => 'conduct-towards-staff', 'num' => 44, 'title' => 'Conduct Towards Staff',
                'blocks' => [
                    ['p', 'Treat all staff members with respect and professionalism.'],
                    ['p', 'Players may ask questions, seek clarification, or respectfully disagree with a decision through the appropriate channels. Harassment, insults, threats, or attempts to pressure staff are not tolerated.'],
                    ['note', 'Do not contact staff through direct messages unless requested.'],
                ],
            ],
            [
                'id' => 'appeals', 'num' => 45, 'title' => 'Appeals',
                'blocks' => [
                    ['p', 'If you believe an administrative decision was made in error, you may submit an appeal through the designated process.'],
                    ['p', 'Appeals should remain respectful, include any relevant information, and explain why the decision should be reviewed. Decisions made after a completed appeal are considered final unless reopened by Server Management.'],
                ],
            ],
        ],
        'violations' => 'Abuse of the server\'s support or administrative systems, including false reports, malicious tickets, falsified evidence, harassment of staff, or attempts to circumvent administrative decisions, may result in warnings, temporary suspensions, permanent bans, or any other action deemed appropriate by Server Management.',
    ],

    /* ── CHAPTER 8 — AFTERWORD ─────────────────────────────── */
    [
        'id'       => 'chapter-8',
        'numeral'  => 'VIII',
        'label'    => 'Chapter 8',
        'title'    => 'Afterword',
        'desc'     => 'A closing note from the owner of Pulang Lupa RP.',
        'type'     => 'afterword',
        'paragraphs' => [
            'I first discovered RedM in 2024 while researching for an article I planned to write for a local publication where I was tasked to cover pop culture trends in the Philippines. My original intention was to explore FiveM as I was seeing a lot of funny clips on social media, but I found myself drawn to RedM instead. The world, atmosphere, and possibilities offered by the RDR2 engine immediately stood out to me. Ironically, the article was never published, but my journey with RedM continued.',
            'What began as simple curiosity eventually grew into something much bigger. Today, I have the privilege of leading Pulang Lupa RP and providing a place where people from different backgrounds can come together to tell unforgettable stories. That opportunity is something I never take for granted.',
            'This community would not exist without the people who believed in it. To Kaloy and Jen, our administration team, our silent supporters and donors, and everyone who contributed their time, ideas, and effort behind the scenes, thank you. Pulang Lupa would not exist without your dedication and belief in this vision.',
            'Finally, remember that rules alone do not create great roleplay. Players do. Every conversation, friendship, rivalry, triumph, and failure becomes part of the history we build together. We encourage every member of our community to roleplay with respect, creativity, and an open mind, because the greatest stories are never written alone.',
        ],
        'welcome'   => 'Welcome to Pulang Lupa.',
        'signoff'   => 'Yours truly,',
        'signature' => 'Caleb, a.k.a. Agapito Dumurangapoy',
        'sigrole'   => 'Owner, Pulang Lupa RP',
    ],
];

/* -------------------------------------------------------------------------
   BLOCK RENDERER
------------------------------------------------------------------------- */
function plrp_render_block( $block ) {
    $type = $block[0];
    switch ( $type ) {
        case 'lead':
            echo '<p class="rule-lead">' . esc_html( $block[1] ) . '</p>';
            break;
        case 'p':
            echo '<p>' . esc_html( $block[1] ) . '</p>';
            break;
        case 'h':
            echo '<h4 class="rule-subhead">' . esc_html( $block[1] ) . '</h4>';
            break;
        case 'sub':
            echo '<div class="rule-subcard">';
            echo '<span class="rule-subcard-title">' . esc_html( $block[1] ) . '</span>';
            echo '<p>' . esc_html( $block[2] ) . '</p>';
            echo '</div>';
            break;
        case 'note':
            echo '<div class="rule-note"><span class="rule-note-icon" aria-hidden="true">!</span><p>' . esc_html( $block[1] ) . '</p></div>';
            break;
        case 'list':
            $intro   = $block[1];
            $variant = $block[2];
            $items   = $block[3];
            echo '<div class="rule-list rule-list--' . esc_attr( $variant ) . '">';
            if ( $intro ) {
                echo '<p class="rule-list-title">' . esc_html( $intro ) . '</p>';
            }
            echo '<ul>';
            foreach ( $items as $item ) {
                echo '<li>' . esc_html( $item ) . '</li>';
            }
            echo '</ul></div>';
            break;
    }
}
?>

<div class="reading-progress" aria-hidden="true"><span id="reading-progress-bar"></span></div>

<div id="rules-page">

  <!-- ── Page Header ─────────────────────────────────────── -->
  <header class="rules-page-header">
    <div class="rules-header-inner">
      <p class="rules-eyebrow">Server Guide</p>
      <h1>Pulang Lupa Roleplay Rules</h1>
      <p class="rules-subhead">A serious-roleplay handbook for the fictional nation of Pulang Lupa, 1890. Read it, live it, and help write the history of this world.</p>
      <ul class="rules-tags">
        <li>18+ Community</li>
        <li>Serious Roleplay</li>
        <li>RedM &middot; RDR2</li>
        <li>Est. 1890</li>
      </ul>
      <div class="rules-header-actions">
        <button type="button" class="rules-print-btn" id="rules-print">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Print / Save PDF
        </button>
      </div>
    </div>
  </header>

  <div class="rules-layout">

    <!-- ── LEFT SIDEBAR ─────────────────────────────────── -->
    <aside class="rules-sidebar">
      <div class="rules-search-wrap">
        <input type="text" id="rules-search" placeholder="Search the rulebook&hellip;" autocomplete="off" aria-label="Search rules">
        <svg class="rules-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
        </svg>
      </div>

      <nav class="rules-nav" id="rules-nav" aria-label="Rules table of contents">
        <?php foreach ( $plrp_chapters as $ci => $chapter ) : ?>
          <div class="rules-nav-chapter<?php echo $chapter['id'] === 'chapter-1' ? ' open' : ''; ?>" data-chapter="<?php echo esc_attr( $chapter['id'] ); ?>">
            <button type="button" class="rules-nav-chapter-toggle">
              <span class="rules-nav-chapter-label"><?php echo esc_html( $chapter['label'] ); ?></span>
              <span class="rules-nav-chapter-title"><?php echo esc_html( $chapter['title'] ); ?></span>
              <svg class="rules-nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <ul class="rules-nav-sublist">
              <?php if ( isset( $chapter['type'] ) && $chapter['type'] === 'afterword' ) : ?>
                <li><a href="#afterword" class="rules-nav-link">Afterword</a></li>
              <?php else : foreach ( $chapter['rules'] as $rule ) : ?>
                <li><a href="#<?php echo esc_attr( $rule['id'] ); ?>" class="rules-nav-link"><span class="rules-nav-num"><?php echo esc_html( $rule['num'] ); ?></span><?php echo esc_html( $rule['title'] ); ?></a></li>
              <?php endforeach; endif; ?>
            </ul>
          </div>
        <?php endforeach; ?>
        <p class="rules-no-results">No rules match your search.</p>
      </nav>
    </aside>

    <!-- ── MAIN CONTENT ─────────────────────────────────── -->
    <main class="rules-main">

      <!-- Intro / server overview -->
      <section class="rules-intro" id="rules-intro">
        <p class="rule-lead"><?php echo esc_html( $plrp_intro['lead'] ); ?></p>
        <p><?php echo esc_html( $plrp_intro['body'] ); ?></p>
        <div class="rules-discretion">
          <h3><span aria-hidden="true">&#9878;</span> Staff Discretion</h3>
          <?php foreach ( $plrp_intro['discretion'] as $para ) : ?>
            <p><?php echo esc_html( $para ); ?></p>
          <?php endforeach; ?>
        </div>
      </section>

      <?php foreach ( $plrp_chapters as $chapter ) : ?>
        <section class="rule-chapter" id="<?php echo esc_attr( $chapter['id'] ); ?>" data-chapter="<?php echo esc_attr( $chapter['id'] ); ?>">

          <div class="chapter-divider">
            <span class="chapter-numeral"><?php echo esc_html( $chapter['numeral'] ); ?></span>
            <div class="chapter-heading">
              <span class="chapter-label"><?php echo esc_html( $chapter['label'] ); ?></span>
              <h2 class="chapter-title"><?php echo esc_html( $chapter['title'] ); ?></h2>
              <p class="chapter-desc"><?php echo esc_html( $chapter['desc'] ); ?></p>
            </div>
          </div>

          <?php if ( isset( $chapter['type'] ) && $chapter['type'] === 'afterword' ) : ?>

            <article class="rules-afterword" id="afterword">
              <?php foreach ( $chapter['paragraphs'] as $para ) : ?>
                <p><?php echo esc_html( $para ); ?></p>
              <?php endforeach; ?>
              <p class="afterword-welcome"><?php echo esc_html( $chapter['welcome'] ); ?></p>
              <div class="afterword-signature">
                <span class="afterword-signoff"><?php echo esc_html( $chapter['signoff'] ); ?></span>
                <span class="afterword-name"><?php echo esc_html( $chapter['signature'] ); ?></span>
                <span class="afterword-role"><?php echo esc_html( $chapter['sigrole'] ); ?></span>
              </div>
            </article>

          <?php else : ?>

            <?php foreach ( $chapter['rules'] as $rule ) : ?>
              <article class="rule-section" id="<?php echo esc_attr( $rule['id'] ); ?>">
                <div class="rule-section-header">
                  <div class="rule-section-title">
                    <span class="rule-number"><?php echo esc_html( $rule['num'] ); ?></span>
                    <h3><?php echo esc_html( $rule['title'] ); ?></h3>
                  </div>
                  <div class="rule-actions">
                    <button type="button" class="rule-copy-link" data-href="#<?php echo esc_attr( $rule['id'] ); ?>" aria-label="Copy link to this rule">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                      <span>Link</span>
                    </button>
                  </div>
                </div>
                <div class="rule-body">
                  <?php foreach ( $rule['blocks'] as $block ) { plrp_render_block( $block ); } ?>
                </div>
              </article>
            <?php endforeach; ?>

            <?php if ( ! empty( $chapter['violations'] ) ) : ?>
              <div class="chapter-violations">
                <span class="chapter-violations-icon" aria-hidden="true">&#9888;</span>
                <div>
                  <h4>Consequences of Violations</h4>
                  <p><?php echo esc_html( $chapter['violations'] ); ?></p>
                </div>
              </div>
            <?php endif; ?>

          <?php endif; ?>

        </section>
      <?php endforeach; ?>

    </main>

  </div><!-- .rules-layout -->
</div><!-- #rules-page -->

<button type="button" class="rules-back-to-top" id="rules-back-to-top" aria-label="Back to top">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php get_footer(); ?>
