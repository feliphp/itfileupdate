<?php
/**
 * Fecha: 2019-10-10 - Update: 2019-10-14
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

defined('MOODLE_INTERNAL') || die();

/**
 * Has permissions
 * 
 * @return boolean
 */
function itfileupdate_Has_permissions()
{
    global $USER;
    $permission = false;
    $context = get_context_instance(CONTEXT_SYSTEM);

    if (is_siteadmin()) {
        $permission = true;
    } else {
        $roles = get_user_roles($context, $USER->id, false);
        $role = key($roles);
        $roleid = $roles[$role]->roleid;
        if ($roleid == 1) {
            $permission = true;
        }
    }
    return $permission;

}

/**
 * Get Courses
 * 
 * @return array
 */
function itfileupdate_Get_courses() 
{
    global $DB;
    $query ="SELECT DISTINCT course FROM {itfileupdate}";
    return $DB->get_records_sql($query);
}

/**
 * Get Course name
 * 
 * @param int $id id course
 * 
 * @return array
 */
function itfileupdate_Get_Course_name($id) 
{
    global $DB;
    $query ="SELECT fullname FROM {course} WHERE id = ".$id."";
    $result_string = $DB->get_record_sql($query);
    return $result_string->fullname;
}

/**
 * Get username
 * 
 * @param int $id id user
 * 
 * @return string
 */
function itfileupdate_Get_username($id)
{
    global $DB;
    $query ="SELECT username FROM {user} WHERE id = ".$id."";
    $result_string = $DB->get_record_sql($query);
    return $result_string->username;
}

/**
 * Exist
 * 
 * @param String $email  email
 * @param String $course course
 * 
 * @return int
 */
function itfileupdate_exist($email,$course)
{
    global $DB;
    
    $sql = "SELECT count(*) as item FROM {itfileupdate} ".
    "WHERE email = '".$email."' AND course = '".$course."'";
    $array_count = $DB->get_record_sql($sql, null);
    return $array_count->item;
}

/**
 * itfileupdate Save update
 * 
 * @param array $data data
 *  
 * @return bool
 */
function itfileupdate_Save_update(
    $data
) {
    global $DB;

     $newautoenrolid = $DB->insert_record('itfileupdate', $data);

    if ($newautoenrolid) {
        return true;
    } else {
        return false;
    }
}

/**
 * Content Delete
 * 
 * @param int $id id 
 *  
 * @return bool
 */
function itfileupdate_delete(
    $id
) {
    global $DB;
    $sql_delete = "DELETE FROM mdl_itfileupdate WHERE id = ".$id."";
    if ($DB->execute($sql_delete)) {
        return true;
    } else {
        return false;
    }
}


/**
 * Get itfileupdate by id
 * 
 * @param int $id id
 * 
 * @return array
 */
function itfileupdate_By_Id_form($id)
{
    global $DB;

    $sql = "SELECT * FROM mdl_itfileupdate WHERE id = $id ";    
    return $DB->get_record_sql($sql, null);
}



/**
 * itfileupdate Get Full name
 * 
 * @param int $userid user id
 *  
 * @return string
 */
function itfileupdate_Get_Full_name($userid)
{
    global $DB;

    $sql = "SELECT firstname, lastname FROM mdl_user WHERE id = $userid ";  
    $ret = $DB->get_record_sql($sql);
    $fullname = $ret->firstname . " " . $ret->lastname;
    return $fullname;
}

/**
 * itfileupdate Get Email
 * 
 * @param int $userid user id
 *  
 * @return string
 */
function itfileupdate_Get_email($userid)
{
    global $DB;

    $sql = "SELECT email FROM mdl_user WHERE id = $userid ";  
    $ret = $DB->get_record_sql($sql);
    $email = $ret->email;
    return $email;
}

/**
 * Get total students
 * 
 * @param int $course_id course id
 * 
 * @return int
 */
function itfileupdate_Get_Total_students($course_id)
{
    global $DB;
    if ($course_id == 0) {
        $query = '';
    } else {
        $query = 'WHERE course ='.$course_id.'';
    }
    $sql = "SELECT count(*) as item FROM {itfileupdate} $query";
    $array_count = $DB->get_record_sql($sql, null);
    return $array_count->item;
}

/**
 * Get Content
 * 
 * @param String $order     value of order
 * @param String $dir       value of dir
 * @param String $page      value of page
 * @param String $limit     value of limit
 * @param int    $course_id course id
 * 
 * @return array
 */
function itfileupdate_content($order,$dir,$page,$limit,$course_id)
{
    global $DB;
    $filter_query = '';

    if (!$dir) {
         $dir = 'asc';
    }

    if ($order == '') {
        $filtro_query_orden="ORDER BY id ASC";
    } elseif ($order == 'id') {
        $filtro_query_orden="ORDER BY id $dir";
    } elseif ($order == 'name') {
        $filtro_query_orden="ORDER BY full_name $dir";
    } elseif ($order == 'course') {
        $filtro_query_orden="ORDER BY course $dir";
    } 

    if ($course_id == 0) {
        $query = '';
    } else {
        $query = 'AND course ='.$course_id.'';
    }

    // página pedida
    $pag = (int) $page;
    if ($pag < 1) {
        $pag = 1;
    }
    $offset = ($pag-1) * $limit;

    $sql = "SELECT * FROM mdl_itfileupdate WHERE id <> 0 $query $filter_query 
    $filtro_query_orden LIMIT $offset, $limit";
    
    return $DB->get_records_sql($sql, null);
}

/**
 * Get itfileupdate by id
 * 
 * @param int $id_course id
 * 
 * @return string
 */
function itfileupdate_Get_Name_course($id_course)
{
    global $DB;

    $sql = "SELECT fullname FROM mdl_course".
    " WHERE id = $id_course ";  
    $ret = $DB->get_record_sql($sql);
    $coursename = $ret->fullname;
    return $coursename;
}

/**
 * Get itfileupdate Function Name by id
 * 
 * @param String $id id
 * 
 * @return string
 */
function itfileupdate_Function_Name_id($id)
{
    if ($id == '0') {
        $ret = 'Enlace';
    } else if ($id == '1') {
        $ret = 'Apoyo';
    } else if ($id == '2') {
        $ret = 'Otro';
    } else {
        $ret = 'Sin Función';
    }
    return $ret;
}

/**
 * Serves the images course attachments. Implements needed access control ;-)
 *
 * @package  mod_itfileupdate
 * @category files
 * @param    stdClass $course        course object
 * @param    stdClass $cm            course module object
 * @param    stdClass $context       context object
 * @param    string   $filearea      file area
 * @param    array    $args          extra arguments
 * @param    bool     $forcedownload whether or not force download
 * @param    array    $options       additional options affecting the file serving
 * @return   bool     false          if file not found, does not return if found 
 */
function itfileupdate_pluginfile(
    $course, 
    $cm, $context, 
    $filearea, 
    $args, 
    $forcedownload, 
    array $options=array()
) {
    global $CFG, $DB;
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false; 
    }
    $itemid = array_shift($args);

    if ($filearea !== 'cvfile' && $filearea !== 'imgfilearea') {
        return false;
    }
    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); 
    if (!$args) {
        $filepath = '/'; 
    } else {
        $filepath = '/'.implode('/', $args).'/'; 
    }
    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file(
        $context->id, 
        'mod_itfileupdate', 
        $filearea, 
        $itemid, 
        $filepath, 
        $filename
    );
    
    if (!$file) {
        return false; // The file does not exist.
    }
    // From Moodle 2.3, use send_stored_file instead.
    send_file($file, $filename, 0, $forcedownload, $options);
}

/**
 * itfileupdate_Get_File_data
 * 
 * @param Int $userid id
 * 
 * @return array
 */
function itfileupdate_Get_File_data($userid)
{
    global $DB;
    $query = "SELECT f.contenthash, f.id, f.contextid, f.component,f.filearea,".
    " f.itemid, f.filepath, f.filename FROM mdl_files f  WHERE f.filename <> '.' ".
    " AND filearea ='cvfile' AND f.userid = $userid";
    $result = $DB->get_record_sql($query);
    if ($result == false) {
        $result = '0';
    } else {

    }
    return $result;

}

