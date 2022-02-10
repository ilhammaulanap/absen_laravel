<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Epresence;
use App\Models\User;
use Validator;
use Auth;

class AbsenController extends Controller
{
    public function add_absen(Request $request){
        try{
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'tanggal' => 'required',   
                'waktu' => 'required',   
            ]);
            if ($validator->fails()) {
                // return response gagal
                $response = [
                    'status' => false,
                    'code' => 400 ,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ];
                return response()->json($response, 200);
            }
            $user = Auth::user();
            // $request['id_users'] = $user['id'];
            $absen = Epresence::create([
                'id_users' => $user['id'],
                'type' => $request['type'],
                'tanggal' => $request['tanggal'],
                'waktu' => $request['waktu'],
            ]);
            if($absen){
                $response = [
                    'status' => true,
                    'code' => 200,
                    'message' => 'Absen Has been saved',
                    'data' => null,
                    ];
                return response()->json($response, 200);
            }else{
                $response = [
                    'status' => false,
                    'code' => 400,
                    'message' => 'Oops Something Wrong',
                    'data' => null,
                    ];
                return response()->json($response, 200);
            }
        }catch (Throwable $e) {
            $response = [
                'status' => false,
                'code' => 400,
                'message' => 'Oops Something Wrong',
                'data' => null,
                ];
            return response()->json($response, 200);
        }
    }


    public function approve_absen(Request $request){
        $validator = Validator::make($request->all(), [
            'id_absen' => 'required',
            'is_approve' => 'required',   
        ]);
        if ($validator->fails()) {
            // return response gagal
            $response = [
                'status' => false,
                'code' => 400 ,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];
            return response()->json($response, 200);
        }
        // dd($request->all());
        $update = Epresence::where('id',$request['id_absen'])
                            ->update(['is_approve' => $request['is_approve']]);
        if($update){
            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Absen Has been apprrove',
                'data' => null,
                ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'code' => 400,
                'message' => 'Oops Something Wrong',
                'data' => null,
                ];
            return response()->json($response, 200);
        }
    }

    public function data_absen(){
        try{
            $data_tanggal = Epresence::get_tgl_absen();
            
            $absen = [];
            foreach($data_tanggal as $tanggal){
                // dd($tanggal);
                $data_user = User::get();
                foreach($data_user as $user){
                    $status_masuk = null;
                    $status_pulang = null;
                    // dd($tanggal['tanggal']);
                    $data_absen_masuk = Epresence::where('id_users',$user['id'])
                                                ->where('tanggal',$tanggal['tanggal'])
                                                ->where('type','IN')->first();
                    $data_absen_keluar = Epresence::where('id_users',$user['id'])
                                                ->where('tanggal',$tanggal['tanggal'])
                                                ->where('type','OUT')->first();
                    if($data_absen_masuk != null){
                        if($data_absen_masuk['is_approve'] == 1) {
                            $status_masuk = 'APPROVE';
                        }else if($data_absen_masuk['is_approve'] == 0) {
                            $status_masuk = 'REJECT';
                        }else{
                            $status_masuk = null;
                        }
                    }
                    if($data_absen_keluar != null){
                        if($data_absen_keluar['is_approve'] == 1) {
                            $status_pulang = 'APPROVE';
                        }else if($data_absen_keluar['is_approve'] == 0) {
                            $status_pulang = 'REJECT';
                        }else{
                            $status_pulang = null;
                        }
                    }
                    // dd($data_absen_masuk);
                    $data_absen_user = [
                        'id_user' => $user['id'],
                        'nama_user' => $user['name'],
                        'tanggal' => $tanggal['tanggal'],
                        'waktu_masuk' => $data_absen_masuk['waktu'] ?? null,
                        'waktu_pulang' => $data_absen_keluar['waktu'] ?? null,
                        'status_masuk' => $status_masuk,
                        'status_pulang' => $status_pulang,
                    ];
                    // dd($data_absen_user);
                    array_push($absen,$data_absen_user);
                }
            }
            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Absen Has been apprrove',
                'data' => $absen,
                ];
            return response()->json($response, 200);
        }catch (Throwable $e) {
            $response = [
                'status' => false,
                'code' => 400,
                'message' => 'Oops Something Wrong',
                'data' => null,
                ];
            return response()->json($response, 200);
        }
    }
}
