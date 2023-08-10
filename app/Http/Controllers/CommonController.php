<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomModel;
use Yajra\DataTables\Facades\Datatables;
use Illuminate\Support\Facades\DB;


class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CustomModel $CustomModel)
    {
        if ($request->ajax()) {
            $data = $CustomModel->getAllUserHobbies();
            $formatted_data = json_decode($data,TRUE); 
            $records = [];
            foreach($formatted_data as $k=>$val){
                if(!empty($val['hobbies'])){
                    $hobbie_id = array_column($val['hobbies'], 'id');
                    $hids = implode(",",$hobbie_id);

                    $hobbie_name = array_column($val['hobbies'], 'hobbie_name');
                    $hnames = implode(",",$hobbie_name);
                    
                    $records[] = (object)[
                                    'user_id' => $val['id'],
                                    'first_name'=> $val['first_name'],
                                    'last_name'=> $val['last_name'],
                                    'hobbies_id' => $hids,
                                    'hobbies_name' => $hnames
                    ];
                }
                
            }
            return Datatables::of($records)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" id="editBtn" onClick="editFunc('.$row->user_id.')">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm"  onClick="deleteFunc('.$row->user_id.')" id="deleteBtn">Delete</a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('user');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'hobbies' => 'required'
        ]);

        $input = $request->all();

        $return = [];
        $return['status'] = true;
        try{
            $records = [];

            if(empty($input['user_id'])){

                $user_id = DB::table('users')->insertGetId([
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
    
              
                foreach($input['hobbies'] as $hobbie_id){
                    $records[] = array(
                        'user_id'=>$user_id,
                        'hobbie_id' => $hobbie_id,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
                $user_hobbies = DB::table('user_hobbies')->insert($records);
                $return['message'] = "Record inserted successfully!!!";

            }else{
                DB::table('users')->where('id', $input['user_id'])->update([
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

               
               $dbHobbyId =  getUserHobbyIds($input['user_id']);
               $deletedHobbies = array_diff($dbHobbyId,$input['hobbies']);
                //Delete Hobby
                DB::table('user_hobbies')->where('user_id',$input['user_id'])->whereIn('hobbie_id',$deletedHobbies)->delete();
    
                foreach($input['hobbies'] as $hobbie_id){
                    $recordExists = DB::table('user_hobbies')->where('user_id',$input['user_id'])
                                        ->where('hobbie_id',$hobbie_id)->count();
                    //Insert hobby if not exists
                    if($recordExists == 0){
                        DB::table('user_hobbies')->insert([
                            'user_id'=>$input['user_id'],
                            'hobbie_id' => $hobbie_id,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                $return['message'] = "Record updated successfully!!!";
            }
            
            DB::commit();
        }catch(Throwable $e){
            DB::rollback();

            $return['message'] = $e->getMessage();
        }
       
        return Response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, CustomModel $CustomModel)
    {
        $input = $request->all();
         $data = $CustomModel->getUserHobbies($input['user_id']);
          $val = json_decode($data,TRUE); 
         
          if(!empty($val['hobbies'])){
            $hobbie_id = array_column($val['hobbies'], 'id');
            $hids = implode(",",$hobbie_id);

            $hobbie_name = array_column($val['hobbies'], 'hobbie_name');
            $hnames = implode(",",$hobbie_name);
          }

          $data = (object)[
            'user_id' => $val['id'],
            'first_name'=> $val['first_name'],
            'last_name'=> $val['last_name'],
            'hobbies_id' => $hids,
            'hobbies_name' => $hnames
        ];
         return Response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        DB::table('user_hobbies')->where('user_id',$input['user_id'])->delete();
        return true;
    }
}
