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
require_once dirname(__FILE__).'/search_form.php';
//
require_once $CFG->libdir.'/adminlib.php';
$contextid = optional_param('contextid', 0, PARAM_INT);
$openlink = optional_param('openlink', 0, PARAM_INT); 
$id = optional_param('id', 0, PARAM_INT); 
$course_id = optional_param('course_id', 0, PARAM_INT); 
$user = optional_param('user', 0, PARAM_INT); 
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
$page = optional_param('page', 0, PARAM_INT);
$order = optional_param('order', 'name', PARAM_TEXT); 
$dir = optional_param('dir', 'asc', PARAM_TEXT); 
$edit = optional_param('edit', '', PARAM_INT); 
$del = optional_param('del', '', PARAM_INT); 

$strname = get_string('itfileupdate', 'itfileupdate');

if ($del != '') {
    itfileupdate_delete($del);
}

$baseurl = new moodle_url('/mod/itfileupdate/index.php', null);
if ($contextid) {
    $context = context_system::instance();
} else {
    $context = context_system::instance();
}

$PAGE->set_url($CFG->wwwroot.'/mod/itfileupdate/index.php');
$PAGE->navbar->add($strname);

$permissions = itfileupdate_Has_permissions();
if (isloggedin() && $permissions ) {
    global $SESSION;
    $SESSION->ccourses = null;
    $PAGE->set_context($context);
    $PAGE->set_pagelayout('admin');
    $PAGE->set_heading($site->fullname);
    $strorg = get_string('itfileupdate', 'itfileupdate');

    echo $PAGE->set_title($strorg);  
    echo $OUTPUT->header();  
    echo $OUTPUT->heading($strname);  

    $imagen_dir_id = $OUTPUT->image_url('menos', 'itfileupdate');
    if ($order=='id') {
        if ($dir == 'asc') {
            $imagen_dir_id = $OUTPUT->image_url('asc', 'itfileupdate');
            $link_dir = 'desc';
        } else {
            $imagen_dir_id = $OUTPUT->image_url('desc', 'itfileupdate');
            $link_dir = 'asc';
        }
    }

    $imagen_dir_course = $OUTPUT->image_url('menos', 'itfileupdate');
    if ($order=='course') {
        if ($dir == 'asc') {
            $imagen_dir_course = $OUTPUT->image_url('asc', 'itfileupdate');
            $link_dir = 'desc';
        } else {
            $imagen_dir_course = $OUTPUT->image_url('desc', 'itfileupdate');
            $link_dir = 'asc';
        }
    }

    $imagen_dir_nombre = $OUTPUT->image_url('menos', 'itfileupdate');
    if ($order=='name') {
        if ($dir == 'asc') {
            $imagen_dir_nombre = $OUTPUT->image_url('asc', 'itfileupdate');
            $link_dir = 'desc';
        } else {
            $imagen_dir_nombre = $OUTPUT->image_url('desc', 'itfileupdate');
            $link_dir = 'asc';
        }
    }

    $imagen_dir_area = $OUTPUT->image_url('menos', 'itfileupdate');
    if ($order=='area') {
        if ($dir == 'asc') {
            $imagen_dir_area = $OUTPUT->image_url('asc', 'itfileupdate');
            $link_dir = 'desc';
        } else {
            $imagen_dir_area = $OUTPUT->image_url('desc', 'itfileupdate');
            $link_dir = 'asc';
        }
    }

    $params = array(
        'id' => $id,
       'course_id' => $course_id
    );

   $customdatas = array('course_id' => $course_id);

    $sform = new Courses_form(
        null,
        $customdatas
    );

    if ($sform->is_cancelled()) {
    } else if ($dataform = $sform->get_data()) {
        $sform->display();
    } else {
        $sform->set_data($toform);
        $sform->display();
    }


    $image_download = $OUTPUT->image_url('flechitaabajo', 'itfileupdate');

    echo "<a href='./itfileupdate.php' class='btn btn-success'>&nbsp;&nbsp;".
    get_string(
        'link_update_form',
        'itfileupdate'
    )." &nbsp;</a><br>";

    // pagination
    $items_by_page_content = 20;
    $all_items = itfileupdate_Get_Total_students($course_id);
    $totalPag = ceil($all_items/$items_by_page_content);
    $baseurl = new moodle_url('/mod/itfileupdate/index.php', $params);
    echo get_string('pages', 'itfileupdate');
    for ($i=1; $i<=$totalPag ; $i++) {
        if ($page_st == $i) {
            $links[] = "<a href='".$baseurl."&page=$i'>".
            "<strong>$i</strong></a>"; 
        } else {
            $links[] = "<a href='".$baseurl."&page=$i'>$i</a>"; 
        }
    }
    echo implode(" - ", $links);
    $links = array();
    // end pagination

    echo "<table class='flexible reportlog generaltable generalbox'
    cellspacing='0'>";
    echo "<thead>";
    echo "<tr>";
        echo "<th class='header c0' scope='col'>";
        echo "<a href ='".$baseurl."&order=id&dir=$link_dir&page=$page'>".
        get_string(
            'label_table_content_id',
            'itfileupdate'
        )."<img src='".$imagen_dir_id."' 
        width='15px' height='15px'></a>";
        echo "</th>";

        echo "<th class='header c3' scope='col'>";
        echo  get_string(
            'label_table_download', 
            'itfileupdate'
        )."</a>";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "<a href ='".$baseurl."&order=name&dir=$link_dir&page=$page'>".
        get_string(
            'label_table_content_name',
            'itfileupdate'
        )."<img src='".$imagen_dir_nombre."' 
        width='15px' height='15px'></a>";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_email',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_numero_empleado',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_delegacion',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_id_sitio',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_cargo',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_red',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "<a href ='".$baseurl."&order=area&dir=$link_dir&page=$page'>".
        get_string(
            'label_table_content_area_adscripcion',
            'itfileupdate'
        )."<img src='".$imagen_dir_area."' 
        width='15px' height='15px'></a>";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_ubicacion_laboral',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c0' scope='col'>";
        echo "".get_string(
            'label_table_content_funcion',
            'itfileupdate'
        )."";
        echo "</th>";

        echo "<th class='header c3' scope='col'>";
        echo  get_string(
            'label_table_delete', 
            'itfileupdate'
        )."</a>";
        echo "</th>";
        
        echo "<th class='header c3' scope='col'>";
        echo "</tr>";
        echo "</thead>";

        $registered_data = itfileupdate_content(
            $order,
            $dir,
            $page,
            $items_by_page_content,
            $course_id
        );

        $baseurlb = new moodle_url('/mod/itfileupdate/index.php', null);
        echo "<tbody>";
        foreach ($registered_data as $data) {
            echo "<tr>";
            echo "<td>";
            $id=$data->id;
            echo $data->id;
            echo "</td>";
            $dataFile = itfileupdate_Get_File_data($data->itemid);
            $nameFileCv = $dataFile->filename;
            if (!is_null($nameFileCv)) {
                $url_file = moodle_url::make_file_url(
                    '/pluginfile.php',
                    '/'.$dataFile->contextid.'/'.$dataFile->component.'/'.
                    $dataFile->filearea.'/'.$dataFile->itemid.'/'.$dataFile->filename
                );
                echo "<td>";
                echo "<center><a href='".$url_file."' ><img src='".$image_download.
                "' width='25px' height='25px'></a></center>";
                echo "</td>";
            } else {
                echo "<td>";
                echo "</td>";
            }
            echo "<td>";
            echo $data->full_name;
            echo "</td>";
            echo "<td>";
            echo $data->email;
            echo "</td>";
            echo "<td>";
            echo $data->numero_empleado;
            echo "</td>";
            echo "<td>";
            echo $data->delegacion;
            echo "</td>";
            echo "<td>";
            echo $data->id_sitio;
            echo "</td>";
            echo "<td>";
            echo $data->cargo;
            echo "</td>";
            echo "<td>";
            echo $data->red;
            echo "</td>";
            echo "<td>";
            echo $data->area_adscripcion;
            echo "</td>";
            echo "<td>";
            echo $data->ubicacion;
            echo "</td>";
            echo "<td>";
            $fuct_nam = itfileupdate_Function_Name_id($data->funcion);
            echo $fuct_nam;
            echo "</td>";
            echo "<td>";
            echo "<a href ='".$baseurlb."?del=".$id."' 
            onclick='return confirmar()'>".get_string(
                'label_table_delete', 
                'itfileupdate'
            )."</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        $img_down = $OUTPUT->image_url('download_data', 'itfileupdate');
        echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="locallib.php?nr=1&course_id='.$course_id.'">'.
        '<img src="'.$img_down.'"".
        " height="35px" width="35px"><br>Descargar</a><br><br>';
        ?>
        <script type="text/javascript">
        function confirmar()
            {
                if(confirm('¿Estas seguro?'))
                    return true;
                else
                    return false;
            }
        </script>
        <?php
} else {
    $session_url = new moodle_url(
        '/mod/itfileupdate/', 
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

