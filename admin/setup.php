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

$langs->load('umhconnector@umh_connector');
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
echo '<img src="'.DOL_URL_ROOT.'/custom/umh_connector/img/logo_umhconnector.svg" width="36" height="36" alt="UMH Logo">';
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
