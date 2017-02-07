<?php
$files = preg_split('@\n@',file_get_contents( 'upgrade/files.php' ));
foreach($files as $f){
	$info = preg_split('@\s+@',trim($f));
}
