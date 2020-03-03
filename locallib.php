<?php
  /**
   * Fecha:  2019-09-03 - Update: 2019-11-13
   * PHP Version 7
   * 
   * @category   Components
   * @package    Moodle
   * @subpackage Mod_Itpreregister
   * @author     JFHR <felsul@hotmail.com>
   * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
   * @link       https://aulavirtual.issste.gob.mx
   */
require_once dirname(dirname(dirname(__FILE__))).'/config.php';
require_once dirname(__FILE__).'/vendor/xlsxwriter/xlsxwriter.class.php';
require_once dirname(__FILE__).'/lib.php';

/**
 * To Excell 
 * 
 * @param int $course_id id course
 * 
 * @return null
 */
function To_excell($course_id)
{
    $filename = "ActualizacionDeAlumnosAulaVirtual.xlsx";
    header(
        'Content-disposition: attachment; filename="'.
        XLSXWriter::sanitize_filename($filename).'"'
    );
    header(
        "Content-Type: application/vnd.openxmlformats-officedocument".
        ".spreadsheetml.sheet"
    );
    header(
        'Content-Transfer-Encoding: binary'
    ); 
    header(
        'Cache-Control: must-revalidate'
    );
    global $DB;

    if ($course_id == 0) {
        $queryadd = '';
    } else {
        $queryadd = 'WHERE course ='.$course_id.'';
    }

    $header = array( 
        'Nombre'=>'string',
        'Curso'=>'string',
        'Email'=>'string',
        'Numero de empleado'=>'string',
        'Delegación'=>'string',
        'C.V.'=>'string',
        'ID Sitio'=>'string',
        'Cargo'=>'string',
        'Función'=>'string',
        'Nombre de Archivo'=>'string',
        'Fecha Creación'=>'string',
        'Fecha Modificación'=>'string'
    );
    
    $styleHeader = array(
        'fill'=>'#80CAF9',
        'font-style'=>'bold',
        'border'=>'left,right,top,bottom'
    );

    $writer = new XLSXWriter(); 
    $writer->writeSheetHeader('Sheet1', $header, $styleHeader);
    $array = array();
    $query = 'SELECT * FROM {itfileupdate} '.$queryadd.'';
    $resultu = $DB->get_recordset_sql($query);

    foreach ($resultu as $users) {
        for ($i=0; $i< count(resultu); $i++ ) {
            $array['A'.$i] = $users->full_name; 
            $name_course = itfileupdate_Get_Name_course(
                $users->course
            );
            $array['B'.$i] = $name_course; 
            $array['C'.$i] = $users->email; 
            $array['D'.$i] = $users->numero_empleado; 
            $array['E'.$i] = $users->delegacion; 
            $dataFile = itfileupdate_Get_File_data($users->itemid);
            $nameFileCv = $dataFile->filename;
            if (!is_null($nameFileCv)) {
                $url_file = moodle_url::make_file_url(
                    '/pluginfile.php',
                    '/'.$dataFile->contextid.'/'.$dataFile->component.'/'.
                    $dataFile->filearea.'/'.$dataFile->itemid.'/'.$dataFile->filename
                );
                $array['F'.$i] = '=HYPERLINK("'.$url_file.
                '","'.$users->url_file.'")';
            } else {
                $array['F'.$i] = ''; 
            }
            $array['G'.$i] = $users->id_sitio; 
            $array['H'.$i] = $users->cargo; 
            $funcion = itfileupdate_Function_Name_id($users->funcion);
            $array['I'.$i] = $funcion;
            $array['J'.$i] = $users->url_file;
            $array['K'.$i] = $users->datecreated;
            $array['L'.$i] = $users->datemodified;
            
        }
        $writer->writeSheetRow('Sheet1', $array);
    }   
    $writer->writeToStdOut();
    
}

$number_report=$_GET['nr'];
$course_id=$_GET['course_id'];
if ($number_report == '1') {
    To_excell($course_id);
} 
exit(0);
