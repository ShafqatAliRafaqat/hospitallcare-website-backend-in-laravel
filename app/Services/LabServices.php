<?php
namespace App\Services;

use App\Models\Admin\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabServices extends Service {

    public function validate($input){

        $rules = [
            'name'                  => 'required',
            'assistant_name'        => 'sometimes',
            'assistant_phone'       => 'sometimes',
            'lng'                   => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
            'lat'                   => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
            'address'               => 'required|min:5',
            'notes'                 => 'string|nullable',
            'is_active'             => 'nullable',
            'meta_title'            => 'nullable',
            'meta_description'      => 'nullable',
            'url'                   => 'nullable',
            'meta_heading'          => 'nullable',
            'image_alt'             => 'nullable',
        ];
        $this->validateOrAbort($input,$rules);
    }
    public function create($data){
        $this->validate($data);
        $by = 'created_by'; //For Created By
        $labs = Lab::create($this->getSecureInput($data,$by));
        $lab_id     = $labs->id;
            if ($data['diagnostic_id'][0] != NULL) {
                $lab_diagnostic = $this->insertLabDiagnostic($data,$lab_id);
            }
            return $lab_id;
    }
    public function update($data,$id){
        $this->validate($data);
        $by = 'updated_by'; //For Updated By
        $labs = Lab::where('id',$id)->update($this->getSecureInput($data,$by));
            if ($data['diagnostic_id'][0] != NULL) {
                $delete_diagnostics     =   DB::table('lab_diagnostics')->where('lab_id', $id)->delete();
                $lab_diagnostic = $this->insertLabDiagnostic($data,$id);
            }
            return $labs;
    }
    public function getSecureInput($input,$by){
        $data = [
            'name'              =>  $input['name'],
            'assistant_name'    =>  $input['assistant_name'],
            'assistant_phone'   =>  $input['assistant_phone'],
            'lng'               =>  $input['lng'],
            'lat'               =>  $input['lat'],
            'address'           =>  $input['address'],
            'notes'             =>  $input['notes'],
            'meta_title'        =>  $input['meta_title'],
            'meta_description'  =>  $input['meta_description'],
            'url'               =>  $input['url'],
            'meta_heading'      => $input['meta_heading'],
            'image_alt'         => $input['image_alt'],
            'is_active'         =>  isset($input['is_active']) ? $input['is_active'] : NULL,
            $by                 =>  Auth::user()->id, //Created or Updated By
        ];

        return $data;
    }
    public function insertLabDiagnostic($data , $lab_id){

        foreach (array_combine($data['diagnostic_id'], $data['cost']) as $diagnostic_id => $cost) {
            $lab_diagnostic = DB::table('lab_diagnostics')->INSERT([
               'lab_id'         => $lab_id,
               'diagnostic_id'  => $diagnostic_id,
               'cost'           => $cost
            ]);
            }
            return $lab_diagnostic;
    }
}

