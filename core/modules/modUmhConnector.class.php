<?php
/**
 * UMH Connector — Dolibarr Module
 * Verbindet Dolibarr-Kunden mit dem Unified Messenger Hub (WhatsApp & Telegram)
 *
 * @author  Vitalij Haun IT HUB <info@messengerhub.de>
 * @license GPL-3.0-or-later
 */

require_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

class modUmhConnector extends DolibarrModules
{
    public function __construct($db)
    {
        global $langs, $conf;

        $this->db = $db;

        // Module identity
        $this->numero          = 500210;
        $this->rights_class    = 'umhconnector';
        $this->family          = 'crm';
        $this->module_position = '90';
        $this->name            = preg_replace('/^mod/i', '', get_class($this));
        $this->description     = 'WhatsApp & Telegram Nachrichten direkt aus der Kundenkarte via Unified Messenger Hub';
        $this->editor_name     = 'Vitalij Haun IT HUB';
        $this->editor_url      = 'https://messengerhub.de';
        $this->version         = '1.0.0';
        $this->const_name      = 'MAIN_MODULE_'.strtoupper($this->name);
        $this->picto           = 'fa-comments';

        // Module parts activated
        $this->module_parts = array(
            'hooks' => array('thirdpartycard', 'contactcard'),
        );

        // Settings page
        $this->config_page_url = array('setup.php@umh_connector');

        // Minimum Dolibarr version
        $this->need_dolibarr_version = array(14, 0);
        $this->phpmin                = array(7, 4);

        // Constants stored in llx_const
        $this->const = array(
            1 => array('UMH_CONNECTOR_URL', 'chaine', 'https://saas.messengerhub.de', 'URL der UMH SaaS-Anwendung', 0, 'current', 1),
        );

        // Custom tab on thirdparty (customer) card
        // Format: objecttype:+tabname:Label:langfile@module:condition:url
        $this->tabs = array(
            'thirdparty:+umh_messenger:UMH Messenger:umhconnector@umh_connector:1:/custom/umh_connector/tab_thirdparty.php?id=__ID__',
        );

        // Permissions
        $this->rights       = array();
        $this->rights[1][0] = $this->numero + 1;
        $this->rights[1][1] = 'UMH-Tab lesen';
        $this->rights[1][3] = 1;
        $this->rights[1][4] = 'read';

        $this->menu = array();
    }

    /**
     * Called on module activation — creates extrafields on societe + socpeople
     */
    public function init($options = '')
    {
        $result = $this->_init(array(), $options);
        if ($result < 0) {
            return $result;
        }

        include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        $ef = new ExtraFields($this->db);

        // --- Thirdparty (company/customer) ---
        $ef->addExtraField(
            'umh_whatsapp',                  // attribute name
            'WhatsApp-Nummer',               // label
            'varchar',                       // type
            100,                             // position
            '30',                            // size
            'societe',                       // element type
            0,                               // unique
            0,                               // required
            '',                              // default
            '',                              // param
            1,                               // always editable
            '',                              // perms
            '-1',                            // list (hidden in lists, visible in card)
            'Format: +49123456789',          // help
            '',                              // computed
            '',                              // entity
            'umhconnector@umh_connector',    // langfile
            '$conf->umhconnector->enabled'   // enabled condition
        );

        $ef->addExtraField(
            'telegram_chat_id',
            'Telegram Chat-ID',
            'varchar',
            101,
            '60',
            'societe',
            0, 0, '', '', 1, '', '-1',
            'Numerische Chat-ID aus UMH (z.B. 123456789)',
            '', '', 'umhconnector@umh_connector',
            '$conf->umhconnector->enabled'
        );

        // --- Contact (socpeople) ---
        $ef->addExtraField(
            'umh_whatsapp',
            'WhatsApp-Nummer',
            'varchar',
            100, '30', 'socpeople',
            0, 0, '', '', 1, '', '-1',
            'Format: +49123456789',
            '', '', 'umhconnector@umh_connector',
            '$conf->umhconnector->enabled'
        );

        $ef->addExtraField(
            'telegram_chat_id',
            'Telegram Chat-ID',
            'varchar',
            101, '60', 'socpeople',
            0, 0, '', '', 1, '', '-1',
            'Numerische Chat-ID aus UMH (z.B. 123456789)',
            '', '', 'umhconnector@umh_connector',
            '$conf->umhconnector->enabled'
        );

        return 1;
    }

    /**
     * Called on module deactivation — removes extrafields
     */
    public function remove($options = '')
    {
        include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        $ef = new ExtraFields($this->db);

        foreach (array('societe', 'socpeople') as $element) {
            $ef->delete('umh_whatsapp',    $element);
            $ef->delete('telegram_chat_id', $element);
        }

        return $this->_remove(array(), $options);
    }
}
