<?php
      //$finfo = finfo_open(FILEINFO_MIME_TYPE);
     // $this->file->filemime = finfo_file($finfo, $filename)
 //$file = finfo_file('./serviceimages/mgs-appliance-repair-icon.png', FILEINFO_MIME);
  //$finfo = finfo_open(FILEINFO_MIME_TYPE);
  //$this->file->filemime = finfo_file($finfo, $filename)
  //$file_name = new SplFileInfo();
  $file_url = './serviceimages/mgs-appliance-repair-icon.png';
   $file= new \stdClass;
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $file->filename = pathinfo($file_url, PATHINFO_FILENAME);
      $file->filemime = finfo_file($finfo, $file_url);
      $file->file_size = filesize($file_url);
      $info = new \SplFileInfo($file_url);
      $file->extension = $info->getExtension();
 var_dump($file);

?>
