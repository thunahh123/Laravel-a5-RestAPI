<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//
Route::get('/',function(){
    $patients = Patient::all();
    Log::channel("api")->info("GET request - '/api' -".$patients);
    return $patients;
});

Route::post('/', function(Request $request){
    $patient = new Patient;
    $patient->firstname = $request->firstname;
    $patient->lastname = $request->lastname;
    $patient->status = $request->status;
    $patient->save();
    Log::channel("api")->info("POST request - '/api' -".$request->getContent());
    return json_encode(array('status' =>"Item inserted successfully"));
});

Route::put('/',function(Request $request){
    $newPatients = array();
    $patients = $request->input();
    Patient::truncate(); //
    foreach($patients as $p){
        $patient = new Patient;
        $patient->firstname = $p["firstname"];
        $patient->lastname = $p["lastname"];
        $patient->status = $p["status"];
        $patient->save();        
        array_push($newPatients,$patient);
    }
    Log::channel("api")->info("PUT request - '/api' -".$request->getContent());
    return json_encode(array('status' =>"Collection replaced"));
});

Route::delete('/',function(){
    Patient::truncate(); 
    Log::channel("api")->info("DELETE request - '/api'");
    return json_encode(array('status' =>"Collection deleted"));
});

Route::get('/{id}',function($id){
    $patients = Patient::where('id','=',$id)->get(['firstname','lastname', 'status']);
    // return Patient::select('firstname','lastname', 'status')->where('id','=',$id)->get();
    Log::channel("api")->info("GET request - '/api/".$id. "' - ".$patients);
    return $patients;

});

Route::put('/{id}',function(Request $request, int $id){
    $updatePatient = Patient::find($id);
    $updatePatient->firstname = $request->firstname;
    $updatePatient->lastname = $request->lastname;
    $updatePatient->status = $request->status;
    $updatePatient->save();   
    Log::channel("api")->info("PUT request - '/api".$id."' - ".$request->getContent());
    return json_encode(array('status' =>"Item updated"));
});

Route::delete('/{id}',function($id){
    Patient::destroy($id);
    Log::channel("api")->info("DELETE request - '/api".$id."'");
    return json_encode(array('status' =>"Item deleted"));
});




