<?php
/**
 * Fecha:  2019-10-10 - Update: 2019-11-13
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
 * Isssteupdate_Form Class
 * 
 * @category Class
 * @package  Moodle
 * @author   JFHR <felsul@hotmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://aulavirtual.issste.gob.mx
 */
class itfileupdate_Form extends moodleform
{
    /**
     * Function from Form 
     * 
     * @return null
     */
    public function definition() 
    {
        global $CFG;

        $context = context_system::instance();

        $mform = $this->_form; 
        $id = 1;
        $id_course = $this->_customdata['course'];
        $upload = $this->_customdata['upload'];
        $name = $this->_customdata['name'];
        $full_name = $this->_customdata['full_name'];
        $email = $this->_customdata['email'];
        $numero_empleado = $this->_customdata['numero_empleado'];
        $delegacion = $this->_customdata['delegacion'];
        $cargo = $this->_customdata['cargo'];
        $funcion = $this->_customdata['funcion'];
        $maxbytes = $this->_customdata['maxbytes'];
        $course = itfileupdate_Get_Name_course($id_course);
        $type = 'category';

        $mform->addElement(
            'text', 
            'course_info', 
            get_string('course', 'itfileupdate'),
            'size="40px" disabled'
        );
        $mform->setType('course_info', PARAM_TEXT);
        $mform->setDefault('course_info', $course);

        $mform->addElement(
            'text', 
            'full_name_info', 
            get_string('fullname', 'itfileupdate'),
            'size="40px" disabled'
        );
        $mform->setType('full_name_info', PARAM_TEXT);
        $mform->setDefault('full_name_info', $full_name);

        $mform->addElement(
            'text', 
            'email', 
            get_string('email', 'itfileupdate')
        );
        $mform->setType('email', PARAM_TEXT);
        $mform->setDefault('email', $email);

        $mform->addElement(
            'text', 
            'numero_empleado', 
            get_string('numero_empleado', 'itfileupdate')
        );
        $mform->setType('numero_empleado', PARAM_TEXT);
        $mform->setDefault('numero_empleado', $numero_empleado);
        $mform->addRule('numero_empleado', get_string('required'), 'required');

        $mform->addElement(
            'text', 
            'delegacion', 
            get_string('delegacion', 'itfileupdate')
        );
        $mform->setType('delegacion', PARAM_TEXT);
        $mform->setDefault('delegacion', $delegacion);
        $mform->addRule('delegacion', get_string('required'), 'required');

        $mform->addElement(
            'text', 
            'id_sitio', 
            get_string('id_sitio', 'itfileupdate')
        );
        $mform->setType('id_sitio', PARAM_TEXT);

        $mform->addElement(
            'text', 
            'cargo', 
            get_string('cargo', 'itfileupdate'),
            'size="40px"'
        );
        $mform->setType('cargo', PARAM_TEXT);
        $mform->setDefault('cargo', $cargo);
        $mform->addRule('cargo', get_string('required'), 'required');

        $radio_funcion_array=array();
        $radio_funcion_array[] = $mform->createElement(
            'radio', 
            'funcion', 
            '', 
            get_string('enlace_informatico', 'itfileupdate'), 
            0
        );
        $radio_funcion_array[] = $mform->createElement(
            'radio', 
            'funcion', 
            '', 
            get_string('apoyo_informatico', 'itfileupdate'), 
            1
        );

        $radio_funcion_array[] = $mform->createElement(
            'radio', 
            'funcion', 
            '', 
            get_string('funcion_otro', 'itfileupdate'), 
            2
        );

        $mform->addGroup(
            $radio_funcion_array,
            'funcion_group', 
            get_string('funcion', 'itfileupdate'), 
            array(' '),
            false
        );
        $mform->addRule('funcion_group', get_string('required'), 'required');

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $id);

        $mform->addElement('hidden', 'id_course', null);
        $mform->setType('id_course', PARAM_INT);
        $mform->setDefault('id_course', $id_course);

        $mform->addElement('hidden', 'full_name', null);
        $mform->setType('full_name', PARAM_TEXT);
        $mform->setDefault('full_name', $full_name);

        $mform->addElement('hidden', 'upload', null);
        $mform->setType('upload', PARAM_TEXT);
        $mform->setDefault('upload', $upload);

        $mform->addElement('hidden', 'course', null);
        $mform->setType('course', PARAM_INT);
        $mform->setDefault('course', $id_course);

        $mform->addElement('hidden', 'name', null);
        $mform->setType('name', PARAM_TEXT);
        $mform->setDefault('name', $full_name);

        if ($upload == '') {

            $mform->addElement(
                'filemanager',
                'cvfile',
                get_string('cvfile', 'itfileupdate'),
                null,
                array(
                    'subdirs' => 0,
                    'maxbytes' => $maxbytes,
                    'areamaxbytes' => 10485760,
                    'maxfiles' => 1,
                    'accepted_types' => array('.pdf'),
                    'return_types'=> FILE_INTERNAL | FILE_EXTERNAL
                    )
            );

            $mform->addRule('cvfile', get_string('required'), 'required');
        
        }


        $mform->addElement('hidden', 'accion', $type);

        $this->add_action_buttons(
            $cancel = true,
            $submitlabel = get_string('submit_value_add', 'itfileupdate')
        ); 

    }

    /**
     * Function from Validation Form 
     * 
     * @param array $data  comment about this variable
     * @param array $files comment about this variable
     * 
     * @return array
     */
    function validation($data, $files) 
    {
        return array();
    }

    
}
