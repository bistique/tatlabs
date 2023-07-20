<?php
	session_start();
	date_default_timezone_set("Asia/Jakarta");
	ini_set('max_execution_time', 0);
	require_once('../requires/config.php');
	require_once('../requires/bukadata.php');
	require_once('../requires/fungsi.php');
	require_once('../plugins/PHPExcel/Classes/PHPExcel.php');
	
	//print_r($_FILES);exit;
	//if($_POST) {	
		$fileName = $_FILES['file']['name'];
		$fileType = $_FILES['file']['type'];
		$fileError = $_FILES['file']['error'];
		$fileContent = file_get_contents($_FILES['file']['tmp_name']);
		
		if($fileError == UPLOAD_ERR_OK){
			if(strtoupper($fileType)!=='APPLICATION/VND.MS-EXCEL')
				echo 'EXTERROR';
			else{
				$objPHPExcel = new PHPExcel();
				$load = PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
				$sheets=$load->getActiveSheet()->toArray(null,true,true,true);
				
				$i=1;
				foreach($sheets as $sheet){
					if($i>1){
						$periode=explode('-',$sheet['A']);
						$bulan=$periode[0];
						$tahun=$periode[1];
						
						//if(strlen($sheet['B'])<8)
						//	$nik='0'.$sheet['B'];
						//else
						//	$nik=$sheet['B'];
						
						$nik=substr($sheet['B'],-8);
						
						//$nik=$sheet['B'];
						$ijin=$sheet['G'];
						$mangkir=$sheet['H'];
						$sthari=$sheet['I'];
						$kop=$sheet['J'];
						$bpjskes=$sheet['K'];
						
						if(empty($ijin))
							$ijin='0';
						if(empty($mangkir))
							$mangkir='0';
						if(empty($sthari))
							$sthari='0';
						
						//inisiasi tarif mangkir
						$tarifijin=21;
						$tarifmangkir=10;
						$tarifsthari=0.5;

						//cari gaji pokok dan tunjangan jabatan untuk masing-masing karyawan
						$resultgaji=mysql_query("select noindex, gaji, tunjangan_jabatan from karyawan where nik='$nik'",$konek);
						$recordgaji=mysql_fetch_array($resultgaji);
						$indexkaryawan=$recordgaji["noindex"];
						$gajipokok=$recordgaji["gaji"];
						$tunjangan_jabatan=$recordgaji["tunjangan_jabatan"];
						
						//perhitungan nominal ijin, mangkir dan stengah hari
						$nominalijin=(($gajipokok+$tunjangan_jabatan)/$tarifijin)*$ijin;
						$nominalmangkir=($gajipokok+$tunjangan_jabatan)*($tarifmangkir/100)*$mangkir;
						$nominalsthari=((($gajipokok+$tunjangan_jabatan)/21)*$tarifsthari)*$sthari;
						
						//update potongan koperasi di master karyawan
						$resultgaji=mysql_query("update karyawan set potongan_koperasi=$kop where nik='$nik'",$konek);
						
						//cek apakah data absen sudah ada atau belom
						$resultgaji=mysql_query("select noindex from absensi where bulan='$bulan' and tahun='$tahun' and idxkaryawan='$indexkaryawan'",$konek);
						$jumrecgaji=mysql_num_rows($resultgaji);
						if($jumrecgaji>0){
							//update data absensi
							$resultgaji=mysql_query("update absensi set gajipokok='$gajipokok', tunjangan_jabatan='$tunjangan_jabatan', jumlahmangkir='$mangkir', tarifmangkir='$tarifmangkir', nominalmangkir='$nominalmangkir', jumlahijin='$ijin', tarifijin='$tarifijin', nominalijin='$nominalijin', jumlahsthari='$sthari', tarifsthari='$tarifsthari', nominalsthari='$nominalsthari', nominalbpjskesehatan='$bpjskes', updated_user='".$_SESSION["pyrol_user"]."' where bulan='$bulan' and tahun='$tahun' and idxkaryawan='$indexkaryawan'",$konek);
						}
						else{
							//insert ke table absensi
							$indexabsensi=generateindex('absensi');
							$resultgaji=mysql_query("insert into absensi(noindex, bulan, tahun, idxkaryawan, harikerja, gajipokok, tunjangan_jabatan, jumlahmangkir, tarifmangkir, nominalmangkir, jumlahijin, tarifijin, nominalijin, jumlahsthari, tarifsthari, nominalsthari, nominalbpjskesehatan, isclosed, added_user, updated_user) values('$indexabsensi', '$bulan', '$tahun', '$indexkaryawan', '0', '$gajipokok', '$tunjangan_jabatan', '$mangkir', '$tarifmangkir', '$nominalmangkir', '$ijin', '$tarifijin', '$nominalijin', '$sthari', '$tarifsthari', '$nominalsthari', '$bpjskes', '0', '".$_SESSION["pyrol_user"]."', '".$_SESSION["pyrol_user"]."')",$konek);
						}
					}
					$i++;
				}
				
				echo 'OK';
			}
		}else{
		   switch($fileError){
			 case UPLOAD_ERR_INI_SIZE:   
				  $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
				  break;
			 case UPLOAD_ERR_FORM_SIZE:  
				  $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
				  break;
			 case UPLOAD_ERR_PARTIAL:    
				  $message = 'Error: no terminó la acción de subir el archivo.';
				  break;
			 case UPLOAD_ERR_NO_FILE:    
				  //$message = 'Error: ningún archivo fue subido.';
				  echo 'NOFILE';
				  break;
			 case UPLOAD_ERR_NO_TMP_DIR: 
				  $message = 'Error: servidor no configurado para carga de archivos.';
				  break;
			 case UPLOAD_ERR_CANT_WRITE: 
				  $message= 'Error: posible falla al grabar el archivo.';
				  break;
			 case  UPLOAD_ERR_EXTENSION: 
				  $message = 'Error: carga de archivo no completada.';
				  break;
			 default: $message = 'Error: carga de archivo no completada.';
					  break;
			}
			  //echo json_encode(array(
			//		   'error' => true,
			//		   'message' => $message
			//		));
		}
	//}
?>