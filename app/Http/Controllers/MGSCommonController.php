<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Models\Service;
use DB;

class MGSCommonController extends Controller
{

    /**
    * Get City List from database
    *
    **/
    public function getCities()
    {
        $cities = DB::table('city')->select('id', 'name')->where('is_active',1)->get();
        return $cities;
    }

    /* Get testimonial From database */
     public function MgetTestimonial()
     {
        $testimonials=  DB::table('testimonial')
            ->leftJoin('services','services.id','=','testimonial.service_id')
            ->select('testimonial.author_name','testimonial.description','services.service_name')
            ->get();

            return json_encode(array('testimonialdata'=>$testimonials));
     }

    /**
     * Get Services List from database
    *
    **/
    public function getServices()
    {
        $services = Service::select('services.*', 'files.file_uri');
        $services = $services->Join('files', 'services.file_id', '=', 'files.id')
                             ->where('services.is_active',1)
                             ->get();
        return $services;
    }
}

