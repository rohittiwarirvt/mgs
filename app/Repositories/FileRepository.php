<?php

namespace App\Repositories;

use App\Models\FileEntry;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Requests;

class FileRepository {

  private $file;
  private $user;

  public function __construct(FileEntry $file, User $user) {
    $this->file = $file;
    $this->user = $user;
  }


  public function createFileAPI(Request $request, $options = ['folder' => 'files','disk' => 'local'], $replace = false) {

    if ($request->hasFile('file'))  {
      $file = $request->file('file');
      $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $extension = $file->getClientOriginalExtension();
      $full_file_name = $file_name . '.' . $extension;
      $exists = Storage::disk($options['disk'])->exists($options['folder']. '/' .$full_file_name );
      $this->file->filemime = $file->getClientMimeType();
      $this->file->file_size = $file->getClientSize();
      $i = 1;
      if($exists && !$replace){
           $full_file_name = "{$file_name}_{$i}.{$extension}";
         Storage::disk('local')->put($options['folder'] . '/'. $full_file_name ,  File::get($file));
        $this->file->file_uri = $options['folder'] .'/'. $full_file_name;

      } else {
        Storage::disk('local')->put($options['folder'] . '/'. $full_file_name,  File::get($file));
        $this->file->file_uri = $options['folder'] .'/'. $full_file_name;
      }
      $this->file->filemime = $full_file_name;
      $this->file->save();
      return response()->json(['success saving file', [$exists, $replace, $full_file_name]]);
    }

  }
  public function createFile($file_url, $options = ['folder' => 'dirs','disk' => 'local'], $replace = false) {
      $file = $this->makeFileObj($file_url);
      $file_name = $file->filename;
      $extension = $file->extension;
      $full_file_name = $file_name . '.' . $extension;
      $exists = Storage::disk($options['disk'])->exists($options['folder']. '/' .$full_file_name );
      $i = 1;
      if($exists && !$replace){
          $full_file_name = "{$file_name}_{$i}.{$extension}";
         Storage::disk($options['disk'])->put($options['folder'] . '/'. $full_file_name ,  File::get($file_url));
        $data['file_uri'] = $options['folder']  .'/'. $full_file_name;

      } else {
        Storage::disk($options['disk'])->put($options['folder'] . '/'. $full_file_name,  File::get($file_url));
       $data['file_uri'] = $options['folder'] .'/'. $full_file_name;
      }
      $data['file_name'] = $full_file_name;
      $data['file_size'] = $file->file_size;
      $data['filemime'] = $file->filemime;
      return $this->file->create($data);
  }

  public function update(array  $data, $id, $attribute = 'id') {
    return $this->file->where($attribute, '=', $id)->update($data);
  }

  public function find($id, $columns = array('*')) {
    return $this->file->find($id, $columns);
  }

  public function findFileBy($attribute, $value, $columns = array('*')) {
    $result = $this->file->where($attribute, '=', $value)->first($columns);
    $file = Storage::disk('local')->get($result->file_name);

    return (new Response($file, 200))
              ->header('Content-Type', $result->filemime);
  }

  public function  getAllFiles() {
    return $this->file->all();
  }

  public function makeFileObj($file_url){
      $file= new \stdClass;
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $file->filename = pathinfo($file_url, PATHINFO_FILENAME);
      $file->filemime = finfo_file($finfo, $file_url);
      $file->file_size = filesize($file_url);
      $info = new \SplFileInfo($file_url);
      $file->extension = $info->getExtension();
      return $file;
  }
}
