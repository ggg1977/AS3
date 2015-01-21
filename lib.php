<?php
// Los siguientes datos han sido modificados para conservar la seguridad del sitio

define	(MYSQL_SRV,	"localhost");
define	(MYSQL_ID,	"xxx");
define	(MYSQL_PWD,	"xxx");
define	(DB_NAME,	"xxxxx");

define	(TB_NL, "</td></tr><tr><td>");
define	(TB_NF, "</td><td>");
define	(TB_TR, "</tr><tr>");

//----------------------------------------------------------------------------------------------------------------------

function char_replace ($cadena)
{
  $cadBuscar = array("á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú", "ñ", "Ñ", " ");
  $cadPoner = array("A", "A", "E", "E", "I", "I", "O", "O", "U", "U", "N", "N", "_");
  $cadena = str_replace ($cadBuscar, $cadPoner, $cadena);
  
/*  $n=0;
  foreach($cadBuscar as $caracter){
	  ereg_replace($caracter,$cadPoner[$n],$cadena);
	  $n++;
  }*/
  return $cadena;
} 


//------------------------------------------------------------------------------------------------------------------

function similitud($str0, $str1){
	$str	=	array(str0, str1);
	$length	=	array (strlen($str0),	strlen($str1));	
	if ($length[0]>$length[1]){
		$mayor = 0;
		$menor = 1;
	}else{
		$mayor = 1;
		$menor = 0;
	}
	$coincidencias	=	0;
	for($i=0;$i<=strlen($str[$mayor]);$i++){
		if($str0[$i]==$str1[$i])$coincidencias++;
		else{
			//echo 
			$pos = strrchr($str1,$str0[$i]);
			if ($pos>0)$coincidencias++;
		}
   }
   return $coincidencias;
}

//----------------------------------------------------------------------------------------------------------------------
function fotos_count($cat_id,$art_id) //Funcion modificada ****
{
	$dat		=	get_cat_data($cat_id);
	$table		=	$dat[0];
	$categoria	=	$dat[1];
	$dir_cat	=	$dat[2];
	
	$data = mysql_query("SELECT nombre, apellido FROM $table WHERE ".$table.".id = $art_id")or die(mysql_error()); 
	$artista	=	mysql_fetch_Array( $data );
	$nombre	=	$artista['nombre'];
	$apellido	=	$artista['apellido'];
	//$nombre_nospc = ereg_replace(" ","_",$nombre);
	//$apellido_nospc = ereg_replace(" ","_",$apellido);
//	$apellido_nospc	= ereg_replace("Ñ","N",$apellido_nospc);

	$str	=	$nombre;
	if ($apellido)$str .= " ".$apellido;
	
	//ereg_replace("Ñ","N",$str);
	//echo "dir: ".
	$dir_name		=	"$dir_cat/".strtoupper(char_replace($str));	
	

	$fotos_qty		=	0;
		
	if (is_dir($dir_name))
	{	
		//echo $dir_name;
		@delvar('archivos');	
 		if (false!=scandir($dir_name))
		{
			$archivos	=	scandir($dir_name);
		}else{
			$dh  = opendir($dir_name);
			while (false !== ($nombre_archivo = readdir($dh))) 
			{
				$archivos[] = $nombre_archivo;
			}
			sort($archivos,SORT_NUMERIC);
		}
		$cant_archivos	=	count($archivos);
		for($n=0;$n<$cant_archivos;$n++)
		{	
			$img_size	=	@getimagesize("$dir_name/$archivos[$n]");
			if ($img_size[2]==2)
			{
				$fotos_qty++;
				$foto[$fotos_qty]=$archivos[$n];
			}
		}
	}
	return $foto;
}

//----------------------------------------------------------------------------------------------------------------------

function show_image($cat_id,$artista_n,$foto_n,$size)
{
	global $doc_root,$dir_cat,$dir_name,$artista,$foto, $nombre, $apellido;
	define(XL_SIZE_X,2000);
	define(XL_SIZE_Y,1500);
	define(L_SIZE_X,1024);
	define(L_SIZE_Y,768);
	define(M_SIZE_X,800);
	define(M_SIZE_Y,600);
	define(S_SIZE_X,640);
	define(S_SIZE_Y,480);
	define(XS_SIZE_X,320);
	define(XS_SIZE_Y,200);
	$xl	=	array(XL_SIZE_X,XL_SIZE_Y);
	$l	=	array(L_SIZE_X,L_SIZE_Y);
	$m	=	array(M_SIZE_X,M_SIZE_Y);
	$s	=	array(S_SIZE_X,S_SIZE_Y);
	$xs	=	array(XS_SIZE_X,XS_SIZE_Y);
	$size_code	= array('xs','s','m','l','xl');
	
	// Los siguientes datos han sido modificados para conservar la seguridad del sitio
	
	if ($cat_id==1){$categoria="cat1";$dir_cat="dir1";}
	if ($cat_id==2){$categoria="cat2";$dir_cat="dir2";}
	if ($cat_id==3){$categoria="cat3";$dir_cat="dir3";}
	
	for ($n=0;$n<5;$n++)
	{
		if ($size==$size_code[$n])
		{
			$size_n=$n;	
		}
	}
	
	$pref_size 	= 	$$size;
	$fotos_qty = fotos_count($cat_id, $nombre, $apellido);
	if($size_n<3)
	{
		if(file_exists("$doc_root"."$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/reducidas/$foto[$foto_n]")==true)
		{
			$foto_file	=	"$doc_root"."$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/reducidas/$foto[$foto_n]";
		}
		elseif(file_exists("$doc_root"."$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/$foto[$foto_n]")==true)
		{
			$foto_file	=	"$doc_root"."$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/$foto[$foto_n]";
		}
		else
		{
		
		$foto_file	=	"";
		}
	}		
	else
	{
		if(file_exists("$doc_root"."$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/$foto[$foto_n]")==true)
		{
			$foto_file	=	"$doc_root"."$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/$foto[$foto_n]";
		}
	}
	
	if($existe=file_exists("$foto_file")==true)
	{
		$img_size	=	getimagesize($foto_file);
		$img_size_x	=	$img_size[0];
		$img_size_y	=	$img_size[1];
		
		while ($img_size[0]<$prefsize[0]||$img_size[1]<$pref_size[1])
		{
			$pref_size=$$size_code[$size_n--];
		}
		
		if($img_size_x>=$img_size_y)
		{
			$posicion='h';
			$relacion=$img_size_x/$img_size_y;
		}
		else
		{
			$posicion='v';
			$relacion=$img_size_y/$img_size_x;
		}

		if ($img_size_x>$pref_size[0]||$img_size_y>$pref_size[1]||$img_size_x<$pref_size[0]||$img_size_y<$pref_size[1])
		{
			if($posicion=='v')
			{
				$cambio=$pref_size[1]/$img_size_y;
				$y=$pref_size[1];
				$x=$img_size_x*$cambio;
			}
			else
			{
				$cambio=$pref_size[0]/$img_size_x;
				$x=$pref_size[0];
				$y=$img_size_y*$cambio;
			}
		}else{
			$x=$img_size_x;
			$y=$img_size_y;
		}
		echo "<img  src=\"$foto_file\" height=\"$y\" width=\"$x\">";
	}
	else
	{
		echo "No se encuentra el archivo $foto_file.";
	}
}

//----------------------------------------------------------------------------------------------------------------------
function reemplazar($cat_id,$artista_n)
{
		global $artista,$nombre,$apellido,$cat_id,$estado,$curriculum,$prioridad,$fotos_qty,$thumb,$categoria,$foto,$error_code;
			$artista[$artista_n][0]=$nombre."<br />";
		 	$artista[$artista_n][1]=$apellido."<br />";
		 	$artista[$artista_n][2]=$estado."<br />";
		 	$artista[$artista_n][3]=$prioridad."<br />";
		 	$artista[$artista_n][4]=$thumb."<br />";
		echo  "<p><strong>Se ha reemplazado el registro de $nombre $apellido.</p></strong></body></html>";	
		return true;
		$reemplazado=true;
}
//----------------------------------------------------------------------------------------------------------------------

function ordenar($cat_id, $campo)
{
	global $artista, $cantidad, $cat_id;
	$ordenados=0;
	echo "cantidad:".$cantidad;
	$falta_orden=true;
	while($falta_orden==true)
	{
		$falta_orden=false;
		for ($n=1;$n<=$cantidad-1;$n++)
		{
			if ($artista[$n][$campo]>$artista[$n+1][$campo])
			{
				echo "ordenar: $n ".$artista[$n][1];
				$falta_orden=true;
				$artista['xx']=$artista[$n];
				$artista[$n]=$artista[$n+1];
				$artista[$n+1]=$artista['xx'];
				$ordenados++;
			}
		}
	}
	if (!$ordenados){return 0;}
	else{return $ordenados;}
	echo "<br />Ordenados:$ordenados<br />";
	$falta_orden=false;
}	

//----------------------------------------------------------------------------------------------------------------------

function subir($cat_id,$artista_n)
{
	global $artista,$cat_id;
	
		switch($cat_id){
		case '1':
			$table="table1";
		break;
		
		case '2':
			$table="table2";
		break;

		case '3':
			$table="table3";
		break;
	}
	$old_pty	=	$artista[$artista_n]['orden'];


	if($artista_n>1)
	{
		if($artista[$artista_n]['orden']==$artista[$artista_n-1]['orden']){$artista[$artista_n]['orden']=$artista[$artista_n-1]['orden']-1;}
		else{
			$artista[$artista_n]['orden']=$artista[$artista_n-1]['orden'];
			$artista[$artista_n-1]['orden']=$old_pty;
		}
		echo "<div align=\"center\">";
		echo $sql_query = " UPDATE $table SET `orden` = ".$artista[$artista_n]['orden']." WHERE `id` = ".$artista[$artista_n]['id']." ";
		echo "<br>";
		mysql_query($sql_query)or die(mysql_error());
		echo "<br>";
		echo $sql_query = " UPDATE $table SET `orden` = ".$old_pty." WHERE `id` = ".$artista[$artista_n-1]['id']." ";
		mysql_query($sql_query)or die(mysql_error());
		echo "</div>";
	}
}

//----------------------------------------------------------------------------------------------------------------------

function bajar($cat_id,$artista_n)
{
	global $artista,$cat_id;
	
	// Los siguientes datos han sido modificados para conservar la seguridad del sitio
	
	switch($cat_id){
	case '1':
		$table="cat1";
	break;
	
	case '2':
		$table="cat2";
	break;

	case '3':
		$table="cat3";
	break;
	}
	
	if($artista_n+1)
	{
		$old_pty	=	$artista[$artista_n]['orden'];
		if($artista[$artista_n]['orden']==$artista[$artista_n+1]['orden']){$artista[$artista_n]['orden']=$artista[$artista_n+1]['orden']+1;}
		else{
			$artista[$artista_n]['orden']=$artista[$artista_n+1]['orden'];
			$artista[$artista_n+1]['orden']=$old_pty;
		}
		echo "<div align=\"center\">";
		echo $sql_query = " UPDATE $table SET `orden` = ".$artista[$artista_n]['orden']." WHERE `id` = ".$artista[$artista_n]['id']." ";
		echo "<br>";
		mysql_query($sql_query)or die(mysql_error());
		echo "<br>";
		echo $sql_query = " UPDATE $table SET `orden` = ".$artista[$artista_n+1]['orden']." WHERE `id` = ".$artista[$artista_n+1]['id']." ";
		mysql_query($sql_query)or die(mysql_error());
		echo "</div>";
	}
}

//----------------------------------------------------------------------------------------------------------------------
function eliminar_datos($cat_id,$artista_n)
{
	
	global $editado,$cantidad,$columnas,$artista,$nombre,$apellido,$estado,$curriculum,$prioridad,$fotos_qty,$thumb,$categoria,$foto,$error_code,$guardado;
	
	// Connects to your Database
	mysql_connect(MYSQL_SRV, MYSQL_ID, MYSQL_PWD) or die(mysql_error());
	mysql_select_db(DB_NAME) or die(mysql_error()); 
	
	// Los siguientes datos han sido modificados para conservar la seguridad del sitio
	
	switch($cat_id){
		case '1':
			$table="cat1";
		break;
		
		case '2':
			$table="cat2";
		break;

		case '3':
			$table="cat3";
		break;
	}
	$id = $artista[$artista_n]['id'];
	echo "<div align=\"center\"><br>Borrando:";
	print_r( $artista[$artista_n]['nombre']);
	echo " ";
	print_r( $artista[$artista_n]['apellido']);
	echo " ";
	echo "ID: $id </div>";
	mysql_query("DELETE FROM $table WHERE id = $id")or die(mysql_error());
	
//	$data		=	mysql_query("SELECT * FROM $table_name");
//	$artista	=	mysql_fetch_array($data);
	//echo "cantidad: ".$cantidad	=	count($artista);
}

//----------------------------------------------------------------------------------------------------------------------
function edit_datos($cat_id,$artista_n)
{
	global $editado,$cantidad,$columnas,$artista,$nombre,$apellido,$estado,$curriculum,$prioridad,$fotos_qty,$thumb,$categoria,$foto,$error_code,$guardado;
	
	// Connects to your Database
	mysql_connect(MYSQL_SRV, MYSQL_ID, MYSQL_PWD) or die(mysql_error());
	mysql_select_db(DB_NAME) or die(mysql_error()); 
	
	// Los siguientes datos han sido modificados para conservar la seguridad del sitio
	
	switch($cat_id){
		case '1':
			$table="cat1";
		break;
		
		case '2':
			$table="cat2";
		break;

		case '3':
			$table="cat3";
		break;
	}
	$id = $artista[$artista_n]['id'];
	
	echo "\n<table border=\"1\" align=\"center\"><th colspan=\"2\">Datos Antiguos</th>";
	echo TB_TR."<td><b>tabla:</b>".TB_NF .$table;
	echo TB_NL."<b>ID:</b>".TB_NF .$artista[$artista_n]['id'];
	echo TB_NL."<b>nombre:</b>".TB_NF .$artista[$artista_n]['nombre'];
	echo TB_NL."<b>apellido:</b>".TB_NF .$artista[$artista_n]['apellido'];
	echo TB_NL."<b>status:</b>".TB_NF .$artista[$artista_n]['status'];
	echo TB_NL."<b>orden:</b>".TB_NF .$artista[$artista_n]['orden'];
	echo TB_NL."<b>thumb:</b>".TB_NF .$artista[$artista_n]['thumb'];
	echo "</td></tr><table>\n";
	
	if($estado=="on"){$status=true;}
	$dif=0;

	$sql_query = "UPDATE `$table` SET ";
	if($nombre<>$artista[$artista_n]['nombre']){$dif++;$sql_query .= "`nombre` = '$nombre'";}
	if($apellido<>$artista[$artista_n]['apellido']){$dif++;	if($dif>1){$sql_query .= ", ";}	$sql_query .= "`nombre` = '$nombre'";}
	if($status<>$artista[$artista_n]['status']){$dif++;	if($dif>1){$sql_query .= ", ";}	$sql_query .= "`status` = '$status'";}
	if($prioridad<>$artista[$artista_n]['orden']){$dif++;	if($dif>1){$sql_query .= ", ";}	$sql_query .= "`orden` = '$prioridad'";}
	if($thumb<>$artista[$artista_n]['thumb']){$dif++;	if($dif>1){$sql_query .= ", ";}	$sql_query .= "`thumb` = '$thumb'";}

	echo "<div align=\"center\"><br>";
	if($dif>0){
		echo $sql_query .= " WHERE `id` = $id LIMIT 1 ";
		mysql_query($sql_query)or die(mysql_error());
	}else{echo "No se han realizado cambios";}
	echo "<br></div>";

//	$cantidad	=	count($artista);
	
	return true;
	$editado=true;
}

//----------------------------------------------------------------------------------------------------------------------
function cargar_datos_post()
{
	global $nombre,$apellido,$cat_id,$estado,$curriculum,$prioridad,$fotos_qty,$thumb,$categoria,$foto,$error_code,$doc_root,$artista_n,$accion,$dir_cat,$foto;	$doc_root	=	"";
	$accion		=	$_POST['accion'];
	if($accion=='Subir'||$accion=='Bajar'||$accion=='Editar'||$accion=='Eliminar'||$accion=='Mostrar')
	{
		$cat_id		=	$_POST['cat_id'];
		$artista_n	=	$_POST['artista_n'];
		$editar = $artista_n;
	}
	elseif( $accion == "Agregar" )
	{
		$cat_id 		=	$_POST['cat_id'];
		if(!$cat_id){echo "<p><strong>Debe ingresar Categoría.</strong></p>"; $error_code="3";}
	}
	if ($cat_id==1){$categoria="Categoria 1";$dir_cat="dir1";}
	if ($cat_id==2){$categoria="Categoria 2";$dir_cat="dir2";}
	if ($cat_id==3){$categoria="Categoria 3";$dir_cat="dir3";}
	if ($accion== "Agregar"|| $accion== "Editar" )
	{
		$nombre 		=	strtoupper($_POST['nombre']); if(!$nombre){echo "<p><strong>Debe ingresar Nombre. </strong></p>"; $error_code="1";}
		$nombre_min		=	ucwords(strtolower(ereg_replace("Á","á",ereg_replace("É","é",ereg_replace("Í","í",ereg_replace("Ó","ó",ereg_replace("Ú","ú",$nombre)))))));
		$nombre_min_nospc	=	ereg_replace(" ","_",$nombre_min);
		$nombre_nospc	=	ereg_replace(" ","_",$nombre);
		$apellido 		=	strtoupper($_POST['apellido']); if(!$apellido){echo "<p><strong>Debe ingresar Apellido.</strong></p>"; $error_code="2";}
		$apellido_min		=	ucwords(strtolower(ereg_replace("Á","á",ereg_replace("É","é",ereg_replace("Í","í",ereg_replace("Ó","ó",ereg_replace("Ú","ú",$apellido)))))));
		$apellido_min_nospc	=	ereg_replace(" ","_",$apellido);
		$apellido_nospc	=	ereg_replace(" ","_",$apellido);
		$estado 		=	$_POST['status']; if(!$estado){$estado="off";}
		$prioridad 		=	$_POST['prioridad']; if(!$prioridad){$prioridad='255';}
		$fotos_qty		=	fotos_count($cat_id,$nombre,$apellido);
		$thumb 			=	$_POST['thumb']; if(!$thumb){if($fotos_qty>0){$thumb='1';}}if($thumb>$fotos_qty){if($fotos_qty>0){echo "<p><strong>La foto principal no se encuentra!! Se asignara la primera.</strong></p>";$thumb='1';}else{$thumb=0;}}
		if($thumb==0){$thumb=1;}
		$thumb_jpg			=	$foto[$thumb];
	}
	if($error_code){return $error_code;}
}

//----------------------------------------------------------------------------------------------------------------------
function cargar_datos_file($cat_id)
{
	global $columnas,$datos,$artista,$fp,$cantidad,$categoria,$cat_id,$filename,$filename_bak;
	$item=0;
	if($fp){
		while(($datos=fgetcsv($fp,100,"\t","\n")) !== FALSE)
		{
			$item++;
			$artista[$item]=$datos;
			$columnas=count($datos);
			//echo "$item ".$artista[$item][0]."<br>";
		}
	}
	$cantidad=count($artista);
}
//----------------------------------------------------------------------------------------------------------------------

function get_cat_data($cat_id){ //Funcion nueva
	switch($cat_id){
	
	// Los siguientes datos han sido modificados para conservar la seguridad del sitio
	
			case '1':
			$table 		= 	"cat1";			$categoria="Categoria 1"; 			$dir_cat="dir1";
			break;
			
			case '2':
			$table 		= 	"cat2";$categoria="Categoria 2"; 	$dir_cat="dir2";
			break;
			
			case '3':
			$table		= 	"cat3";$categoria="Categoria 3"; $dir_cat="dir3";
			break;
			
			case '4':
			$table 		=	"cat4";		$categoria="Categoria 4"; 				$dir_cat="dir4";
			break;
			
			case '5':
			$table 		= 	"cat5";			$categoria="Categoria 5";					$dir_cat="dir5";
			break;
			
			case '6':
			$table		=	"cat6";				$categoria="Categoria 6";				$dir_cat=	"dir6";
			break;
		
			case '7':
			$table		=	"cat7";		$categoria="Categoria 7";				$dir_cat=	"dir7";
			break;
		
			case '8':
			$table		=	"cat8";		$categoria=	"Categoria 8"; 			$dir_cat=	"dir8";
			break;
		
			case '9':
			$table		=	"cat9";			$categoria=	"Categoria 9"; 			$dir_cat=	"dir9";
			break;
		
			case '10':
			$table		=	"cat10";			$categoria=	"Categoria 10"; 				$dir_cat=	"dir10";
			break;
		
			case '11':
			$table		=	"cat11";			$categoria=	"Categoria 11";		$dir_cat=	"dir11";
			break;
		
			case '12':
			$table		=	"cat12";			$categoria=	"Categoria 12"; 			$dir_cat=	"dir12";
			break;

	}
	$dat	=	array($table, $categoria, $dir_cat);
	return	$dat;
}
//----------------------------------------------------------------------------------------------------------------------

function cargar_lista_artistas($cat_id,$sort_fld)//Función modificada
{
	mysql_connect(MYSQL_SRV, MYSQL_ID, MYSQL_PWD) or die(mysql_error()) ;
	mysql_select_db(DB_NAME) or die(mysql_error()) ; 
	
	$dat		=	get_cat_data($cat_id);
	$table		=	$dat[0];
	$categoria	=	$dat[1];
	$dir_cat	=	$dat[2];
	
	$queryarray	=	array(
						id
						,
						nombre
						,
						apellido
						,
						orden
						,
						activo
						,
						thumb
						);
	$i=0;
	
	foreach($queryarray as $data){
		$querydata .= "`".$data."`";
		$i++;
		if ($i<count($queryarray)){
			$querydata .= ", ";
		}else{
			$querydata .= " ";
		}
	}
//	$querydata = "*";
//	echo $querydata;


	if($cat_id<>"11"){
		$sqlquery	=	"SELECT ". $querydata. " FROM `$table` WHERE `activo` = 1 ORDER BY `".strtolower($sort_fld)."` ASC ";
	}else{
		$sqlquery	=	"SELECT ". $querydata. " FROM `$table` ";
	}
	
	$data = mysql_query($sqlquery)or die(mysql_error()); 
	
	$item=0;
	while($artista[++$item]	=	mysql_fetch_Array( $data ));
	
	$u=0;
	$lista_artistas = array();
	foreach($artista as $art){
		//echo $u;
		//echo "nombre".$art['nombre'];
		
	 	$art['nombre']	=	 mb_convert_encoding ($art['nombre'],"UTF-8",mysql_client_encoding());
		if($cat_id<>"11")$art['apellido']	=	 mb_convert_encoding ($art['apellido'],"UTF-8",mysql_client_encoding());
		
		$fotos	=	fotos_count($cat_id, $u);
		$u++;
		
		$foto_id=0;
		//foreach($fotos as $foto){
		//	$art['foto'.$foto_id]=$foto;
		//	$foto_id++;
		//}
		

		if ($art['nombre']!="")array_push($lista_artistas, $art);
	}
	//echo count($lista_artistas);
	
	$item=0;
	while($artista[$item]	=	mysql_fetch_Array( $data ))
	{
		//echo $artista[$item]['nombre'];
	 	$artista[$item]['nombre']	=	 mb_convert_encoding ($artista[$item]['nombre'],"UTF-8",mysql_client_encoding());
		$artista[$item]['apellido']	=	 mb_convert_encoding ($artista[$item]['apellido'],"UTF-8",mysql_client_encoding());
		$fotos	=	fotos_count($cat_id, $item);
		$item++;
	}
	return $lista_artistas;
}

//----------------------------------------------------------------------------------------------------------------------

function cargar_lista_clientes()//Función modificada
{
	mysql_connect(MYSQL_SRV, MYSQL_ID, MYSQL_PWD) or die(mysql_error()) ;
	mysql_select_db(DB_NAME) or die(mysql_error()) ; 
	
	$table		=	"clientes";
	$categoria	=	$table;
	$dir_cat	=	$dat[2];
	
	$queryarray	=	array(
						id
						,
						nombre
						,
						titulo
						,
						descripcion
						,
						logo
						);

	$i=0;
	
	foreach($queryarray as $data){
		$querydata .= "`".$data."`";
		$i++;
		if ($i<count($queryarray)){
			$querydata .= ", ";
		}else{
			$querydata .= " ";
		}
	}

	$sqlquery	=	"SELECT ". $querydata. " FROM `$table` ";
	
	$data = mysql_query($sqlquery)or die(mysql_error()); 
	
	$item=0;
	while($clientes[++$item]	=	mysql_fetch_Array( $data ));
	
	return $clientes;
}

//----------------------------------------------------------------------------------------------------------------------
function verifica_repetido()
{
	global $artista,$nombre,$apellido,$item,$error_code,$cantidad,$cat_id,$estado,$curriculum,$prioridad,$fotos_qty,$thumb;
	if($cantidad>0){
		for($n=1;$n<=$cantidad;$n++)
		{
			if($artista[$n][0]==$nombre){
				if($artista[$n][1]==$apellido){
					echo "<p><strong>$nombre $apellido ya existe en la lista.<br />";
					echo "¿Desea reemplazarlo?<strong></p><br />";
					echo "<form action=\"reemplazar.php\" method=\"post\">";
					echo "<input type=\"hidden\" name=\"reemplazar\" value=\"$n\">";
					echo "<input type=\"submit\"  value=\"SI\" name=\"reemplaza\">";
					echo "<input type=\"submit\"  value=\"NO\" name=\"reemplaza\">";
					echo "<input type=\"hidden\" name=\"nombre\" value=\"$nombre\">";
					echo "<input type=\"hidden\" name=\"apellido\" value=\"$apellido\">";
					echo "<input type=\"hidden\" name=\"categoria\" value=\"$cat_id\">";
					echo "<input type=\"hidden\" name=\"estado\" value=\"$estado\">";
					echo "<input type=\"hidden\" name=\"prioridad\" value=\"$prioridad\">";
					echo "<input type=\"hidden\" name=\"thumb\" value=\"$thumb\">";
					echo  "</form>";
					$error_code='5';
					$repetido=$n;
					
				}
			}
		}
		if ($error_code==5){return $repetido;}else{return false;}
	}
}
//----------------------------------------------------------------------------------------------------------------------
function select_lista($lista)
{
	global $cat_id;

	echo "<table align=\"center\"><tr><td><p align=\"center\"><form action=\"lista.php\" method=\"get\" />";
	echo "<select name=\"cat_id\" >";
		echo "<option ";
		if ($cat_id=='1'){echo " selected ";}
		echo "value=\"1\">Nuevos Talentos</option> <br />";
  	
		echo "<option ";	
		if ($cat_id=='2'){echo " selected ";}
		echo "value=\"2\">Artistas Femeninos</option> <br />";
  	
		echo "<option ";	
		if ($cat_id=='3'){echo " selected ";}
		echo "value=\"3\">Artistas Mastculinos</option> <br />";
  	
	echo "</select>";
		echo "<input type=\"submit\" name=\"vista\" value=\"lista\" >";
	echo "<input type=\"submit\" name=\"vista\" value=\"menu\" >";
	echo "</form></td></tr></table>";
	
}

//----------------------------------------------------------------------------------------------------------------------
function delvar($var)
{
	if($var=='all')
	{
		$nombre 	= "";
		$apellido 	= "";
		$cat_id 	= "";
		$estado 	= "";
		$curriculum = "";
		$prioridad 	= "";
		$fotos_qty 	= "";
		$thumb 		= "";
		$thumb_jpg 	= "";
		$dir_cat 		= "";
		$foto 		= "";
		
		unset($artista);
		$cantidad="";
	}
	else
	{
		if (is_array($$var)	==	true)
		{
			unset($$var);
		}
		else
		{
			$$var	=	false;
		}
	}		
}

//----------------------------------------------------------------------------------------------------------------------
function cargar_datos_artista($cat_id,$art_id)  //Funcion modificada
{
	mysql_connect(MYSQL_SRV, MYSQL_ID, MYSQL_PWD) or die(mysql_error()) ;
	mysql_select_db(DB_NAME) or die(mysql_error()) ; 

	$dat		=	get_cat_data($cat_id);
	$table		=	$dat[0];
	$dir_cat	=	$dat[2];

	$query	=	"SELECT * FROM $table WHERE ".$table.".id = $art_id";
	$data	=	mysql_query($query);

	$datos	=	mysql_fetch_Array( $data );
/*
	$nombre				=	$datos['nombre'];
	$nombre_min			=	ucwords(strtolower(ereg_replace("Á","á",ereg_replace("É","é",ereg_replace("Í","í",ereg_replace("Ó","ó",ereg_replace("Ú","ú",$nombre)))))));
	$nombre_min_nospc 	=	ereg_replace(" ","_",$nombre_min);
	$nombre_nnn 		=	char_replace($nombre_min);
	$nombre_nospc 		=	ereg_replace(" ","_",$nombre);
	$apellido			=	$datos['apellido'];
	$apellido_min		=	ucwords(strtolower(ereg_replace("Á","á",ereg_replace("É","é",ereg_replace("Í","í",ereg_replace("Ó","ó",ereg_replace("Ú","ú",$apellido)))))));
	$apellido_min_nospc	=	ereg_replace(" ","_",$apellido_min);
	$apellido_nnn 		=	char_replace($apellido_min);
	$apellido_nospc		=	ereg_replace(" ","_",$apellido);
	$activo				=	$datos['status'];
	if(file_exists("$doc_root"."curriculums/$nombre_min_nospc"."_"."$apellido_min_nospc"."_"."Curriculum.pdf"))
	{
		$curriculum ="$doc_root"."curriculums/"."$nombre_min"."_"."$apellido_min"."_"."Curriculum.pdf";
	}else{$curriculum="";}
	$prioridad			=	$datos['orden'];
	$dir_name			=	"$dir_cat/$nombre_nospc"."_"."$apellido_nospc";
	$thumb				=	$datos['thumb'];
	if($thumb==0){$thumb=1;}
	$thumb_jpg			=	$foto[$thumb];
*/
	return $datos;
}


//----------------------------------------------------------------------------------------------------------------------


function mostrar_tabla_sql($cat_id)
{

	global $cantidad,$artista,$doc_root,$dir_cat,$foto;

	global 
	$prioridad,
	$dir_name,
	$fotos_qty,
	$thumb,
	$thumb_jpg,
	$fotos_jpg,
	$artista,
	
	$nombre,
	$nombre_min,
	$nombre_nnn,
	$nombre_min_nospc,
	$nombre_nospc,

	$apellido,
	$apellido_min,
	$apellido_nnn,
	$apellido_min_nospc,
	$apellido_nospc,
	
	$doc_root,
	$dir_cat,
	$dir_name,

	$activo,
	$curriculum_path,
	$curriculum_file,
	$curriculum_file_upr,
	$curriculum_pathfile,
	$curriculum_pathfile_upr,
	$curriculum;
	
	if ($cat_id==1){$categoria="Nuevos Talentos"; $dir_cat="Modelos";}
	if ($cat_id==2){$categoria="Artista Femenino"; $dir_cat="Actrices";}
	if ($cat_id==3){$categoria="Artista Masculino"; $dir_cat="Actores";}
	$login	=	$_SESSION['loggedin'];
	$cantidad	=	count($artista);
	echo	"<table border=\"0\" align=\"center\">";
	echo 	"<tr>
			<td valign=\"middle\" align=\"center\"><a href=\"lista.php?cat_id=$cat_id&vista=lista&sort_fld=nombre\"><img src=\"botones/abajo.gif\" width=\"20\" border=\"0\" ></a><strong> Nombre </strong></td>
			<td valign=\"middle\"align=\"center\"><a href=\"lista.php?cat_id=$cat_id&vista=lista&sort_fld=apellido\"><img src=\"botones/abajo.gif\" width=\"20\" border=\"0\" ></a><strong> Apellido </strong></td>".
			"<td valign=\"middle\"align=\"center\"><strong>Curric.</strong></td>".
			"<td valign=\"middle\"align=\"center\"><strong>Fotos</strong></td>
			<td valign=\"middle\"align=\"center\"><strong>Imagen</strong></td>"
			."</tr>";

	for ($artista_n=1;$artista_n<$cantidad;$artista_n++)
	{
		cargar_datos_artista($cat_id,$artista_n)or die("no se encontro $cat_id $artista_n");
		
			if ($activo||($login==1&&$activo==false)){
				echo		"\n<tr bordercolor=\"";
				echo 		"\">";
			
				echo		"<td valign=\"middle\" align=\"left\"><h3>".str_replace("Ñ","ñ",$nombre_min)."</h3></td>";
				echo		"<td valign=\"middle\" align=\"left\"><h3>".str_replace("Ñ","ñ",$apellido_min)."</h3></td>";
				
				
				//mostrar curriculum
				if($curriculum)
				{
					echo		"<td align=\"center\"><a href=\"$curriculum\"><img src=\"$doc_root"."pdf-logo.gif\" height=\"30\" weight=\"30\"></a></td>";
				}
				else
				{
					echo		"<td align=\"center\"><img src=\"$doc_root"."x.gif\"></td>";
				}
				
				//mostrar cantidad de fotos
				$fotos_qty = fotos_count($cat_id,$artista[$artista_n]['nombre'],$artista[$artista_n]['apellido']);
				echo		"<td align=\"center\"><b>$fotos_qty</b></td>";
				$thumb_jpg			=	ereg_replace(".JPG", ".jpg", $foto[$thumb]);
				$thmb_path	=	"$doc_root"."$dir_cat/".char_replace("$nombre_nospc")."_".char_replace("$apellido_nospc")."/thumbs/$thumb_jpg";
	
				//mostrar thumbnails
				if (file_exists($thmb_path))
				{
					echo		"<td align=\"center\"><a href=\"foto.php?";
					$txt	= "cat_id=$cat_id&artista_n=$artista_n";
					echo $urltxt = rawurldecode("$txt")."\" border=\"0\" bordercolor=\"\">";
					echo "<img src=\"$thmb_path\" width=\"50\" height=\"50\"></a></td>";
				}
	
	/*			else
				{
					$dir="$dir_cat/$nombre_nnn"."_"."$apellido_nnn";
					$fotos_qty = fotos_count($cat_id,$nombre,$apellido);
					list($width, $height) = getimagesize("img.php?file=$dir_cat/$nombre_nnn"."_"."$apellido_nnn/$foto[0]");
					echo		"<td align=\"center\"><img src=\"img.php?file=$dir_cat/$nombre_nnn"."_"."$apellido_nnn/$foto[0]";
					 
					if($width>$height){
						echo " width=\"50\" ";
					}else{
						echo " height=\"50\" ";
					}
					echo "></td>";
				}*/
			
				else
				{
					//echo $thmb_path;
					echo		"<td align=\"center\"><img src=\"unknown.jpg\" width=\"50\" height=\"50\"></td>";
				}
				if($login == 1)
				{
					echo		"<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" />";
					echo 			"<input type=\"hidden\" name=\"artista_n\" value=\"$artista_n\"/>"; 
					echo 			"<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\"/>"; 
					echo 			"<input type=\"hidden\" name=\"vista\" value=\"lista\">";
					if($sort_fld=="orden"||$sort_fld==""){
						echo 			"<td align=\"center\"><b>";
						print_r			($artista[$artista_n]['orden']);
						echo 			"</b></td>";
						echo 			"<td align=\"center\">";
						echo 			"<input type=\"submit\" name=\"accion\" Value=\"Subir\" />";
						echo			"</td>";
						echo			"<td align=\"center\">";
						echo			"<input type=\"submit\" name=\"accion\" Value=\"Bajar\"/>";
						echo			"</td>";
					}
					echo			"<td align=\"center\">";
					echo			"<input type=\"submit\" name=\"accion\" Value=\"Eliminar\"/>";
					echo			"</td>";
					echo 		"</form>";
					echo		"<form action=ingresar.php method=\"POST\" />";
					echo 			"<input type=\"hidden\" name=\"artista_n\" value=\"$artista_n\"/>"; 
					echo 			"<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\"/>"; 
					echo			"<td align=\"center\">";
					echo			"<input type=\"submit\" name=\"accion\" Value=\"Editar\"/>";
					echo 			"</td>";
					if($activo){echo " <td><img src=\"tilde.gif\" width=\"20\"></td>";} else {echo " <td><img src=\"x.gif\" width=\"20\"></td>";}
					echo 		"</form>";
					echo 	"</div></p>";
				}
				if($activo||$login){	echo 		"</tr>";}
			}
	}
	echo 		"</table>";

	if($login == '1')
	{
		echo 		"<p><div align=\"center\" >
					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" />";
					
		if (file_exists("$doc_root"."DB/$filename_bak"))
		{
			echo 		"<input type=\"submit\" name=\"accion\" value=\"Cancelar\"/>";
		}
		echo		"<input type=\"hidden\" name=\"cat_id\" value=$cat_id/>
					<input type=\"hidden\" name=\"vista\" value=\"lista\"/>
					</form></br>";
		
		echo 		"<p><div align=\"center\" ><form action=\"ingresar.php\" method=\"post\" /><input type=\"submit\" name=\"accion\" value=\"Agregar\"/></form>";
	}
}
//----------------------------------------------------------------------------------------------------------------------
function mostrar_menu_sql($cat_id)
{
	global $cantidad,$doc_root,$dir_cat,$artista,$foto,$login;
	
	global 
	$prioridad,
	$dir_name,
	$fotos_qty,
	$thumb,
	$fotos_jpg,
	$artista,
	
	$nombre,
	$nombre_min,
	$nombre_min_nnn,
	$nombre_min_nospc,
	$nombre_nospc,

	$apellido,
	$apellido_min,
	$apellido_min_nnn,
	$apellido_min_nospc,
	$apellido_nospc,
	
	$doc_root,
	$dir_cat,
	$dir_name,

	$activo,
	$curriculum_path,
	$curriculum_file,
	$curriculum_file_upr,
	$curriculum_pathfile,
	$curriculum_pathfile_upr,
	$curriculum;

	
	if ($cat_id==1){$categoria="Nuevos Talentos";$dir_cat="Modelos";}
	if ($cat_id==2){$categoria="Artista Femenino";$dir_cat="Actrices";}
	if ($cat_id==3){$categoria="Artista Masculino";$dir_cat="Actores";}
	
	for ($n=3;$n<=6;$n++){
		if (is_int($cantidad/$n)){$columnas=$n;}else{$columnas	= 5;}
	}
	$filas		= intval($cantidad / $columnas);
	if((($cantidad/$columnas)-(intval($cantidad/$columnas)))>0){$filas++;}
	echo "<table border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"5\" class=\"Estilo31\"><tr>";
	$artista_n	= 0;
	$posicion	= 0;
	$str		= "foto.php?cat_id=$cat_id";

	for ($fila=1;$fila<=$filas;$fila++)
	{
	  	for ($col=1;$col<=$columnas;$col++)
		{
			$artista_n++;
			if($artista_n<$cantidad)
			{
				$thumb				=	$artista[$artista_n][4];
				if($thumb==0){$thumb=1;}
				
				cargar_datos_artista($cat_id,$artista_n);

				
				$fotos_qty	=	fotos_count($cat_id, $nombre, $apellido);
				$thumb_jpg			=	$foto[$thumb];
				$posicion++;
						
				if (file_exists("$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/thumbs/$thumb_jpg")||$activo=='true')
				{
					echo "<td width=\"100\" align=\"center\" valign=\"top\" scope=\"col\"><div><p align=\"center\">";
					echo "<a href=\"";
					$str_thmb	=	$str. "&artista_n=$artista_n";
					echo $urlstr = rawurldecode($str_thmb);
					echo "\"><img src=\"$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/thumbs/$thumb_jpg\" width=\"100\" height=\"100\" border=\"0\" /> $nombre_min $apellido_min </a></p></div></td>";
				}
				elseif($activo)
				{
					echo "<td width=\"100\" align=\"center\" valign=\"top\" scope=\"col\"><div><p align=\"center\"><img src=\"unknown.jpg\" width=\"100\" height=\"100\"> $nombre_min $apellido_min </p></div></td>";
				}

			}
			if(is_int($posicion/$columnas))
			{
				echo"</tr><tr>";
			}
		}
	}
	echo "</tr></table>";
}
//----------------------------------------------------------------------------------------------------------------------


function login($loger)
{
	global $cat_id, $vista;
	
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1)
	{
		//They are logged in.
		//echo "<h3><p align=\"center\">Congratulations $_SESSION['user'].<br />You have logged in correctly!</p></h3>";
		echo "<p align=\"center\"><form action=".$_SERVER['PHP_SELF']."?cat_id=".$cat_id."&vista=".$vista."\" method=\"get\"><input type=\"submit\" name=\"accion\" value=\"Logout\"></p>";

//		echo "<form action=".$_SERVER['PHP_SELF']." method=\"get\"><input type=\"submit\" name=\"accion\" value=\"Logout\">";
	}
	else
	{
		if(isset($_POST['submit']))
		{
			//They have posted something!
			$username = "usr";
			$password = "pwd";
			if($_POST['pass'] == $password && $_POST['user'] == $username)
			{
				//They have sent us the correct login information!
				$_SESSION['loggedin'] = "1";
				$_SESSION['user'] = $_POST['user'];
				header('Location: '."lista.php");
				//The user has been redirected back to the main page and it should say they they have logged in!
			}
			else
			{
				//They failed to send us the correct username or password!
				die('Incorrect username or password!');
			}
		}
		elseif($loger==1)
		{
		echo "<table align=\"center\"><th colspan=2>LOGIN</th><tr><tr><td><form method=post action=\"ingresar.php\">Username: <input type=text name=user></td></tr><tr><td>Password: <input type=password name=pass></td></tr><tr><td colspan=\"2\"><input type=submit name=submit value=\"Login!\"></form><td></tr></table>";
		}
	}
}

//----------------------------------------------------------------------------------------------------------------------
function logout()
{
	if($_GET['accion']=='Logout'){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1){
			unset($_SESSION['loggedin']);
			unset($_SESSION['user']);
			session_destroy();
			header("Location: ".$_SERVER['PHP_SELF']);
		}else{
			echo "You are not logged in!";
		}
	}
}
	  
	  
//----------------------------------------------------------------------------------------------------------------------

function procesar()
{
	global $error_code,$nombre,$nombre_min,$apellido,$apellido_min,$cat_id,$categoria,$dir_cat,$estado,$prioridad,$thumb,$accion,$login,$artista_n,$filename,$filename_bak,$doc_root;

	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1)
	{
		$login	=	'true';
		$error_code=0;
		cargar_datos_post();
		cargar_datos_sql($cat_id);
		switch($accion)
		{
			case 'Cancelar':
			break;
			
			case 'Subir':
				subir($cat_id,$artista_n);
			break;
	
			case 'Bajar':
				bajar($cat_id,$artista_n);
			break;
	
			case 'Editar':
				edit_datos($cat_id, $artista_n);
			break;
			
			case 'Eliminar':
				echo "<div align=\"center\">Eliminar ".$artista[$artista_n]['nombre']." ".$artista[$artista_n]['apellido']."</div>";
				eliminar_datos($cat_id,$artista_n);
			break;
			
			case 'Agregar':
				switch($cat_id){
					case '1':
						$table="cat1";
					break;
					
					case '2':
						$table="cat2";
					break;
				
					case '3':
						$table="cat3";
					break;
				}

				if (verifica_repetido()){exit;}else{
					echo "<div align=\"center\"><br>";
					$sql_query = "INSERT INTO `$table` VALUES ('' , ";
					$sql_query .= " '$nombre' ,";
					$sql_query .= " '$apellido' ,";
					$sql_query .= " '$status' ,";
					$sql_query .= " '$prioridad' ,";
					$sql_query .= " '$thumb' )";
					echo "<br></div>";
					mysql_query($sql_query)or die(mysql_error());
				}
				
			break;
		}
	
	echo "<div align=\"center\"><p><strong>Los datos han sido ingresados correctamente.</strong></p><div><br>";
	}
	else
	{
	echo "<p align=\"center\"><h3> No está logueado </h3></p>";
	login();
	}
	
}
//----------------------------------------------------------------------------------------------------------------------

function thumbnail($file){
	// The file you are resizing
	//$file = 'yourfile.jpg';
	
	//This will set our output to 45% of the original size
	//$size = 0.45;
	
	// This sets it to a .jpg, but you can change this to png or gif
	header('Content-type: image/jpeg');
	
	// Setting the resize parameters
	list($width, $height) = getimagesize($file);
	
	if($width>=$height)
	{
		$posicion='h';
		//$relacion=$iwidth/$height;
		$modwidth="100";
		$modheight=$height/100;
	}
	else
	{
		$posicion='v';
		//$relacion=$img_size_y/$img_size_x;
		$modheight="100";
		$modwidth=$height/100;
	}
	
	
	//$modwidth = $width * $size;
	//$modheight = $height * $size;
	
	
	
	// Resizing the Image
	$tn = imagecreatetruecolor($modwidth, $modheight);
	$image = imagecreatefromjpeg($file);
	imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
	
	// Outputting a .jpg, you can make this gif or png if you want
	//notice we set the quality (third value) to 100
	imagejpeg($tn, null, 100); 
	
}

//----------------------------------------------------------------------------------------------------------------------

function zoom_tool()
{	
	echo "<table  border=\"0\" align=\"center\" >
		<tr><td class=\"Estilo32\">Si la imagen tarda demasiado en cargarse intente un tamaño menor</td></tr>".
			"<tr><td><p align =\"center\" ><div align=\"center\">
			<form action=\"foto.php\" method=\"get\" />
			<input type=\"submit\" name=\"accion\" value=\" + \" />
			 ZOOM 
			 <input type=\"submit\" name=\"accion\" value=\" - \" />
			 <input type=\"hidden\" name=\"artista_n\" value=\"$artista_n\">
 			 <input type=\"hidden\" name=\"foto_n\" value=\"$foto_n\">
 			 <input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">
  			 <input type=\"hidden\" name=\"size\" value=\"$size\">
			</form></td></tr> <tr><td>
			</td></tr></div></p></table>";
}
//----------------------------------------------------------------------------------------------------------------------

// Los siguientes datos han sido modificados para conservar la seguridad del sitio

function gen_lista_doc($cat_id){
	if ($cat_id==1){$categoria_s="cat1"; $dir_cat="dir1";}
	if ($cat_id==2){$categoria_s="cat2"; $dir_cat="dir2";}
	if ($cat_id==3){$categoria_s="cat3"; $dir_cat="dir3";}
	
	$filename = $dir_cat . ".doc";
	if(!file_exists($filename)){
		$doc_h = fopen($filename, 'w+')or die("\nEl archivo $filename no pudo ser creado");
		HttpResponse::setCache(true);
		$str = HttpResponse::capture(mostrar_tabla_sql_doc($cat_id));
		fwrite($doc_h, $str)or die("\nEl archivo $filename no pudo ser llenado");
		fclose($doc_h)or die("\nEl archivo $filename no pudo ser cerrado"); 
	}else{
		echo "<a href=\"". $filename ."><div align=\"center\"><h5>Descargar Lista de ".$categoría_s."</h5></div></a>";
	}
}
//----------------------------------------------------------------------------------------------------------------------
// Los siguientes datos han sido modificados para conservar la seguridad del sitio

function descargar_lista($cat_id)
{
	if ($cat_id==1){$categoria_s="Categoria 1"; $dir_cat="dir1";}
	if ($cat_id==2){$categoria_s="Categoria 2"; $dir_cat="dir2";}
	if ($cat_id==3){$categoria_s="Categoria 3"; $dir_cat="dir3";}

	echo "\n<p align=\"center\"><a href=";
	echo "\"listado_".$dir_cat.".php\"";
    echo "><em><strong>DESCARGAR LISTAS <img border=0 src=$doc_root/pdf-logo.gif\" width=\"20\" height=\"20\" /> </strong></em></a></p>";
}

//----------------------------------------------------------------------------------------------------------------------
function display_name($cat_id,$artista_n){
	global 
	$prioridad,
	$dir_name,
	$fotos_qty,
	$thumb,
	$fotos_jpg,
	$artista,
	$str,
	
	$nombre,
	$nombre_min,
	$nombre_min_nnn,
	$nombre_min_nospc,
	$nombre_nospc,

	$apellido,
	$apellido_min,
	$apellido_min_nnn,
	$apellido_min_nospc,
	$apellido_nospc,
	
	$doc_root,
	$dir_cat,
	$dir_name,

	$activo,
	$curriculum_path,
	$curriculum_file,
	$curriculum_file_upr,
	$curriculum_pathfile,
	$curriculum_pathfile_upr,
	$curriculum;

	$str	= "foto.php?cat_id=$cat_id";

	if($activo=='off'&&$login==1){
		echo "<td border=\"0\" width=\"250\" align=\"center\" valign=\"top\" scope=\"col\"><div>";
//		echo "<a href=\"";
//		$str_thmb	=	$str. "&artista_n=$artista_n";
//		echo $urlstr = rawurldecode($str_thmb);
//		echo "\">";
		echo "<h5>$nombre_min $apellido_min</h5>";
//		echo "</a>";
		echo "</div></td>";
	}else{
		echo "<td border=\"0\" width=\"250\" align=\"center\" valign=\"top\" scope=\"col\"><div>";
//		echo "<a href=\"";
//		$str_thmb	=	$str. "&artista_n=$artista_n";
//		echo $urlstr = rawurldecode($str_thmb);
//		echo "\">";
		echo str_replace("Ñ","ñ",$nombre_min)." ".str_replace("Ñ","ñ",$apellido_min)." ";
//		echo "</a>";
		echo "</div></td>";
	}
}

//----------------------------------------------------------------------------------------------------------------------

function mostrar_lista($cat_id)
{
	global $cantidad,$doc_root,$dir_cat,$artista,$foto,$login;
	
	global 
	$prioridad,
	$dir_name,
	$fotos_qty,
	$thumb,
	$fotos_jpg,
	$artista,
	
	$nombre,
	$nombre_min,
	$nombre_min_nnn,
	$nombre_min_nospc,
	$nombre_nospc,

	$apellido,
	$apellido_min,
	$apellido_min_nnn,
	$apellido_min_nospc,
	$apellido_nospc,
	
	$doc_root,
	$dir_cat,
	$dir_name,

	$activo,
	$curriculum_path,
	$curriculum_file,
	$curriculum_file_upr,
	$curriculum_pathfile,
	$curriculum_pathfile_upr,
	$curriculum;

	// Los siguientes datos han sido modificados para conservar la seguridad del sitio
	
	if ($cat_id==1){$categoria="Categoria 1";$dir_cat="dir1";}
	if ($cat_id==2){$categoria="Categoria 2";$dir_cat="dir2";}
	if ($cat_id==3){$categoria="Categoria 3";$dir_cat="dir3";}

	$columnas = 3;
	$filas		= intval($cantidad / $columnas);
	if((($cantidad/$columnas)-(intval($cantidad/$columnas)))>0){$filas++;}
	//echo "<h1 align=\"center\">$dir_cat</h1>";
	echo "<table width=\"80%\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"5\" class=\"Estilo31\"><tr>";
	$artista_n	= 0;
	$posicion	= 0;

	for ($fila=1;$fila<=$filas;$fila++)
	{
	  	for ($col=1;$col<=$columnas;$col++)
		{
			$artista_n++;
			if($artista_n<=$cantidad)
			{
				cargar_datos_artista($cat_id,$artista_n);
				$posicion++;
				display_name($cat_id,$artista_n);
			}
			if(is_int($posicion/$columnas))
			{
				echo"</tr><tr>";
			}
		}
	}
	echo "</tr></table>";
}
//----------------------------------------------------------------------------------------------------------------------
function thumb_table($cat_id, $nombre, $apellido){
	global $foto, $dir_cat, $artista_n;
  echo  "<div class=\"feature\"><table  border=\"0\"align=\"center\" cellspacing=\"10\"><tr align=\"center\" valign=\"middle\">";
		if(!$fotos_qty)
		{
			$fotos_qty	=	fotos_count($cat_id,$nombre,$apellido);
		}
		for ($n=1;$n<=$fotos_qty;$n++)
		{	
			if (!$size){$size='l';}
 			$query = "foto.php?". rawurldecode("cat_id=$cat_id&artista_n=$artista_n&foto_n=$n&size=$size");
			echo	"<td align=\"center\"><a href=\"$query\"><img border=\"0\" src=\"$doc_root/$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/thumbs/".ereg_replace(".JPG", ".jpg", $foto[$n])."\" width=\"50\" height=\"50\"></a>";
			echo "</td>";
			if (($col=is_int($n/4))==true)
			{
				echo "</tr><tr>";
			}
		}
     echo "</tr></table></div>" ;
}
//----------------------------------------------------------------------------------------------------------------------

function thumb_nav()
{
	global 
	$foto_n,
	$dir_cat,
	$nombre,
	$apellido,
	$str,
	$foto;
	
/*	echo 
	" foto: ".$foto_n.
	" dir_cat: ".$dir_cat.
	" nombre: ".$nombre.
	" apellido: ".$apellido.
	" cant fotos: ".$fotos_qty;*/
	//echo "fotos:".
	$fotos_qty=fotos_count($dir_cat,$nombre,$apellido);
	
	//echo "</br>restantes:".
	$fotos_restantes = $fotos_qty - $foto_n;
	$min = -2;
	$max = 3;

	while ($foto_n+$min<1)
	{
		$min++;
		$max++;
	}
	
	while (($foto_n+$max-1)>$fotos_qty)
	{
		if(($foto_n+$min)>1){$min--;}
		$max--;
	}	
	//echo "min:".$min." max:".$max;		
		
		echo "<div>
		<table border=0 align=\"center\"><tr border=1>";
			
		if (($foto_n+($min-1))>0)
		{
			echo "\n<td border=0 width=\"54\" scope=\"col\">";
			echo "<a href=\"";
			$str_thmb	=	$str."&foto_n=". ($foto_n + ($min-1));
			// ."&size=l" ;
			echo $urlstr = rawurldecode($str_thmb);
			echo "\"><img src=\"botones/anterior.gif\" width=\"57\" height=\"57\" border=\"0\" /></a></td>";
		}
			
		for ($n=$min;$n < $max; $n++)
		{
			if(file_exists("$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/thumbs/".ereg_replace(".JPG",".jpg",$foto[$foto_n+$n]))==true&&$foto_n+$n>0)
			{
				//echo "min:".$min." max:".$max;		
				//echo $foto[$foto_n+$n];
				echo "\n<td width=\"54\" scope=\"col\">";
				echo "<a href=\"";
				$str_thmb	=	$str."&foto_n=". ($foto_n + $n);
				echo $urlstr = rawurldecode($str_thmb);
				echo "\" > <img src=\"$dir_cat/".char_replace("$nombre")."_".char_replace("$apellido")."/thumbs/".ereg_replace(".JPG", ".jpg", $foto[$foto_n+$n])."\" width=\"100\" height=\"100\" border=\"0\" /></a></td>";
			}
		}
	
		if ($foto_n + $max<=$fotos_qty)
		{
			echo "
			<td width=\"54\" scope=\"col\">";
			echo "<a href=\"";
			$str_thmb	=	$str."&foto_n=". ($foto_n + $max);
			// . "&size=l" ;
			echo $urlstr = rawurldecode($str_thmb);
			echo "\"><img src=\"botones/siguiente.gif\" width=\"57\" height=\"57\" border=\"0\" /></a></td>";
		}
		echo "
		</tr>
		</table></div>";
}
//----------------------------------------------------------------------------------------------------------------------

?>
