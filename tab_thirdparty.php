<?php
/**
 * UMH Connector — Tab "UMH Messenger" in der Kundenkarte
 */

$res = 0;
if (!$res && file_exists('../../main.inc.php'))   { require '../../main.inc.php'; $res = 1; }
if (!$res && file_exists('../../../main.inc.php')) { require '../../../main.inc.php'; $res = 1; }
if (!$res) die('Include of main fails');

require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';

global $langs, $user, $conf, $db;

$langs->load('umhconnector@umhconnector');
$langs->load('companies');

$id     = GETPOST('id',     'int');
$action = GETPOST('action', 'aZ09');

if (!$user->rights->umhconnector->read) {
    accessforbidden();
}

// Load customer
$object = new Societe($db);
if ($object->fetch($id) <= 0) {
    dol_print_error($db);
    exit;
}
$object->fetch_optionals();

// --- Save extrafields ---
if ($action === 'update_umh' && $user->rights->umhconnector->read) {
    $whatsapp = trim(GETPOST('umh_whatsapp',  'alpha'));
    $telegram = trim(GETPOST('umh_telegram',  'alpha')); // Formularfeld heißt umh_telegram, speichert als telegram_chat_id

    $object->array_options['options_umh_whatsapp']     = $whatsapp;
    $object->array_options['options_telegram_chat_id'] = $telegram;
    $object->insertExtraFields();

    header('Location: tab_thirdparty.php?id='.$id.'&saved=1');
    exit;
}

// --- Page ---
$title = $object->name.' — UMH Messenger';
llxHeader('', $title, '');

$head = societe_prepare_head($object);
print dol_get_fiche_head($head, 'umh_messenger', $langs->trans('ThirdParty'), -1, 'company');

$linkback = '<a href="'.DOL_URL_ROOT.'/societe/list.php?restore_lastsearch_values=1">'.
    $langs->trans('BackToList').'</a>';

dol_banner_tab($object, 'id', $linkback, 1, 'rowid', 'nom');

print '<div class="fichecenter">';

if (GETPOST('saved', 'int')) {
    print '<div class="ok">'.$langs->trans('UMHSaved').'</div>';
}

// Current values
$whatsapp = isset($object->array_options['options_umh_whatsapp'])
    ? $object->array_options['options_umh_whatsapp'] : '';
$telegram  = isset($object->array_options['options_telegram_chat_id'])
    ? $object->array_options['options_telegram_chat_id']  : '';

$umhUrl = rtrim(getDolGlobalString('UMH_CONNECTOR_URL', 'https://saas.messengerhub.de'), '/');

// --- Info-Bereich + Buttons ---
print '<div style="margin: 1.5rem 0; display:flex; gap: 1rem; flex-wrap: wrap;">';

// WhatsApp-Karte
$openUrlWa = $whatsapp ? $umhUrl.'/inbox?phone='.urlencode($whatsapp) : null;
print '<div style="flex:1; min-width:200px; padding:1rem 1.25rem; background:#f8f9fa; border-radius:8px; border:1px solid #dee2e6;">';
print '<div style="font-size:0.78rem; color:#666; margin-bottom:0.35rem;"><i class="fab fa-whatsapp" style="color:#25D366;"></i> WhatsApp</div>';
if ($whatsapp) {
    print '<div style="font-weight:700; font-size:1rem; margin-bottom:0.6rem;">'.dol_escape_htmltag($whatsapp).'</div>';
    print '<a href="'.dol_escape_htmltag($openUrlWa).'" target="umh_inbox" class="button" style="background:#25D366;border-color:#25D366;color:#fff;display:block;text-align:center;">';
    print '<i class="fab fa-whatsapp"></i> '.$langs->trans('UMHOpenConversation');
    print '</a>';
} else {
    print '<div style="color:#aaa; font-size:0.9rem; margin-bottom:0.6rem;">'.$langs->trans('UMHNotSet').'</div>';
    print '<span class="button" style="opacity:0.35;display:block;text-align:center;cursor:default;pointer-events:none;">'.$langs->trans('UMHOpenConversation').'</span>';
}
print '</div>';

// Telegram-Karte
$openUrlTg = $telegram ? $umhUrl.'/inbox?telegram='.urlencode($telegram) : null;
print '<div style="flex:1; min-width:200px; padding:1rem 1.25rem; background:#f8f9fa; border-radius:8px; border:1px solid #dee2e6;">';
print '<div style="font-size:0.78rem; color:#666; margin-bottom:0.35rem;"><i class="fas fa-paper-plane" style="color:#229ED9;"></i> Telegram</div>';
if ($telegram) {
    print '<div style="font-weight:700; font-size:1rem; margin-bottom:0.6rem;">'.dol_escape_htmltag($telegram).'</div>';
    print '<a href="'.dol_escape_htmltag($openUrlTg).'" target="umh_inbox" class="button" style="background:#229ED9;border-color:#229ED9;color:#fff;display:block;text-align:center;">';
    print '<i class="fas fa-paper-plane"></i> '.$langs->trans('UMHOpenConversation');
    print '</a>';
} else {
    print '<div style="color:#aaa; font-size:0.9rem; margin-bottom:0.6rem;">'.$langs->trans('UMHNotSet').'</div>';
    print '<span class="button" style="opacity:0.35;display:block;text-align:center;cursor:default;pointer-events:none;">'.$langs->trans('UMHOpenConversation').'</span>';
}
print '</div>';

print '</div>'; // end cards flex-row

// Hinweis zu den Feldern
print '<div style="margin: 0 0 1.5rem 0; padding: 0.75rem 1rem; background: #fff8e1; border-left: 3px solid #ffc107; border-radius: 4px; font-size: 0.85rem; color: #555;">';
print '<b>Hinweis:</b> ';
print 'WhatsApp-Lookup in UMH läuft über das Standard-Telefon-Feld von Dolibarr (<i>Telefon</i> in der Firmenkarte). ';
print 'Das WhatsApp-Feld hier ist nur für den direkten Link-Button. ';
print 'Die Telegram Chat-ID wird von UMH automatisch für die Kundenerkennung verwendet.';
print '</div>';

// --- Edit form ---
print '<form method="post" action="tab_thirdparty.php?id='.$id.'">';
print '<input type="hidden" name="token"  value="'.newToken().'">';
print '<input type="hidden" name="action" value="update_umh">';

print '<fieldset style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1rem 1.5rem; margin-bottom: 1rem;">';
print '<legend style="font-weight: 600; color: #2c3e50; padding: 0 0.5rem;">'.$langs->trans('UMHEditContacts').'</legend>';

print '<div style="display:flex; gap:1rem; flex-wrap:wrap;">';

// WhatsApp Eingabe
print '<div style="flex:1; min-width:200px;">';
print '<label style="display:block; font-size:0.82rem; color:#666; margin-bottom:0.3rem;"><i class="fab fa-whatsapp" style="color:#25D366;"></i> WhatsApp-Nummer</label>';
print '<input type="text" name="umh_whatsapp" value="'.dol_escape_htmltag($whatsapp).'" class="minwidth200" placeholder="+49 123 456789" style="width:100%;">';
print '</div>';

// Telegram Eingabe
print '<div style="flex:1; min-width:200px;">';
print '<label style="display:block; font-size:0.82rem; color:#666; margin-bottom:0.3rem;"><i class="fas fa-paper-plane" style="color:#229ED9;"></i> Telegram Chat-ID</label>';
print '<input type="text" name="umh_telegram" value="'.dol_escape_htmltag($telegram).'" class="minwidth200" placeholder="z.B. 123456789 (nur Zahl aus UMH)" style="width:100%;">';
print '</div>';

print '</div>'; // end flex

print '<div style="margin-top:1rem;">';
print '<input type="submit" class="button button-save" value="'.$langs->trans('Save').'">';
print '</div>';

print '</fieldset>';
print '</form>';

print '</div>';

print dol_get_fiche_end();
llxFooter();
