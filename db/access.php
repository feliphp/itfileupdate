<?php
/**
 * Fecha:  2019-10-10 - Update: 2019-10-10
 * PHP Version 7
 * 
 * @category   Components
 * @package    Moodle
 * @subpackage Mod_itfileupdate
 * @author     JFHR <felsul@hotmail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://aulavirtual.issste.gob.mx
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'mod/itfileupdate:addinstance' => array(
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'student' => CAP_ALLOW,
        )
    ),

    'mod/itfileupdate:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'manager' => CAP_ALLOW,
            'student' => CAP_ALLOW,
        )
    ),

    'mod/itfileupdate:createattachment' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

);

