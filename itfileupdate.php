<?php
  /**
   * Fecha:  2019-10-17 - Update: 2019-11-14
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
require_once dirname(__FILE__).'/itfileupdate_form.php';
require_once $CFG->libdir.'/adminlib.php';

$contextid = optional_param('contextid', 0, PARAM_INT);
$openlink = optional_param('openlink', 0, PARAM_INT); 
$user = optional_param('user', 0, PARAM_INT); 
$category_id = optional_param('category_id', 0, PARAM_INT); 
$id_course = optional_param('id_course', null, PARAM_INT); 
$upload = optional_param('upload', '', PARAM_TEXT); 
$name = optional_param('name', '', PARAM_TEXT); 
$full_name = optional_param('full_name', '', PARAM_TEXT); 
$course_id = optional_param('course_id', 0, PARAM_INT); 
$accion = optional_param('accion', '', PARAM_TEXT); 
$reporte = optional_param('report_visibility', 0, PARAM_INT); 

$email = optional_param('email', '', PARAM_TEXT); 
$numero_empleado = optional_param('numero_empleado', '', PARAM_TEXT);
$delegacion = optional_param('delegacion', '', PARAM_TEXT);
$id = optional_param('id', null, PARAM_INT);
$id_sitio = optional_param('id_sitio', '', PARAM_TEXT); 
$cargo = optional_param('cargo', '', PARAM_TEXT); 
$funcion = optional_param('funcion', '', PARAM_TEXT); 
$course = optional_param('course', null, PARAM_INT); 
$page = optional_param('page', 0, PARAM_INT);
$err_valida = optional_param('err_valida', 0, PARAM_INT); 
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
$strname = get_string('itfileupdate', 'itfileupdate');
$site = get_site();
$params = array('page' => $page); 
$baseurl = new moodle_url('/mod/itfileupdate/itfileupdate.php', $params);

if ($contextid) {
    $context = context_system::instance();
} else {
    $context = context_system::instance();
}
global $SESSION;

$PAGE->set_url($CFG->wwwroot.'/mod/itfileupdate/itfileupdate.php');
$cancelurl = new moodle_url('/mod/itfileupdate/index.php', null);

if (($err_valida == '1') || ($err_valida == '2')) {

} else {
    if (is_null($id)) {
        $full_name = itfileupdate_Get_Full_name($USER->id); 
        $email = itfileupdate_Get_email($USER->id); 
    }
}

if (isloggedin()) {

    $PAGE->navbar->add(
        get_string('newupdate', 'itfileupdate'),
        $baseurl
    );
    $PAGE->navbar->add($strname);
    $PAGE->set_context($context);
    $PAGE->set_pagelayout('admin');
    $PAGE->set_heading($site->fullname);
    echo $PAGE->set_title(get_string('newupdate', 'itfileupdate'));  
    echo $OUTPUT->header();  
    echo $OUTPUT->heading($strname);  


    $validate_index = itfileupdate_exist(
        $email, 
        $id_course
    );

    if ($id_course == '') {
        echo "<font color='red'>".get_string(
            'error_course_empty',
            'itfileupdate'
        )."</font>";
    } else {
        $customdata = array(
            'course' => $id_course,
            'upload' => $upload,
            'name' => $name,
            'full_name' => $full_name,
            'email' => $email,
            'numero_empleado' => $numero_empleado,
            'delegacion' => $delegacion,
            'id_sitio' => $id_sitio,
            'cargo' => $cargo,
            'funcion' => $funcion,
            'err_valida_form' => $err_valida,
            'maxbytes' => $maxbytes
        );

        $mformst = new itfileupdate_Form(
            null,
            $customdata
        );

        if ($mformst->is_cancelled()) {
            redirect($cancelurl);
        } else if ($data = $mformst->get_data()) {
 
            
            if (empty($entry->id)) {
                $entry = new stdClass;
                $entry->id = $USER->id;
            }
            if ($upload == '') {
                $draftitemid = file_get_submitted_draft_itemid('cvfile');
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    'mod_itfileupdate',
                    'cvfile',
                    $entry->id,
                    array(
                        'subdirs' => 0,
                        'maxbytes' => $maxbytes,
                        'maxfiles' => 1
                        )
                );
        
                $entry->attachments = $draftitemid;
                $mformst->set_data($entry);

                $file = file_save_draft_area_files(
                    $data->cvfile,
                    $context->id,
                    'mod_itfileupdate',
                    'cvfile',
                    $entry->id,
                    array(
                        'subdirs' => 0,
                        'maxbytes' => $maxbytes,
                        'maxfiles' => 50
                    )
                );

                $fs = get_file_storage();
                $file_o = $fs->get_area_files(
                    $context->id,
                    'mod_itfileupdate',
                    'cvfile',
                    $entry->id
                );
             
                foreach ($file_o as $f) {
                    $filename = $f->get_filename();
                }

                $data->url_file = $filename;
                $data->itemid = $USER->id;

            } else {
                $data->url_file = '';
                $data->itemid = '';
            }
            
            $data->type = $accion;
            
            $email = strtolower($data->email);
            $data->datecreated = date("Y-m-d"); 
            $data->datemodified = date("Y-m-d"); 

            //save update
            
            if ($validate_index == 0) {
                if ($data->course != '') {
                    $preregister = itfileupdate_Save_update(
                        $data
                    );
                    
                }
            }

            $baseurl_course = new moodle_url(
                '/course/view.php?id='.$id_course.'',
                null
            );
            echo "<br><strong>".get_string(
                'successful_message',
                'itfileupdate'
            )."</strong><br><br>";
            echo "<a href='".$baseurl_course."' class='btn btn-success'>".
            get_string(
                'link_home',
                'itfileupdate'
            )."</a><br>";


        } else {

            if ($validate_index != 0) {
                $baseurl_course = new moodle_url(
                    '/course/view.php?id='.$id_course.'',
                    null
                );
                echo "<br><strong>".get_string(
                    'duplicate_message',
                    'itfileupdate'
                )."</strong><br><br>";
                echo "<a href='".$baseurl_course."' class='btn btn-success'>".
                get_string(
                    'link_home',
                    'itfileupdate'
                )."</a><br>";
            } else {

                echo "<table>";
                echo "<tr><td>Usuario:</td><td>".$full_name."</td></tr>";
                echo "<tr><td>Email:</td><td>".$email."</td></tr>";
                if ($id_course != '') {
                    $course = itfileupdate_Get_Name_course($id_course);
                    echo "<tr><td>Curso:</td><td>".$course."</td></tr>";
                } else {
                    echo "<tr><td>Curso:</td><td><font color='red'>".get_string(
                        'error_course_empty',
                        'itfileupdate'
                    )."</font></td></tr>";
                }
                echo "</table>";
                echo "<br>".get_string('message_instructions_upd', 'itfileupdate').
                "<br><br>";
                $mformst->set_data($toform);
                $mformst->display();
            }
        }
    }
    
} else {
    $session_url = new moodle_url(
        '/mod/itfileupdate/itfileupdate.php', 
        null
    );
    $SESSION->wantsurl = $session_url;

    $login_url = new moodle_url(
        '/login/index.php', 
        null
    );
    redirect($login_url);

    $PAGE->navbar->add($strname);
    $PAGE->set_context($context);
    $PAGE->set_pagelayout('admin');
    $PAGE->set_heading($site->fullname);
    echo $PAGE->set_title(get_string('itfileupdate', 'itfileupdate'));  
    echo $OUTPUT->header();  
    echo $OUTPUT->heading(get_string('requireloginerror', 'itfileupdate'));  

}

echo $OUTPUT->footer();
