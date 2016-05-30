<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FileRepository;
use App\Http\Requests;

class FileController extends Controller
{
  protected $file;

  public function __construct(FileRepository $file)
    {
     $this->middleware('jwt.auth', ['except' => ['index','getAllAttributes','store']]);
      $this->file = $file;
    }

  public function index()
    {
      return $this->file->getAllFiles();
    }

  public function store(Request $request)
    {

          return $this->file->createFile($request);

    }

  public function update(Request $request, $id)
    {

    }

  public function destroy($id)
    {

    }


  public function show($id)
    {
        $file = $this->file->find($id);
        return $file;
    }


}
