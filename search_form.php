<?php
/**
 * Fecha:  2019-11-14 - Update: 2019-11-14
 * PHP Version 7
 * 
 * @category   Components
 * @package    Moodle
 * @subpackage Mod_itfileupdate
 * @author     JFHR <felsul@hotmail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://aulavirtual.issste.gob.mx
 */
require_once dirname(dirname(dirname(__FILE__))).'/config.php';
require_once dirname(__FILE__).'/lib.php';
require_once $CFG->libdir.'/adminlib.php';
require_once $CFG->libdir.'/formslib.php';
/**
 * Category_Form Class
 * 
 * @category Class
 * @package  Moodle
 * @author   JFHR <felsul@hotmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://aulavirtual.issste.gob.mx
 */
class Courses_Form extends moodleform
{
    /**
     * Function from Form 
     * 
     * @return null
     */
    public function definition() 
    {
        global $CFG;
 
        $mform = $this->_form;    
        //filtro curso
        $courses_array = array();
        $courses_array =itfileupdate_Get_courses();
        
        $div_fitem ='<div id="fitem_id_type" class="fitem fitem_fselect">'.
        '<div class="fitemtitle"><label for="course_id" >'
        .get_string('filter_course', 'itfileupdate').'</label></div>';
        $cou_id = $this->_customdata['course_id'];
        $cou_name = itfileupdate_Get_Course_name($cou_id);

        if ($cou_id == 0) {
            $div_felement ='<div class="felement fselect">'.
            '<select name="course_id"'.
            ' id="course_id" ><option value="0">'
            .get_string('option_default_course', 'itfileupdate').'</option>';
        } else {
            $div_felement ='<div class="felement fselect">'.
            '<select name="course_id"'.
            ' id="coursey_id" ><option value="'.$cou_id.'">'
            .$cou_name.'</option><option value="0">'
            .get_string('option_default_course', 'itfileupdate').'</option>';
        }
        $mform->addElement('html', ''.$div_fitem);
        $mform->addElement('html', ''.$div_felement);

        foreach ($courses_array as $cou) {
            $c_name = itfileupdate_Get_Course_name($cou->course);
            $html_cou = '<option value="'.$cou->course.'">'.$c_name.'</option>';
            $mform->addElement('html', $html_cou);
        }
        $mform->addElement('html', '</select></div></div>'."\n");

        $mform->addElement('hidden', 'accion', 'courses');

        $this->add_action_buttons(
            $cancel = false,
            $submitlabel = get_string('submit_value', 'itfileupdate')
        );

    }
}