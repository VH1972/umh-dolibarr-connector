<?php
/**
 * UMH Connector — Einstellungsseite
 */

$res = 0;
if (!$res && file_exists('../../../main.inc.php'))    { require '../../../main.inc.php'; $res = 1; }
if (!$res && file_exists('../../../../main.inc.php')) { require '../../../../main.inc.php'; $res = 1; }
if (!$res) die('Include of main fails');

global $langs, $user, $conf, $db;

require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

if (!$user->admin) {
    accessforbidden();
}

$langs->load('umhconnector@umhconnector');
$langs->load('admin');

$action = GETPOST('action', 'aZ09');

if ($action === 'update') {
    $url = rtrim(trim(GETPOST('UMH_CONNECTOR_URL', 'alpha')), '/');
    dolibarr_set_const($db, 'UMH_CONNECTOR_URL', $url, 'chaine', 0, '', $conf->entity);
    header('Location: setup.php?saved=1');
    exit;
}

$title = 'UMH Connector — Einstellungen';
llxHeader('', $title, '');

// Logo + Titel
echo '<div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;">';
echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="36" height="36" style="flex-shrink:0"><defs><linearGradient id="us-big" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#3D8BFD"/><stop offset="100%" stop-color="#1E3A8A"/></linearGradient><linearGradient id="us-sm" x1="0" y1="48" x2="48" y2="0" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#7DD3FC"/><stop offset="100%" stop-color="#3B82F6"/></linearGradient></defs><path d="M19 4C28 4 35 10 35 18.5C35 27 28 33 19 33C16.5 33 14.2 32.6 12.2 31.8L5 34L7 27C4.5 24.6 3 21.7 3 18.5C3 10 10 4 19 4Z" fill="url(#us-big)"/><path d="M32.5 18C39.5 18 45 22.5 45 28.5C45 31 44 33.3 42.3 35.2L43.5 41L38 38.5C36.3 39 34.5 39 32.5 39C25.5 39 20 34.5 20 28.5C20 22.5 25.5 18 32.5 18Z" fill="url(#us-sm)"/><circle cx="28" cy="28.5" r="1.6" fill="white"/><circle cx="32.5" cy="28.5" r="1.6" fill="white"/><circle cx="37" cy="28.5" r="1.6" fill="white"/></svg>';
echo '<div>';
echo '<div style="font-size:1.2rem;font-weight:700;color:#1e3a8a;">Unified Messenger Hub</div>';
echo '<div style="font-size:0.85rem;color:#666;">WhatsApp &amp; Telegram Connector v1.0</div>';
echo '</div>';
echo '</div>';

echo load_fiche_titre($title, '', 'fa-comments');

if (GETPOST('saved', 'int')) {
    echo '<div class="ok">Einstellungen gespeichert.</div>';
}

echo '<form method="post" action="setup.php">';
echo '<input type="hidden" name="token" value="'.newToken().'">';
echo '<input type="hidden" name="action" value="update">';

echo '<table class="noborder centpercent">';
echo '<tr class="liste_titre"><td>Einstellung</td><td>Wert</td><td>Hinweis</td></tr>';

$currentUrl = getDolGlobalString('UMH_CONNECTOR_URL', 'https://saas.messengerhub.de');
echo '<tr class="oddeven">';
echo '<td><label for="UMH_CONNECTOR_URL"><b>UMH App-URL</b></label></td>';
echo '<td><input type="url" id="UMH_CONNECTOR_URL" name="UMH_CONNECTOR_URL" class="minwidth350" value="'.dol_escape_htmltag($currentUrl).'"></td>';
echo '<td class="opacitymedium">URL Ihrer UMH-Instanz. Standard: https://saas.messengerhub.de</td>';
echo '</tr>';

echo '</table>';

echo '<div class="center" style="margin-top:1rem">';
echo '<input type="submit" class="button button-save" value="Speichern">';
echo '</div>';
echo '</form>';

llxFooter();
