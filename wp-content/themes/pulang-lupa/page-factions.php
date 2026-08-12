<?php
/**
 * Template Name: Factions Page
 *
 * Data-driven Organizations & Factions handbook for Pulang Lupa Roleplay.
 * Shares the handbook UI (classes / JS) with page-rules.php. The full
 * document lives in the $fx_intro / $fx_chapters arrays below and is rendered
 * into both the sidebar table of contents and the main content.
 *
 * Block types (see plrp_faction_block):
 *   ['p', 'text']  ['lead', 'text']  ['h', 'sub-heading']
 *   ['sub', 'Title', 'desc']   ['note', 'call-out text']
 *   ['list', 'Intro:', 'must|mustnot|neutral', [items…]]
 *   ['chips', [labels…]]       ['stats', [[num, label], …]]
 */
get_header();

$fx_intro = [
    'lead' => "Organizations are the driving force behind many of the stories told in Pulang Lupa RP. Whether you establish a lawful association, a political movement, a ranching family, a business consortium, or an outlaw posse, every organization has the potential to leave a lasting mark on the world through its actions, reputation, and relationships.",
    'body' => [
        "This handbook outlines the policies and expectations for creating, managing, and participating in official player organizations. Its purpose is to ensure that every organization develops through meaningful roleplay, contributes positively to the server's narrative, and provides engaging experiences for both its members and the wider community.",
        "Official organizations are expected to grow naturally through roleplay. Influence, reputation, and recognition should be earned through consistent character development and player interaction — not through mechanical progression or short-term objectives. Whether your group seeks to uphold the law, build a business empire, inspire political change, or live outside the law, your story should enrich the living world of Pulang Lupa.",
    ],
    'discretion' => [
        "While this handbook aims to cover the majority of situations players may encounter, no document can anticipate every circumstance. Pulang Lupa RP staff reserve the right to interpret, enforce, and apply these rules in a manner that best supports fairness, immersion, and the long-term health of the community.",
        "Decisions will always consider the context, intent, severity, and impact of a player's actions. Deliberate attempts to exploit loopholes or technicalities within the rules may still result in administrative action, even if the behavior is not explicitly listed.",
        "If you are ever unsure whether an action is permitted, ask a staff member before proceeding.",
    ],
];

$fx_chapters = [

    /* ── CHAPTER 1 ─────────────────────────────────────────── */
    [
        'id' => 'chapter-1', 'numeral' => 'I', 'label' => 'Chapter 1',
        'title' => 'Organization Registration',
        'desc'  => 'Getting your group officially recognized by Management.',
        'rules' => [
            [
                'id' => 'purpose', 'num' => 1, 'title' => 'Purpose',
                'blocks' => [
                    ['p', "Organizations are an integral part of Pulang Lupa RP and help shape the world through long-term roleplay. Whether lawful or unlawful, organizations should exist to create meaningful stories, encourage player interaction, and contribute to the server's evolving narrative."],
                    ['p', "This handbook applies to all organized player groups, including but not limited to:"],
                    ['chips', [
                        'Outlaw Posses', 'Criminal Syndicates', 'Bandit Groups', 'Smuggling Rings',
                        'Political Organizations', 'Revolutionary Movements', 'Business Associations',
                        'Vigilante Groups', 'Hunting Parties', 'Religious Orders',
                        'Cultural Organizations', 'Indigenous Tribes',
                    ]],
                    ['note', "These examples are not exhaustive. Players are encouraged to create unique organizations that fit the setting and are approved by Management."],
                    ['p', "Players are allowed to join multiple factions, and doing so is an acknowledgement that any in-character consequences are solely their responsibility and admin intervention would always be situational."],
                    ['p', "Players are also reminded not to involve other factions they belong to if an RP was initiated toward another faction they belong to — this is a violation of the Faction Code. For example: a player is a member of Faction A and Faction B. If an RP was initiated toward Faction A, the player is not allowed to involve Faction B in the RP unless a separate In-Character RP was initiated properly. Players with multiple faction affiliations may be subject to review in certain cases; a ticket or review does not necessarily mean a player is in trouble, and may simply be requested for clarification or to better understand the situation."],
                ],
            ],
            [
                'id' => 'official-registration', 'num' => 2, 'title' => 'Official Faction Registration',
                'blocks' => [
                    ['p', "Any group wishing to become an official organization within Pulang Lupa must first submit a Faction Registration Ticket for review by Management."],
                    ['p', "Groups that have not been officially approved may still roleplay together as informal associations. However, they will not receive official recognition, administrative support regarding their group, or access to faction-specific systems and benefits."],
                    ['list', "Management reserves the right to approve, deny, or request changes to any proposed organization to ensure it:", 'neutral', [
                        "Fits the setting and time period.",
                        "Encourages healthy and immersive roleplay.",
                        "Does not conflict with existing organizations or server systems.",
                        "Has a clear purpose, identity, and long-term direction.",
                    ]],
                    ['note', "Official recognition is granted based on the quality of the organization's concept and roleplay, not simply the number of members."],
                ],
            ],
            [
                'id' => 'eligibility', 'num' => 3, 'title' => 'Eligibility Requirements',
                'blocks' => [
                    ['p', "Before creating, joining, or assuming a leadership position within an official faction, a character must:"],
                    ['list', "Requirements:", 'must', [
                        "Have been actively present in Pulang Lupa for a minimum of 14 OOC days.",
                        "Have established themselves through consistent in-character roleplay.",
                        "Meet any additional requirements established by Management.",
                    ]],
                    ['p', "This waiting period ensures players become familiar with the server, develop their characters naturally, and build meaningful in-character relationships before participating in organized group roleplay."],
                    ['note', "Attempts to circumvent this requirement through alternate characters or similar methods are prohibited."],
                ],
            ],
            [
                'id' => 'identity', 'num' => 4, 'title' => 'Organization Identity',
                'blocks' => [
                    ['lead', "Every organization must maintain a clear and consistent identity."],
                    ['list', "When submitting a registration ticket, organizations should define:", 'neutral', [
                        "Their purpose and objectives.",
                        "Their leadership structure.",
                        "Their area of operation.",
                        "Their methods of operation.",
                        "Their long-term goals.",
                        "Any public or hidden affiliations.",
                        "An organization logo (optional).",
                    ]],
                    ['note', "Organizations are expected to remain reasonably consistent with their established identity. Significant changes to an organization's purpose or structure may require review by Management."],
                ],
            ],
            [
                'id' => 'leadership-responsibilities', 'num' => 5, 'title' => 'Leadership Responsibilities',
                'blocks' => [
                    ['p', "Organization leaders are responsible for maintaining a healthy roleplay environment within their group."],
                    ['list', "Leaders are expected to:", 'must', [
                        "Promote quality roleplay and sportsmanship.",
                        "Ensure members understand and follow all server rules.",
                        "Address internal disputes responsibly.",
                        "Cooperate with staff when required.",
                        "Foster long-term stories rather than short-term mechanical gains.",
                    ]],
                    ['note', "Leaders may be held accountable for repeated rule violations committed by members if those violations result from poor leadership, encouragement, or negligence."],
                ],
            ],
        ],
        'violations' => "Failure to comply with the Organization Registration policies may result in administrative action, including denial or removal of official faction status, roster adjustments, warnings, temporary suspensions, permanent bans, disbandment of the organization, or other actions deemed appropriate by Management.",
    ],

    /* ── CHAPTER 2 ─────────────────────────────────────────── */
    [
        'id' => 'chapter-2', 'numeral' => 'II', 'label' => 'Chapter 2',
        'title' => 'Membership & Management',
        'desc'  => 'Recruiting, leading, and stewarding your members.',
        'rules' => [
            [
                'id' => 'recruitment', 'num' => 6, 'title' => 'Recruitment',
                'blocks' => [
                    ['p', "Organizations are expected to recruit members through meaningful in-character roleplay. Recruitment should be gradual, story-driven, and based on trust, shared goals, or established relationships rather than convenience."],
                    ['p', "As stated in Chapter 1, characters must have been actively present in Pulang Lupa for a minimum of 14 OOC days before they are eligible to join an official organization."],
                    ['p', "Organizations should avoid indiscriminate or mass recruitment. Members should be chosen based on the quality of their roleplay and how well they fit the organization's identity, values, and long-term goals."],
                    ['p', "While not required, organizations are encouraged to implement a membership progression system. New members may begin as associates, recruits, prospects, supporters, or any other introductory rank appropriate to the organization's structure before becoming full members. Allowing trust and loyalty to develop naturally often leads to stronger stories and a more cohesive organization."],
                    ['note', "Recruitment conducted solely to increase numbers or gain a mechanical advantage may be subject to review by Management."],
                ],
            ],
            [
                'id' => 'membership-standards', 'num' => 7, 'title' => 'Membership Standards',
                'blocks' => [
                    ['p', "Every member represents their organization through their actions and conduct. Members are expected to contribute positively to both their organization and the wider roleplay community."],
                    ['list', "Members are expected to:", 'must', [
                        "Follow all server rules.",
                        "Maintain roleplay consistent with the organization's established identity.",
                        "Understand the organization's leadership and internal structure.",
                        "Contribute to meaningful roleplay and collaborative storytelling.",
                        "Avoid conduct that brings unnecessary administrative issues upon the organization.",
                    ]],
                    ['p', "Organizations may establish additional internal rules, provided they do not conflict with the Server Handbook or this handbook."],
                    ['note', "Repeated rule violations by members may result in administrative action against the individual and, where appropriate, the organization itself."],
                ],
            ],
            [
                'id' => 'leadership-succession', 'num' => 8, 'title' => 'Leadership Succession',
                'blocks' => [
                    ['p', "Every organization should maintain a clear succession plan to ensure continuity should its leader become inactive, resign, retire, or receive a Character Kill (CK)."],
                    ['p', "Leadership transitions should be resolved through in-character roleplay whenever reasonably possible. Organizations are encouraged to establish their own succession procedures, provided they remain consistent with server rules."],
                    ['p', "Management may intervene when prolonged inactivity or unresolved leadership disputes negatively affect the broader community."],
                    ['note', "Any change in leadership must be reported to Management for documentation purposes."],
                ],
            ],
            [
                'id' => 'disputes-coups', 'num' => 9, 'title' => 'Internal Disputes & Coups',
                'blocks' => [
                    ['p', "Internal conflict can create compelling roleplay when handled responsibly. Power struggles, betrayals, leadership challenges, and coups are permitted, provided they develop through meaningful in-character roleplay and contribute to the organization's story."],
                    ['p', "Organizations should avoid resolving internal disputes through immediate violence or Out-of-Character influence. Leadership changes should feel earned through roleplay rather than being treated as administrative decisions."],
                    ['note', "If an internal conflict significantly impacts the stability or leadership of an official organization, Management may review the situation to ensure fairness and compliance with server policies."],
                ],
            ],
            [
                'id' => 'leaving-disbanding', 'num' => 10, 'title' => 'Leaving or Disbanding an Organization',
                'blocks' => [
                    ['p', "Characters may leave an organization at any time through appropriate in-character roleplay. Likewise, organizations may remove members in accordance with their internal rules and roleplay."],
                    ['p', "Resignations, expulsions, retirements, and departures should be treated as meaningful character moments rather than simple administrative actions whenever possible."],
                    ['p', "If an organization becomes inactive, loses its leadership without succession, or voluntarily disbands, Management may revoke its official status."],
                    ['note', "Former members of a dissolved organization may create or join another organization, provided they meet the required 14-day OOC cooldown. This cooldown applies to all players, regardless of whether they are joining an organization for the first time or transitioning from a previous organization. File a ticket if further assistance is needed."],
                ],
            ],
        ],
        'violations' => "Failure to comply with the Membership & Management policies may result in administrative action, including warnings, removal from an organization, suspension of faction leadership privileges, revocation of official faction status, temporary suspensions, permanent bans, or other measures deemed appropriate by Management.",
    ],

    /* ── CHAPTER 3 ─────────────────────────────────────────── */
    [
        'id' => 'chapter-3', 'numeral' => 'III', 'label' => 'Chapter 3',
        'title' => 'Organization Operations',
        'desc'  => 'Territory, safehouses, business, and reputation.',
        'rules' => [
            [
                'id' => 'territory-influence', 'num' => 11, 'title' => 'Territory & Areas of Influence',
                'blocks' => [
                    ['p', "Organizations may establish an area of influence through consistent roleplay, reputation, and continued presence within a particular region. Influence is earned over time and should develop naturally through player interaction rather than simple declaration."],
                    ['p', "An area's influence does not grant ownership of public roads, towns, or businesses. Other players and organizations remain free to travel through, conduct business within, or roleplay in these locations unless circumstances created through roleplay reasonably prevent them from doing so."],
                    ['note', "Organizations are expected to resolve territorial disputes through meaningful roleplay rather than assuming exclusive control over any location. Management reserves the right to review or intervene in territorial disputes when necessary to maintain fairness and healthy roleplay."],
                ],
            ],
            [
                'id' => 'safehouses', 'num' => 12, 'title' => 'Safehouses & Hideouts',
                'blocks' => [
                    ['p', "Organizations may establish safehouses, hideouts, camps, or meeting locations that support their roleplay."],
                    ['list', "These locations should:", 'must', [
                        "Be appropriate for the organization's identity.",
                        "Be actively used through roleplay.",
                        "Not unreasonably prevent access to public areas.",
                        "Respect all server property and housing rules.",
                    ]],
                    ['note', "A safehouse should enhance storytelling, not function as an untouchable or permanently protected location. Rival organizations, law enforcement, and other players may discover or interact with these locations through legitimate in-character roleplay."],
                ],
            ],
            [
                'id' => 'businesses-fronts', 'num' => 13, 'title' => 'Businesses & Front Operations',
                'blocks' => [
                    ['p', "Organizations may own or operate legitimate businesses, subject to the Server Handbook and any applicable business policies."],
                    ['p', "Businesses should exist primarily to support roleplay and should not serve solely as a source of income or organizational advantages."],
                    ['p', "Organizations using legitimate businesses to conceal unlawful activities are expected to develop those storylines gradually through roleplay. Front businesses should remain believable within the setting and should never be immune from investigation or consequences."],
                    ['note', "Organizations may not monopolize industries or attempt to prevent reasonable competition without meaningful in-character justification."],
                ],
            ],
            [
                'id' => 'alliances-diplomacy', 'num' => 14, 'title' => 'Alliances & Diplomacy',
                'blocks' => [
                    ['p', "Organizations are free to establish alliances, partnerships, trade agreements, or other diplomatic relationships through roleplay. Likewise, rivalries and hostilities should develop naturally over time rather than appearing without meaningful interaction."],
                    ['p', "Organizations are encouraged to resolve disagreements through negotiation, diplomacy, or other forms of roleplay whenever appropriate before escalating into open conflict. All agreements remain entirely in-character and may succeed, fail, or change as the story develops."],
                ],
            ],
            [
                'id' => 'public-reputation', 'num' => 15, 'title' => 'Public Reputation',
                'blocks' => [
                    ['lead', "Every organization develops a reputation based on its actions."],
                    ['p', "Reputation should be earned through consistent roleplay and meaningful interactions with the community. Organizations should not expect immediate recognition, influence, or fear simply because they have received official faction status."],
                    ['note', "Organizations should avoid portraying themselves as universally feared, respected, or influential without first establishing that reputation through roleplay. An organization's legacy is built over time through its successes, failures, relationships, and contributions to the world of Pulang Lupa."],
                ],
            ],
        ],
        'violations' => "Failure to comply with the Organization Operations policies may result in administrative action, including warnings, restrictions on organization activities, loss of organization privileges, revocation of official faction status, temporary suspensions, permanent bans, or other measures deemed appropriate by Management.",
    ],

    /* ── CHAPTER 4 ─────────────────────────────────────────── */
    [
        'id' => 'chapter-4', 'numeral' => 'IV', 'label' => 'Chapter 4',
        'title' => 'Conflict Between Organizations',
        'desc'  => 'Rivalries, wars, diplomacy, and dissolution.',
        'rules' => [
            [
                'id' => 'rivalries', 'num' => 16, 'title' => 'Rivalries',
                'blocks' => [
                    ['p', "Rivalries between organizations are encouraged when they develop naturally through meaningful roleplay and contribute to long-term storytelling."],
                    ['p', "Hostility should arise from repeated interactions, competing interests, political differences, territorial disputes, or other in-character events. Organizations are expected to build tension gradually rather than rushing into violence."],
                    ['note', "Conflict should always prioritize immersive storytelling over mechanical victory or personal gain."],
                ],
            ],
            [
                'id' => 'organization-wars', 'num' => 17, 'title' => 'Organization Wars',
                'blocks' => [
                    ['p', "An organization war represents a significant escalation of conflict and should only occur after substantial in-character development."],
                    ['p', "Organizations are expected to establish clear roleplay through ongoing disputes, retaliation, negotiations, or other meaningful interactions before engaging in large-scale hostilities."],
                    ['note', "Management must be notified through a ticket before an official organization war — faction war, bandido war, gang war, or posse war — begins. This allows staff to monitor the conflict, provide assistance when necessary, and ensure the roleplay remains fair, balanced, and enjoyable for all parties involved."],
                    ['p', "Organization wars should emphasize storytelling rather than constant combat. Diplomacy, failed operations, espionage, temporary victories, setbacks, and changing alliances are all valuable parts of a compelling conflict."],
                ],
            ],
            [
                'id' => 'third-party-intervention', 'num' => 18, 'title' => 'Third-Party Intervention',
                'blocks' => [
                    ['p', "Organizations that are not directly involved in a conflict should not intervene unless they have a legitimate in-character reason to do so."],
                    ['list', "Intervention should be supported by established roleplay, such as:", 'neutral', [
                        "Existing alliances or treaties.",
                        "Long-standing rivalries or shared enemies.",
                        "Political, financial, or personal interests developed through roleplay.",
                    ]],
                    ['note', "Organizations may not join conflicts solely because of Out-of-Character friendships, shared Discord communities, or a desire to gain a numerical advantage. Management may restrict or deny third-party involvement if it unfairly disrupts the balance or progression of an ongoing conflict."],
                ],
            ],
            [
                'id' => 'ceasefires-peace', 'num' => 19, 'title' => 'Ceasefires & Peace Agreements',
                'blocks' => [
                    ['p', "Organizations are free to negotiate ceasefires, peace agreements, alliances, or other diplomatic arrangements through roleplay."],
                    ['p', "These agreements are entirely in-character and should be respected until they are legitimately broken through subsequent roleplay."],
                    ['note', "Organizations are encouraged to pursue diplomacy whenever it serves the story. Not every conflict needs to end in violence, and peaceful resolutions often create new opportunities for future roleplay."],
                ],
            ],
            [
                'id' => 'ck-clauses', 'num' => 20, 'title' => 'Character Kill (CK) Clauses',
                'blocks' => [
                    ['p', "Organizations may establish Character Kill (CK) agreements as part of their internal policies, provided all members have given informed Out-of-Character consent in accordance with the Server Handbook."],
                    ['p', "Organization leaders are responsible for ensuring prospective members fully understand any CK agreements before joining."],
                    ['note', "No player may be pressured, coerced, or misled into accepting a CK clause. All Character Kill scenes remain subject to the Server Handbook and may be reviewed or invalidated by staff if any applicable rules are violated."],
                ],
            ],
            [
                'id' => 'dissolution', 'num' => 21, 'title' => 'Organization Dissolution',
                'blocks' => [
                    ['p', "An organization may be dissolved voluntarily through roleplay or administratively by Management."],
                    ['list', "Official recognition may be revoked if an organization:", 'mustnot', [
                        "Becomes inactive for an extended period.",
                        "Repeatedly violates server rules.",
                        "Fails to maintain effective leadership.",
                        "No longer contributes positively to the server's roleplay environment.",
                        "Requests voluntary dissolution.",
                    ]],
                    ['note', "When an organization is dissolved, its official status, privileges, and recognition are revoked. Members may continue their characters independently or establish or join another organization in accordance with existing server policies."],
                ],
            ],
        ],
        'violations' => "Failure to comply with the Conflict Between Organizations policies may result in administrative action, including warnings, suspension of organization privileges, removal of leadership, revocation of official faction status, temporary suspensions, permanent bans, or other measures deemed appropriate by Management.",
    ],

    /* ── CHAPTER 5 ─────────────────────────────────────────── */
    [
        'id' => 'chapter-5', 'numeral' => 'V', 'label' => 'Chapter 5',
        'title' => 'Administrative Policies & Technical Restrictions',
        'desc'  => 'Management authority and the hard limits.',
        'rules' => [
            [
                'id' => 'management-authority', 'num' => 22, 'title' => 'Management Authority',
                'blocks' => [
                    ['p', "Management is responsible for overseeing all official organizations within Pulang Lupa RP. Their role is to ensure organizations remain active, fair, immersive, and beneficial to the server's roleplay environment."],
                    ['list', "Management reserves the right to:", 'neutral', [
                        "Approve or deny organization applications.",
                        "Request reasonable adjustments to an organization's concept or structure.",
                        "Monitor organization wars and major roleplay events.",
                        "Review organization leadership when necessary.",
                        "Suspend or revoke official organization status.",
                        "Resolve disputes relating to official organizations.",
                    ]],
                    ['note', "Administrative decisions will always prioritize fairness, server health, and long-term storytelling."],
                ],
            ],
            [
                'id' => 'technical-restrictions', 'num' => 23, 'title' => 'Technical Restrictions',
                'blocks' => [
                    ['p', "To maintain server balance and ensure enjoyable roleplay for everyone, the following technical restrictions apply to all official organizations."],
                    ['stats', [
                        ['12', 'War participants per side'],
                        ['40', 'Maximum members per org'],
                        ['14', 'OOC days before eligibility'],
                    ]],
                    ['h', 'Organization Wars'],
                    ['note', "Official Organization Wars are limited to 12 participants per side. If there is an alliance, it is your responsibility to ensure that your side only has 12 participants. This rule is absolute."],
                    ['p', "Only the listed participants may actively engage in the conflict."],
                    ['list', "The following are not permitted:", 'mustnot', [
                        "Substituting or rotating participants during an active conflict to bypass participation limits.",
                        "Spectators or uninvolved organizations interfering or meddling around the area of the war.",
                    ]],
                    ['note', "Getting killed within the vicinity of an ongoing war will be counted as collateral damage and will not be counted by Management as RDM unless deemed necessary."],
                    ['h', 'Organization Membership'],
                    ['note', "Management allows a maximum of 40 members within official organizations. If you are an associate or confidante — someone who does not participate in wars but actively shares information with faction members — you will be counted as a member. Share information at your own risk."],
                    ['p', "Organizations are expected to maintain an active roster. Inactive members may be removed by the organization itself."],
                    ['h', 'Safehouses & Properties'],
                    ['p', "Organizations may only utilize properties, camps, or hideouts that comply with the Server Handbook and applicable housing or property policies."],
                    ['h', 'Businesses'],
                    ['p', "Organizations may only own or operate businesses in accordance with the Business Handbook and any limits established by Management."],
                    ['note', "Technical restrictions may be updated as the server evolves. Organizations are expected to remain informed of any changes."],
                ],
            ],
            [
                'id' => 'activity-requirements', 'num' => 24, 'title' => 'Activity Requirements',
                'blocks' => [
                    ['p', "Official organizations are expected to remain active and contribute to the server's roleplay environment."],
                    ['list', "Organizations should:", 'must', [
                        "Maintain active leadership.",
                        "Continue developing meaningful storylines.",
                        "Participate in roleplay consistently.",
                        "Avoid remaining officially registered while inactive for prolonged periods.",
                    ]],
                    ['note', "Management may conduct periodic activity reviews to ensure organizations continue meeting these expectations."],
                ],
            ],
            [
                'id' => 'leadership-inactivity', 'num' => 25, 'title' => 'Leadership Inactivity',
                'blocks' => [
                    ['p', "If an organization's leadership becomes inactive for an extended period without prior notice, Management may request that the organization appoint a successor through roleplay."],
                    ['p', "If no suitable leadership can be established, Management may temporarily suspend or revoke the organization's official status until leadership is restored."],
                    ['note', "Organizations are encouraged to establish succession plans to minimize disruption."],
                ],
            ],
            [
                'id' => 'organization-reviews', 'num' => 26, 'title' => 'Organization Reviews & Administrative Audits',
                'blocks' => [
                    ['p', "Management may review any official organization at any time."],
                    ['list', "Reviews may include:", 'neutral', [
                        "Organization activity.",
                        "Leadership effectiveness.",
                        "Membership conduct.",
                        "Compliance with server policies.",
                        "Overall contribution to the server's roleplay environment.",
                    ]],
                    ['note', "Organizations found to be inactive, abusive, or operating contrary to server expectations may be placed under administrative review and required to make improvements."],
                ],
            ],
            [
                'id' => 'rule-violations', 'num' => 27, 'title' => 'Rule Violations & Administrative Action',
                'blocks' => [
                    ['p', "Failure to comply with this handbook or the Server Handbook may result in administrative action, including:"],
                    ['list', "Possible administrative actions:", 'mustnot', [
                        "Verbal or written warnings.",
                        "Organization probation.",
                        "Restrictions on organization activities.",
                        "Leadership removal.",
                        "Roster adjustments.",
                        "Revocation of official organization status.",
                        "Temporary or permanent suspensions.",
                        "Any additional action deemed appropriate by Management.",
                    ]],
                    ['note', "Administrative decisions will consider the severity of the violation, prior history, and the overall impact on the roleplay environment."],
                ],
            ],
        ],
    ],

    /* ── AFTERWORD ─────────────────────────────────────────── */
    [
        'id' => 'chapter-6', 'numeral' => 'VI', 'label' => 'Closing',
        'title' => 'Afterword',
        'desc'  => 'A closing note from the owner of Pulang Lupa RP.',
        'type'  => 'afterword',
        'paragraphs' => [
            "Organizations are among the most influential forces within Pulang Lupa RP. Every alliance, rivalry, business venture, political movement, and outlaw posse contributes to the living history of the server.",
            "Leadership comes with responsibility. Whether your organization seeks fortune, justice, influence, or infamy, remember that the greatest legacy is not measured by victories or wealth, but by the stories your organization leaves behind.",
            "Build patiently, lead responsibly, and always prioritize meaningful roleplay over short-term success.",
        ],
        'welcome'   => "Welcome to Pulang Lupa.",
        'signoff'   => "Yours truly,",
        'signature' => "Caleb, a.k.a. Agapito Dumurangapoy",
        'sigrole'   => "Owner, Pulang Lupa RP",
    ],
];

/* -------------------------------------------------------------------------
   BLOCK RENDERER (factions — adds chips / stats to the shared block set)
------------------------------------------------------------------------- */
function plrp_faction_block( $block ) {
    switch ( $block[0] ) {
        case 'lead':
            echo '<p class="rule-lead">' . esc_html( $block[1] ) . '</p>';
            break;
        case 'p':
            echo '<p>' . esc_html( $block[1] ) . '</p>';
            break;
        case 'h':
            echo '<h4 class="rule-subhead">' . esc_html( $block[1] ) . '</h4>';
            break;
        case 'note':
            echo '<div class="rule-note"><span class="rule-note-icon" aria-hidden="true">!</span><p>' . esc_html( $block[1] ) . '</p></div>';
            break;
        case 'list':
            echo '<div class="rule-list rule-list--' . esc_attr( $block[2] ) . '">';
            if ( $block[1] ) {
                echo '<p class="rule-list-title">' . esc_html( $block[1] ) . '</p>';
            }
            echo '<ul>';
            foreach ( $block[3] as $item ) {
                echo '<li>' . esc_html( $item ) . '</li>';
            }
            echo '</ul></div>';
            break;
        case 'chips':
            echo '<ul class="faction-types">';
            foreach ( $block[1] as $chip ) {
                echo '<li>' . esc_html( $chip ) . '</li>';
            }
            echo '</ul>';
            break;
        case 'stats':
            echo '<div class="faction-stats">';
            foreach ( $block[1] as $stat ) {
                echo '<div class="faction-stat"><span class="faction-stat-num">' . esc_html( $stat[0] ) . '</span><span class="faction-stat-label">' . esc_html( $stat[1] ) . '</span></div>';
            }
            echo '</div>';
            break;
    }
}

// Prefer the site's configured Discord; fall back to the invite in the handbook
// document so the "Register a Faction" ticket link is never a dead end.
$fx_discord = get_theme_mod( 'plrp_discord_url', '' );
if ( empty( $fx_discord ) || $fx_discord === '#' ) {
    $fx_discord = 'https://discord.gg/pulangluparp';
}
$fx_discord = esc_url( $fx_discord );
?>

<div class="reading-progress" aria-hidden="true"><span id="reading-progress-bar"></span></div>

<div class="factions-page">

  <!-- ── Page Header ─────────────────────────────────────── -->
  <header class="rules-page-header">
    <div class="rules-header-inner">
      <p class="rules-eyebrow">Server Guide</p>
      <h1>Organizations &amp; Factions</h1>
      <p class="rules-subhead">How player organizations are founded, led, and fought over in the world of Pulang Lupa, 1890. Build patiently — every posse, syndicate, and movement writes its own chapter of history.</p>
      <ul class="rules-tags">
        <li>Player Factions</li>
        <li>Registration</li>
        <li>War &amp; Diplomacy</li>
        <li>18+ Community</li>
      </ul>
      <div class="rules-header-actions">
        <a class="rules-cta-btn" href="<?php echo $fx_discord; ?>" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
          Register a Faction
        </a>
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
        <input type="text" id="rules-search" placeholder="Search the handbook&hellip;" autocomplete="off" aria-label="Search factions handbook">
        <svg class="rules-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
        </svg>
      </div>

      <nav class="rules-nav" id="rules-nav" aria-label="Factions table of contents">
        <?php foreach ( $fx_chapters as $ci => $chapter ) : ?>
          <div class="rules-nav-chapter<?php echo $ci === 0 ? ' open' : ''; ?>" data-chapter="<?php echo esc_attr( $chapter['id'] ); ?>">
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
        <p class="rules-no-results">No sections match your search.</p>
      </nav>
    </aside>

    <!-- ── MAIN CONTENT ─────────────────────────────────── -->
    <main class="rules-main">

      <!-- Intro / overview -->
      <section class="rules-intro" id="factions-intro">
        <p class="rule-lead"><?php echo esc_html( $fx_intro['lead'] ); ?></p>
        <?php foreach ( $fx_intro['body'] as $para ) : ?>
          <p><?php echo esc_html( $para ); ?></p>
        <?php endforeach; ?>
        <div class="rules-discretion">
          <h3><span aria-hidden="true">&#9878;</span> Staff Discretion</h3>
          <?php foreach ( $fx_intro['discretion'] as $para ) : ?>
            <p><?php echo esc_html( $para ); ?></p>
          <?php endforeach; ?>
        </div>
      </section>

      <?php foreach ( $fx_chapters as $chapter ) : ?>
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
                    <button type="button" class="rule-copy-link" data-href="#<?php echo esc_attr( $rule['id'] ); ?>" aria-label="Copy link to this section">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                      <span>Link</span>
                    </button>
                  </div>
                </div>
                <div class="rule-body">
                  <?php foreach ( $rule['blocks'] as $block ) { plrp_faction_block( $block ); } ?>
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
</div><!-- .factions-page -->

<button type="button" class="rules-back-to-top" id="rules-back-to-top" aria-label="Back to top">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php get_footer(); ?>
