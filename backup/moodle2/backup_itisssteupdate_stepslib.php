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
defined('MOODLE_INTERNAL') || die;
/**
 * Backup_itfileupdate_activity_structure_step Class
 * 
 * @category Class
 * @package  Moodle
 * @author   JFHR <felsul@hotmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://aulavirtual.issste.gob.mx
 */
class Backup_itfileupdate_Activity_Structure_Step extends 
backup_activity_structure_step
{
    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function defineStructure()
    {
        $userinfo = $this->get_setting_value('userinfo');
        $itfileupdate = new backup_nested_element(
            'itfileupdate', array('id'),
            array('name', 'intro', 'introformat', 'grade')
        );
        $itfileupdate->set_source_table(
            'itfileupdate',
            array('id' => backup::VAR_ACTIVITYID)
        );
        $itfileupdate->annotate_files('mod_itfileupdate', 'intro', null);
        return $this->prepare_activity_structure($itfileupdate);
    }
}