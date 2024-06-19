<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Managemnt;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\Datatables;

class ManagementController extends Controller
{
    public function show(Request $request, $any = null, $id = null)
    {
        if ($request->ajax()) {





            $users = Managemnt::query()
                ->select('managements.*', \DB::raw("DATE_FORMAT(managements.created_at ,'%d/%m/%Y') AS created_date"))
                ->where('managements.parent_id', $request->parent_id)
                ->orderBy('managements.created_at', 'desc');



            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {



                    $userName = $row->name;

                    return $userName;
                })

                ->addColumn('email', function ($row) {



                    return $row->email;



                })

                ->addColumn('phone_number', function ($row) {


                    return $row->phone_number;

                })

                ->addColumn('position', function ($row) {

                    if ($row->position == '1') {

                        return 'Manger';

                    }

                    if ($row->position == '2') {
                        return 'Assistance Manager';
                    }

                    if ($row->position == '3') {
                        return 'Team Lead';
                    }

                    if ($row->position == '4') {
                        return 'Coller';
                    }

                })
                ->addColumn('user_details', function ($row) use ($request) {
                    if ($row->position == '1') {
                        $url = url('cm/manager' . '/?id=' . $row->id);
                    }
                    if ($row->position == '2') {
                        $url = url('cm/assist-manager' . '/?id=' . $row->id);

                    }



                    if ($row->position == '3' && $request->second_segemt == 'manager') {
                        $url = url('cm/manager/teamlead' . '/?id=' . $row->id);

                    }


                    if ($row->position == '3' && $request->second_segemt == 'assist-manager') {
                        $url = url('cm/assist-manager/teamlead' . '/?id=' . $row->id);

                    }

                    if ($row->position == '4' && $request->second_segemt == 'manager') {
                        $url = url('cm/manager/teamlead/coller' . '/?id=' . $row->id);

                    }

                    if ($row->position == '4' && $request->second_segemt == 'assist-manager') {
                        $url = url('cm/assist-manager/teamlead/coller' . '/?id=' . $row->id);

                    }



                    $userDetailView = '<a href="' . $url . '" class="ti-user">View</a>';



                    return $userDetailView;



                })


                ->rawColumns(['position', 'user_details'])
                ->make(true);


        }
        return view('management.manager');
    }

    public function cmEmployeesave(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managements',
            'phone_no' => 'required|string|max:20',
            'position' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'sucess' => false,
                'errormessage' => $validator->errors(),


            ], 422);


        }

        $employee = new Managemnt();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->position = $request->position;
        $employee->phone_number = $request->phone_no;



        $employee->parent_id = $request->parent_id;

        $employee->save();


        return response()->json($employee);

    }
}
