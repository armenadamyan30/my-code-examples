<?php

namespace App\Http\Controllers;

use App\ActionLog;
use App\AllowSameCodeSelection;
use App\GlobalRuleMonitor;
use App\Instruction;
use App\Section;
use App\SectionMonitor;
use App\SectionPreviousSelection;
use App\Selection;
use App\Student;
use App\StudentSelection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use LucaDegasperi\OAuth2Server\Authorizer;
use App\LogType;

class SectionsController extends BaseAdminController
{
    public function __construct(Authorizer $authorizer)
    {
        parent::__construct($authorizer);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $this->addLog(array(
            "log_type_id" => LogType::INFO,
            "message"     => "Get All Sections",
            "status_code" => Response::HTTP_OK
        ));

        return Section::all();
    }

    /**
     * Display a listing of the resource.
     * @param  int  $year_level_id
     * @return Response
     */
    public function get_by_year_id($year_level_id) {
        $this->addLog(array(
            "log_type_id" => LogType::INFO,
            "message"     => "Get All Sections By YearID",
            "status_code" => Response::HTTP_OK
        ));

        return Section::where('year_level_id', $year_level_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {

        $response['status'] = false;

        try{
            $statusCode = Response::HTTP_CREATED;

            $year_level_id = isset($request['year_level_id']) ? $request['year_level_id'] : null;

            $validate_rules = array(
                'year_level_id'    => 'required|integer|exists:year_levels,id',
                'title'            => 'required|max:32',
                'report_name'      => 'required|max:32',
                'min_selections'   => 'integer|between:0,999999',
                'max_selections'   => 'integer|between:0,999999',
                'min_units'        => 'integer|between:0,999999',
                'max_units'        => 'integer|between:0,999999',
                'show_on_reports'  => 'integer|between:0,1',
                'allow_same_code'  => 'integer|between:0,1',
                'order_number'     => 'integer',
            );

            $validator = Validator::make($request->all(), $validate_rules);

            if ($validator->fails()) {
                $statusCode = Response::HTTP_BAD_REQUEST;
                $response['errors'][] =  $validator->errors();
                return \Response::json($response, $statusCode);
            }

            //sections table
            $section = new Section();
            $section->year_level_id = $year_level_id;
            $section->title = $request['title'];
            $section->description = isset($request['description']) ? $request['description'] : null;
            $section->report_name = isset($request['report_name']) ? $request['report_name'] : isset($request['title']) ? $request['title'] : null;
            $section->show_on_reports = isset($request['show_on_reports']) ? $request['show_on_reports'] : 1;
            $section->min_selections = isset($request['min_selections']) && trim($request['min_selections']) != ''  ? $request['min_selections'] : null;
            $section->max_selections = isset($request['max_selections']) && trim($request['max_selections']) != '' ? $request['max_selections'] : null;
            $section->min_units = isset($request['min_units']) && trim($request['min_units']) != '' ? $request['min_units'] : null;
            $section->max_units = isset($request['max_units']) && trim($request['max_units']) != '' ? $request['max_units'] : null;
            $section->allow_same_code = isset($request['allow_same_code']) ? $request['allow_same_code'] : 0;

            $order_number = Section::where('year_level_id', '=', $year_level_id)->max('order_number') + 1;
            $section->order_number = isset($request['order_number']) ? $request['order_number'] : $order_number;

            if($section->save()){
                $response['status'] = true;
                $response['inserted_id'] = $section->id;
                $year_level = $section->year;
                $this->addLog(array(
                    "message"     => 'Success added new > '.$year_level->title.' > Section '. $section->report_name,
                    "status_code" => $statusCode,
                    "show_status" => ActionLog::SHOW
                ));
//                $student_user_ids = Student::where('year_level_id', '=', $year_level_id)->lists('user_id')->toArray();
//                if(!empty($student_user_ids)){
//                    foreach ($student_user_ids as $student_user_id){
//                        //update status of section
//                        SectionMonitor::updateSectionStatus($section->id, $student_user_id, $year_level_id);
//                        GlobalRuleMonitor::updateGlobalRulesMonitorStatuses($year_level_id, $student_user_id);
//                    }
//                }


            }else{
                $statusCode = Response::HTTP_BAD_REQUEST;
                $response['status'] = false;
                $this->addLog(array(
                    "log_type_id" => LogType::ERROR,
                    "message"     => "Something went wrong when adding new Section " .$request['title'],
                    "status_code" => $statusCode
                ));
            }
        }catch (\Exception $e){
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response['errors'][] = $e->getMessage();
            $this->addLog(array(
                "log_type_id" => LogType::ERROR,
                "message"     => $e->getMessage(),
                "status_code" => $statusCode
            ));
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    protected function update_student_selects(Section $section){
        $response = [];
        $min_selections = $section->min_selections;
        $max_selections = $section->max_selections;
        $min_units = $section->min_units;
        $max_units = $section->max_units;

        $year_level_id = $section->year_level_id;

        $selection_ids = [];
        $not_null_units_sel_ids = [];
        $check_data = [];
        if(!$section->selections->isEmpty()){
            foreach ($section->selections as $selection){
                $selection_ids[] = $selection->id;
                if($selection->selection_matrix->units !== null){
                    if(!in_array($selection->id, $not_null_units_sel_ids)){
                        $not_null_units_sel_ids[] = $selection->id;
                    }
                }
                if(!$selection->student_selected->isEmpty()){
                    $check_data[$selection->id]['units'] = $selection->selection_matrix->units;
                    $check_data[$selection->id]['student_selected'] = $selection->student_selected;
                }
            }
        }
        $student_user_ids = Student::where('year_level_id', '=', $year_level_id)->lists('user_id')->toArray();


        if($max_selections !== 0){
            if(!empty($student_user_ids)){
                foreach ($student_user_ids as $student_user_id){
                    Selection::enableSelections($student_user_id, $selection_ids);
                }
            }
        }
        if($max_units !== 0){
            if(!empty($student_user_ids)){
                foreach ($student_user_ids as $student_user_id){
                    Selection::enableSelections($student_user_id, $not_null_units_sel_ids);
                }
            }
        }

        $user_selects = [];
        if(!empty($check_data)){
            foreach ($check_data as $selection_id=>$item){
                $units = $item['units'];
                foreach ($item['student_selected'] as $student_selected){
                    $user_selects[$student_selected->student_user_id][] = [
                        'section_id' => $student_selected->section_id,
                        'selection_id' => $selection_id,
                        'units' => $units,
                        'updated_at' => strtotime($student_selected->updated_at),
                        'id' => $student_selected->id,
                    ];
                }
            }
        }
        $remove_selected = [];
        if(!empty($user_selects)){
            foreach ($user_selects as $student_user_id => $user_select_items){

//                $response['$user_select_items1'] = $user_select_items;

                usort($user_select_items, function  ($a, $b) {
                    return strcmp($a["updated_at"], $b["updated_at"]);
                });
//                $response['$user_select_items2'] = $user_select_items;

                if($section->allow_same_code == 1){
                    //important
                    AllowSameCodeSelection::where('student_user_id', '=', $student_user_id)
                                            ->whereIn('selection_id', $selection_ids)->delete();
                }

                $count_selected = count($user_select_items);
                $count_units = 0;
                foreach ($user_select_items as $user_select_item){
                    if(!is_null($user_select_item['units'])){
                        $count_units += $user_select_item['units'];
                    }
                }

                $status = false;

                if($max_selections && $max_selections >= $count_selected){
                    $status = true;
                }else if($max_selections !== null){
                    $diff = $count_selected - $max_selections;
//                    $response['$diff'][] = $diff;
                    if($diff){
                        for($i=0; $i < $diff; $i++){
                            $index = $count_selected - $i;
                            $remove_selected[] = [
                                'student_user_id'=> $student_user_id,
                                'selection_id'=> $user_select_items[$index-1]['selection_id'],
                                'section_id'=> $user_select_items[$index-1]['section_id']
                            ];
                        }
                    }


                }

                //check units
                if($count_units !== 0){

                    if($max_units && $max_units >= $count_selected){
                        $status = true;
                    }else if($max_units !== null){

                        $count_units = 0;
                        foreach ($user_select_items as $user_select_item){
                            if(!is_null($user_select_item['units'])){
                                $count_units += $user_select_item['units'];
                                if($max_units <= $count_units){
                                    $remove_selected[] = [
                                        'student_user_id' => $student_user_id,
                                        'selection_id' => $user_select_item['selection_id'],
                                        'section_id' => $user_select_item['section_id']
                                    ];
                                }
                            }
                        }

                    }
                }
                $response['status'][] = $status;
            }

            if(!empty($remove_selected)){
                foreach ($remove_selected as $remove_item){
                    if (
                        StudentSelection::where([
                            ['student_user_id', '=', $remove_item['student_user_id']],
                            ['selection_id', '=', $remove_item['selection_id']],
                        ])->delete()
                    ) {
                        //if no any previous selection need delete all selections from current section
                        Selection::previousSectionConditions($remove_item['selection_id'], $remove_item['student_user_id'], $year_level_id);

//                        if($section->allow_same_code == 0){
                        //important
                        Selection::enableTheSameCode($remove_item['section_id'], $remove_item['selection_id'], $remove_item['student_user_id']);
//                        }


                        Selection::enable_selections($remove_item['selection_id'], $remove_item['student_user_id']);
//                    $this->enable_selections($selection_id, $student_user_id);
                        Selection::enable_child_selections($remove_item['selection_id'], $remove_item['student_user_id']);
//                    $response['enable_selections'] = $this->enable_child_selections($selection_id, $student_user_id);

                        //selections of Section Instruction enable disable
                        Selection::enableDisableSelectionsFromInstructionSection($remove_item['selection_id'], $remove_item['student_user_id']);

                        //enable disable by Global Rules
                        Selection::enableDisableSelectionsByGlobalRules($year_level_id, $remove_item['student_user_id']);

                        //enable disable by Capacities
                        Selection::enableDisableSelectionsByCapacities($year_level_id);


                        //enable disable by Tick Flick
                        Selection::enableDisableSelectionsByTickFlick($remove_item['student_user_id']);

                        Selection::enableDisableSelections($remove_item['student_user_id']);

                        //update status of section
                        SectionMonitor::updateSectionStatus($remove_item['section_id'], $remove_item['student_user_id'], $year_level_id);

                        GlobalRuleMonitor::updateGlobalRulesMonitorStatuses($year_level_id, $remove_item['student_user_id']);
                    }
                }
            }

            //Important for enable disable need selections
            $student_selects = StudentSelection::where([
                ['section_id', '=', $section->id]
            ])->get();

            if(!$student_selects->isEmpty()){
                $user_data = [];

                foreach ($student_selects as $student_select){
                    $user_data[$student_select->student_user_id][] = $student_select;
                }

                foreach ($user_data as $student_user_id=>$items){
                    if(!empty($items)){
                        $instruction_ids = [];
                        foreach ($items as $item){
                            if($section->allow_same_code == 0){
                                //important
                                Selection::enableDisableTheSameCode($item->section_id, $item->selection_id, $item->student_user_id);
                            }

                            if(!in_array($item->instruction_id, $instruction_ids)){
                                $instruction_ids[] = $item->instruction_id;
                                $data = [
                                    'year_level_id' => $item->year_level_id,
                                    'section_id' => $item->section_id,
                                    'instruction_id' => $item->instruction_id,
                                    'selection_id' => $item->selection_id,
                                    'student_user_id' => $item->student_user_id,
                                ];
                                $this->updateDisableEnableSelections($data);
                            }
                        }
                    }
                }
            }
        }

        if($max_selections === 0){ //TODO if max_selections = 0
//            $current_section_selection_ids = $section->selections()->lists('selections.id')->toArray();
            $current_section_selection_ids = $selection_ids;
            foreach ($student_user_ids as $student_user_id){
                Selection::disableSelections($student_user_id, $current_section_selection_ids);
            }
        }

        if($max_units === 0){ //TODO if max_units = 0
            if (!empty($not_null_units_sel_ids)) {
                foreach ($student_user_ids as $student_user_id){
                    Selection::disableSelections($student_user_id, $not_null_units_sel_ids);
                }
            }
        }

        $response['updateSectionStatus1111'] = SectionMonitor::updateSectionStatusWhenSettingsUpdate($section->id, $year_level_id,true);
        return $response;
    }

    protected function updateDisableEnableSelections($data = array()){

        $year_level_id = $data['year_level_id'];
        $selection_id = $data['selection_id'];
        $student_user_id = $data['student_user_id'];
        $section_id = $data['section_id'];
        $instruction_id = $data['instruction_id'];

        $instruction = Instruction::find($instruction_id);

        $select_type = $this->getSelectType($instruction);

        $data_auto = [
            'year_level_id' => $year_level_id,
            'student_user_id' => $student_user_id,
            'section_id' => $section_id,
            'instruction_id' => $instruction_id,
            'selection_id' => $selection_id,
            'select_type'=>$select_type
        ];
        //Important
        Selection::enableDisableTheSameCode($section_id, $selection_id, $student_user_id);
        /*$auto_selects_status = */Selection::autoSelectsDeselects($data_auto);


//                /*$response['auto_selects_status'] = */isset($auto_selects_status['status']) ? $auto_selects_status['status'] : false;

//                if(!empty($old_selection_id)){
//                    Selection::enable_child_selections($old_selection_id, $student_user_id);
//                }

        //selections of Section Instruction enable disable
        Selection::enableDisableSelectionsFromInstructionSection($selection_id, $student_user_id);

        //enable disable by Global Rules
        Selection::enableDisableSelectionsByGlobalRules($year_level_id, $student_user_id);

        //enable disable by Capacities
        Selection::enableDisableSelectionsByCapacities($year_level_id);


        //enable disable by Tick Flick
        Selection::enableDisableSelectionsByTickFlick($student_user_id);

        Selection::enableDisableSelections($student_user_id);
    }

    protected function getSelectType(Instruction $instruction){
        $result = '';
        $selection_method = $instruction->selection_method;

        switch ($selection_method){
            case 'radio':
                $result = 'radio_dropdown';
                break;
            case 'dropdown':
                if($instruction->max_selections != 1){
                    $result = 'radio_dropdown';
                }else{
                    $result = 'checkbox_multi_dropdown';
                }
                break;
            case 'checkbox':
                $result = 'checkbox_multi_dropdown';
                break;
            case 'text':
                $result = 'text';
                break;

        }

        return $result;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {

        $response['status'] = false;

        $response['warnings'] = [];


        try{
            $statusCode = Response::HTTP_OK;

            $validate_rules = array(
                'year_level_id'    => 'integer|exists:year_levels,id',
                'title'            => 'max:32',
                'report_name'      => 'max:32',
                'min_selections'   => 'integer|between:0,999999',
                'max_selections'   => 'integer|between:0,999999',
                'min_units'        => 'integer|between:0,999999',
                'max_units'        => 'integer|between:0,999999',
                'show_on_reports'  => 'integer|between:0,1',
                'allow_same_code'  => 'integer|between:0,1',
                'order_number'     => 'integer',
            );

            $validator = Validator::make($request->all(), $validate_rules);

            if ($validator->fails()) {
                $statusCode = Response::HTTP_BAD_REQUEST;
                $response['errors'][] =  $validator->errors();
                return \Response::json($response, $statusCode);
            }

            $section = Section::find($id);
            $original = null;
            if(!empty($section)){
                $original = $section->getOriginal();

                $student_selections = StudentSelection::where('student_selections.section_id','=',$id)
                    ->join('student_selections as second_student_selections', function ($join)use($id){
                        $join->on('student_selections.student_user_id','=','second_student_selections.student_user_id');
                        $join->where('second_student_selections.section_id','=',$id);
                    })
                    ->join('selections',function ($join){
                        $join->on('selections.id','=','student_selections.selection_id');
                    })
                    ->join('selection_matrices',function ($join){
                        $join->on('selection_matrices.id','=','selections.selection_matrix_id');
                    })
                    ->distinct('student_selections.id')
                    ->get([
                        'student_selections.id',
                        'student_selections.student_user_id',
                        'selection_matrices.id as sm_id',
                    ]);
                $has_same_code_selections = false;
                $students = [];
                if(!$student_selections->isEmpty()){
                    foreach ($student_selections as $selection){
                        $students[$selection->student_user_id][$selection->sm_id][] = $selection;
                        if (count($students[$selection->student_user_id][$selection->sm_id]) > 1) {
                            $has_same_code_selections = true;
                            break;
                        }
                    }
                }
                //check min-max start
                $sec_min_selections = isset($request['min_selections']) && trim($request['min_selections']) != '' ? $request['min_selections'] : $section->min_selections;
                $sec_max_selections = isset($request['max_selections']) && trim($request['max_selections']) != '' ? $request['max_selections'] : $section->max_selections;
                $sec_min_units = isset($request['min_units']) && trim($request['min_units']) != '' ? $request['min_units'] : $section->min_units;
                $sec_max_units = isset($request['max_units']) && trim($request['max_units']) != '' ? $request['max_units'] : $section->max_units;
//
                $sum_min_max = Instruction::where('section_id', '=', $id)->select(
                    \DB::raw(
                        'SUM(min_selections) AS `sum_min_selections`,
                         SUM(max_selections) AS `sum_max_selections`,
                         SUM(min_units)      AS `sum_min_units`,
                         SUM(max_units)      AS `sum_max_units`'
                    )
                )->first();

                $response['$sum_min_max'] = $sum_min_max;


//
                $sum_min_selections = isset($sum_min_max->sum_min_selections) ? $sum_min_max->sum_min_selections : null;
                $sum_max_selections = isset($sum_min_max->sum_max_selections) ? $sum_min_max->sum_max_selections : null;
                $sum_min_units = isset($sum_min_max->sum_min_units) ? $sum_min_max->sum_min_units : null;
                $sum_max_units = isset($sum_min_max->sum_max_units) ? $sum_min_max->sum_max_units : null;
                //check sec_min <= instruction_sum_max
//                $response['errors'] = [];
                if($sec_max_selections !== null){
                    if($sec_max_selections < $sum_min_selections){
                        $response['errors'][] = "Max selections of Section must be bigger or equal to sum of instructions' min selections.";
                    }
                }
                //check sec_max >= instruction_sum_min
                if($sec_min_selections){
                    $max_selection_null = Instruction::where(array(
                        array('section_id', '=', $id),
                        array('max_selections', '=', null)
                    ))->get();
                    if($max_selection_null->isEmpty()
                        && $sec_min_selections > $sum_max_selections && $sum_max_selections !== null){
                        $response['errors'][] = "Min selections of Section must be lower or equal to sum of instructions' max selections.";
                    }
                }

                //check sec_min_units <= instruction_sum_max_units
                if($sec_max_units !== null){
                    if($sec_max_units < $sum_min_units){
                        $response['errors'][] = "Max units of Section must be bigger or equal to sum of instructions' min units.";
                    }
                }
                //check sec_max_units >= instruction_sum_min_units
                if($sec_min_units){
                    $max_unit_null = Instruction::where(array(
                        array('section_id', '=', $id),
                        array('max_units', '=', null)
                    ))->get();
                    if($max_unit_null->isEmpty()
                        && $sec_min_units > $sum_max_units && $sum_max_units !== null){

                        $response['errors'][] = "Min units of Section must be lower or equal to sum of instructions' max units.";
                    }
                }


                if(!empty($response['errors'])){
                    $statusCode = Response::HTTP_BAD_REQUEST;
                    return \Response::json($response, $statusCode);
                }
                //check min-max end

                if(isset($request['year_level_id'])){
                    $section->year_level_id = $request['year_level_id'];
                }
                if(isset($request['title'])){
                    $section->title = $request['title'];
                }
                if(isset($request['description'])){
                    $section->description = trim($request['description']) != '' ? $request['description'] : null;
                }
                if(isset($request['report_name'])){
                    $section->report_name = $request['report_name'];
                }
                if(isset($request['show_on_reports'])){
                    $section->show_on_reports = $request['show_on_reports'];
                }
                if(isset($request['min_selections'])){
                    $section->min_selections = trim($request['min_selections']) != '' ? $request['min_selections'] : null;
                }
                if(isset($request['max_selections'])){
                    $section->max_selections = trim($request['max_selections']) != '' ? $request['max_selections'] : null;
                }
                if(isset($request['min_units'])){
                    $section->min_units = trim($request['min_units']) != '' ? $request['min_units'] : null;
                }
                if(isset($request['max_units'])){
                    $section->max_units = trim($request['max_units']) != '' ? $request['max_units'] : null;
                }
                if(isset($request['allow_same_code'])){
                    if($request['allow_same_code'] == 0 && $has_same_code_selections) {
                        $response['warnings'][] = 'Allow same code selections connot be "No" because there are selections with same code selected more than 1';
                        $section->allow_same_code = 1;
                    } else {
                        $section->allow_same_code = $request['allow_same_code'];
                    }

                }
                if(isset($request['order_number'])){
                    $section->order_number = $request['order_number'];
                }
//                $response['section'] = $section;
//                return $response;
                $changes = $section->isDirty() ? $section->getDirty() : false;
                if ($section->save()) {
                    $year_level = $section->year;
                    $response['changes'] = $changes;
                    $msg = $this->get_log_msg($changes, $original, $year_level->title, $section->report_name);
                    if($msg != ''){
                        $this->addLog(array(
                            "log_type_id" => LogType::INFO,
                            "message"     => $msg,
                            "status_code" => Response::HTTP_OK,
                            "show_status" => ActionLog::SHOW
                        ));
                    }
                    $response['status'] = true;
                    $response['update_student_selects'] = $this->update_student_selects($section);
                }
            }else{
                $statusCode = Response::HTTP_BAD_REQUEST;
                $err_msg = "Invalid Section Id ".$id." when updating the section.";
                $response['errors'][] = $err_msg;
                $this->addLog(array(
                    "log_type_id" => LogType::ERROR,
                    "message"     => $err_msg,
                    "status_code" => $statusCode,
                    "show_section" => ActionLog::SHOW
                ));
            }
        }catch (\Exception $e){
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response['errors'][] = $e->getMessage();
            $this->addLog(array(
                "log_type_id" => LogType::ERROR,
                "message"     => $e->getMessage(),
                "status_code" => $statusCode
            ));
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {

        $response['status'] = false;

        try{
            $statusCode = Response::HTTP_OK;

            $section = Section::find($id);

            if(!empty($section)){
               $year_level = $section->year;
               $report_name = $section->report_name;
               if($section->delete()){
                   $response['status'] = true;
                   $this->addLog(array(
                       "log_type_id" => LogType::INFO,
                       "message"     => $year_level->title. " > Section " . $report_name . " has been deleted successfully.",
                       "status_code" => $statusCode,
                       "show_status" => ActionLog::SHOW,
                   ));

                   $section_ids = Section::where('year_level_id', '=', $year_level->id)
                                           ->orderBy('order_number')->lists('id')->toArray();
                   if(!empty($section_ids)){
                       Section::update_order_numbers($section_ids);
                   }
               }
            }else{
                $response['status'] = false;
                $this->addLog(array(
                    "log_type_id" => LogType::ERROR,
                    "message"     => "Invalid Section ID " . $id . " and cannot delete Section -> .",
                    "status_code" => $statusCode,
                    "show_status" => ActionLog::SHOW,
                ));
            }

        }catch (\Exception $e){
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response['errors'][] = $e->getMessage();
            $this->addLog(array(
                "log_type_id" => LogType::ERROR,
                "message"     => $e->getMessage(),
                "status_code" => $statusCode
            ));
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * Display a listing of the resource.
     * @param  int  $year_level_id
     * @return Response
     */
    public function get_by_year_id_with_joins($year_level_id) {
//        $this->addLog(array(
//            "log_type_id" => LogType::INFO,
//            "message"     => "Get All Sections By YearID With Joins",
//            "status_code" => Response::HTTP_OK
//        ));

        return Section::where('year_level_id', $year_level_id)->orderBy('order_number', 'asc')->with('student_selections')->with(array('instructions'=>function($query){
            $query->with('student_selections');
            $query->with(array('selections'=>function($q){
                $q->with('selection_matrix');
                $q->with('student_selected');
                $q->orderBy('order_number', 'asc');
            }));
            $query->orderBy('order_number', 'asc');
        }))->get();

//        return Section::where('year_level_id', $year_level_id)->with('selections')->get();
    }

    /**
     * Display a listing of the resource.
     * @param  int  $year_level_id
     * @return Response
     */
    public function get_by_year_id_with_global_rules($year_level_id) {
        $this->addLog(array(
            "log_type_id" => LogType::INFO,
            "message"     => "Get All Sections By YearID With Joins",
            "status_code" => Response::HTTP_OK
        ));

        return Section::where('year_level_id', $year_level_id)->orderBy('order_number', 'asc')->with(array('instructions'=>function($query){
            $query->with(array('selections'=>function($q){
                $q->with(array('selection_matrix'=>function($q){
                    $q->with('global_rules');
                }));
                $q->orderBy('order_number', 'asc');
            }));
            $query->orderBy('order_number', 'asc');
        }))->get();

//        return Section::where('year_level_id', $year_level_id)->with('selections')->get();
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return Response
     */
    public function get_previous_selections($id) {
        $this->addLog(array(
            "log_type_id" => LogType::INFO,
            "message"     => "Get All Previous Selections By Section Id",
            "status_code" => Response::HTTP_OK
        ));

        $current_section = Section::find($id);
        if(empty($current_section)){
            return [];
        }
        $year_level_id = $current_section->year_level_id;
        $order_number = $current_section->order_number;

        return Section::where('year_level_id', $year_level_id)->orderBy('order_number', 'asc')->with(array('instructions'=>function($query){
            $query->with(array('selections'=>function($q){
                $q->with('selection_matrix');
                $q->orderBy('order_number', 'asc');
            }));
            $query->orderBy('order_number', 'asc');
        }))->where('order_number' , '<', $order_number)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store_previous_selection(Request  $request){
        $response['status'] = false;

        try{
            $statusCode = Response::HTTP_CREATED;

            $section_id = isset($request['section_id']) ? $request['section_id'] : null;
            $selection_id = isset($request['selection_id']) ? $request['selection_id'] : null;

            //check is exist selection from this section in student selects table
            $check_student_selection = StudentSelection::where('section_id', '=', $section_id)->first();
            if(!empty($check_student_selection)){
                $statusCode = Response::HTTP_BAD_REQUEST;
                $response['errors'][] = "Selection process started and some students have been selected selections from current Section.You cannot add previous selections";
                return $response;
            }

            $validate_rules = array(
                'section_id'    => 'required|integer|exists:sections,id',
                'selection_id'    => 'required|integer|exists:selections,id|unique_with:section_previous_selections,section_id',
            );
            $messages = [
               'selection_id.unique_with' => 'The selection is already set as required for this section'
            ];

            $validator = Validator::make($request->all(), $validate_rules, $messages);

            if ($validator->fails()) {
                $statusCode = Response::HTTP_BAD_REQUEST;
                $response['errors'][] =  $validator->errors();
                return $response;
            }

            //section_previous_selections table
            $section_previous_selection = new SectionPreviousSelection();
            $section_previous_selection->section_id = $section_id;
            $section_previous_selection->selection_id = $selection_id;
            if($section_previous_selection->save()){

                $section = $section_previous_selection->section;
                $year_level = $section->year;

                $selection = $section_previous_selection->selection;
                $selection_matrix = $selection->selection_matrix;
                $sel_instruction = $selection->instruction;
                $sel_section = $sel_instruction->section;

                $msg = $year_level->title .' > Section '.$section->report_name." added new Previous Selection " . " > Section ". $sel_section->report_name . " > Instruction ". $sel_instruction->report_name . " > Selection ". $selection_matrix->course_code . " - " .$selection_matrix->course_name;

                $this->addLog(array(
                    "log_type_id" => LogType::INFO,
                    "message"     => $msg,
                    "status_code" => Response::HTTP_OK,
                    "show_status" => ActionLog::SHOW
                ));

                $response['status'] = true;
                $response['store'] = SectionMonitor::updateSectionStatusForStudentByPreviousSelections($section_previous_selection->section_id);
            }else{
                $statusCode = Response::HTTP_BAD_REQUEST;
                $response['status'] = false;
                $this->addLog(array(
                    "log_type_id" => LogType::ERROR,
                    "message"     => "Something went wrong when adding new Previous Selection.",
                    "status_code" => $statusCode
                ));
            }
        }catch (\Exception $e){
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response['errors'][] = $e->getMessage();
            $this->addLog(array(
                "log_type_id" => LogType::ERROR,
                "message"     => $e->getMessage(),
                "status_code" => $statusCode
            ));
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function destroy_previous_selection(Request $request, $id) {

        $response['status'] = false;

        try{
            $statusCode = Response::HTTP_OK;


            $section_previous_selection = SectionPreviousSelection::find($id);

            if(!empty($section_previous_selection)){


                $section = $section_previous_selection->section;
                $year_level = $section->year;

                $selection = $section_previous_selection->selection;
                $selection_matrix = $selection->selection_matrix;
                $sel_instruction = $selection->instruction;
                $sel_section = $sel_instruction->section;

                $section_id = $section_previous_selection->section_id;

                if ($section_previous_selection->delete()) {
                    $msg = $year_level->title .' > Section '.$section->report_name." deleted Previous Selection " . " > Section ". $sel_section->report_name . " > Instruction ". $sel_instruction->report_name . " > Selection ". $selection_matrix->course_code . " - " .$selection_matrix->course_name;

                    $response['status'] = true;
                    $this->addLog(array(
                        "log_type_id" => LogType::INFO,
                        "message"     => $msg,
                        "status_code" => $statusCode,
                        "show_status" => ActionLog::SHOW,
                    ));
                    $response['destroy'] = SectionMonitor::updateSectionStatusForStudentByPreviousSelections($section_id);
                }
            }else{ //
                $statusCode = Response::HTTP_BAD_REQUEST;
                $msg = "Invalid Previous Selection Id " . $id;

                $response['status'] = false;
                $this->addLog(array(
                    "log_type_id" => LogType::ERROR,
                    "message"     => $msg,
                    "status_code" => $statusCode,
                    "show_status" => ActionLog::SHOW,
                ));
            }
        }catch (\Exception $e){
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response['errors'][] = $e->getMessage();
            $this->addLog(array(
                "log_type_id" => LogType::ERROR,
                "message"     => $e->getMessage(),
                "status_code" => $statusCode
            ));
        }finally{
            return \Response::json($response, $statusCode);
        }
    }


    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return Response
     */
    public function get_previous_selections_by_id($id) {
        $this->addLog(array(
            "log_type_id" => LogType::INFO,
            "message"     => "Get All Previous Sections By Id",
            "status_code" => Response::HTTP_OK
        ));

        return Section::where('id', $id)->with(array('previous_selections' => function($query){
            $query
                ->with('selection_matrix')
                ->join('instructions', 'selections.instruction_id', '=', 'instructions.id')
                ->join('sections', 'instructions.section_id', '=', 'sections.id')
//                ->orderBy('instructions.order_number', 'asc')
                ->get([
                    'section_previous_selections.id as sps_id',
                    'sections.id as section_id',
                    'sections.title as section_title',
                    'sections.report_name as section_report_name',
                    'instructions.id as instruction_id',
                    'instructions.title as instruction_title',
                    'instructions.report_name as instruction_report_name',
                    'selections.*',
                ]);
        }))->first();
    }

    /**
     * Display a listing of the resource.
     * @param  Request  $request
     * @return Response
     */
    public function update_order_numbers(Request  $request) {

        $this->addLog(array(
            "log_type_id" => LogType::INFO,
            "message"     => "Get Update Sections Order Numbers By Ids",
            "status_code" => Response::HTTP_OK
        ));
        $ids = isset($request['ids']) ? $request['ids'] : null;

        if(empty($ids)){
            $response['status'] = false;
            return \Response::json($response, Response::HTTP_BAD_REQUEST);
        }

        $response['status'] = Section::update_order_numbers($ids);
        if($response['status']){
            $statusCode = Response::HTTP_OK;

            SectionPreviousSelection::where('section_id', '=', $ids[0])->delete();
            unset($ids[0]);
            foreach ($ids as $k=>$section_id){
                $sectionPS_s = SectionPreviousSelection::where('section_id', '=', $section_id)->get();
                $current_section = Section::find($section_id);
                if(!empty($sectionPS_s)){
                    foreach ($sectionPS_s as $sectionPS){

                        $sel_id = $sectionPS->selection_id;
                        $selection = Selection::get_parent_section_by_id($sel_id);
                        if($selection){
                            $other_section = $selection->instruction->section;

                            $os_ids = Section::where('id','=', $other_section->id)->with('selections')->first();
                            if(!empty($os_ids->selections)){
                                $other_selection_ids = $os_ids->selections->lists('id');
                            }else{
                                $other_selection_ids = [];
                            }

                            if($current_section->order_number < $other_section->order_number){
                                SectionPreviousSelection::where('section_id', '=', $current_section->id)
                                    ->whereIn('selection_id', $other_selection_ids)
                                    ->delete();
                            }
                        }
                    }
                }
            }


        }else{
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        return \Response::json($response, $statusCode);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $year_level_id
     * @return Response
     */
    public function get_for_global_rules_by_year_id($year_level_id) {
        return Section::where('year_level_id', $year_level_id)->orderBy('order_number', 'asc')
            ->has('instructions')
            ->with(array('instructions'=>function($query){
                $query->has('selections');
                $query->with(array('selections'=>function($q){
                    $q->with('selection_matrix');
                    $q->orderBy('order_number', 'asc');
                }));
                $query->orderBy('order_number', 'asc');
        }))->get();

    }

    /**
     * Display a listing of the resource.
     * @param  int  $year_level_id
     * @param  int  $selection_matrix_id
     * @return Response
     */
    public function get_selections_by_year_id_selection_matrix_id($year_level_id, $selection_matrix_id) {
        $sections = Section::where('year_level_id', $year_level_id)->orderBy('order_number', 'asc')

            ->with(array('instructions'=>function($query) use($selection_matrix_id){
                $query->has('selections');
                $query->with(array('selections'=>function($q) use($selection_matrix_id){
                    $q->where('selection_matrix_id', '=', $selection_matrix_id);
                    $q->with('selection_matrix');
                    $q->with('capacities');
                    $q->orderBy('order_number', 'asc');
                }));
                $query->orderBy('order_number', 'asc');

            }))
            ->has('instructions')
            ->get();
        if(!$sections->isEmpty()){
            foreach ($sections as $k=>$section){
                $section->has_sel = false;

                foreach ($section->instructions as $k1 => $instruction){
                    $instruction->sel_cnt = count($instruction->selections);
                    if(count($instruction->selections) > 0){
                        $section->has_sel = true;
                    }else{
                        unset($section->instructions[$k1]);
                    }
                }
                if(!$section->has_sel) {
                    unset($sections[$k]);
                }
            }
        }
        return $sections;
    }

    /**
     * Display a listing of the resource.
     * @param  int  $year_level_id
     * @return Response
     */
    public function get_by_year_id_with_global_rules_capacities($year_level_id) {

        return Section::where('year_level_id', $year_level_id)
            ->orderBy('order_number', 'asc')
            ->with(array('instructions'=>function($query){
                $query->with(array('selections'=>function($q){
                    $q->with(array('selection_matrix'=>function($q2){
                        $q2->with('global_rules');
                    }));
                    $q->with('capacities');
                    $q->orderBy('order_number', 'asc');
                }));
                $query->orderBy('order_number', 'asc');
        }))->get();
    }

    protected function get_log_msg($changes, $original, $year_level_title, $report_name){
        $msg = '';
        if($changes){
            $msg .= $year_level_title . ' > Section '.$report_name.' changed ';
            foreach ($changes as $k=>$change_item){
                if(is_null($change_item) || trim($change_item) == ''){
                    $change_item = 'NULL';
                }
                if($k == 'title'){
                    if(!is_null($original['title'])){
                        $msg .= ' > Title from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Title to '. $change_item;
                    }
                }elseif($k == 'description'){
                    if(!is_null($original[$k]) && !empty($original[$k])){
                        $msg .= ' > Description from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Description to '. $change_item;
                    }
                }elseif($k == 'report_name'){
                    if(!is_null($original[$k])){
                        $msg .= ' > Report Name from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Report Name to '. $change_item;
                    }
                }elseif($k == 'show_on_reports'){
                    if(!is_null($original['show_on_reports'])){
                        $original_text = "";
                        if($original[$k] == 1){
                            $original_text = "Yes";
                        }else if($original[$k] == 0){
                            $original_text = "No";
                        }
                        $item_text = "";
                        if($change_item == 1){
                            $item_text = "Yes";
                        }else if($change_item == 0){
                            $item_text = "No";
                        }
                        $msg .= ' > Show On Reports from '. $original_text . ' to '. $item_text;
                    }else{
                        $item_text = "";
                        if($change_item == 1){
                            $item_text = "Yes";
                        }else if($change_item == 0){
                            $item_text = "No";
                        }
                        $msg .= ' >  Show On Reports to '. $item_text;
                    }
                }elseif($k == 'min_selections'){
                    if(!is_null($original[$k])){
                        $msg .= ' > Min Selections from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Min Selections to '. $change_item;
                    }
                }elseif($k == 'max_selections'){
                    if(!is_null($original[$k])){
                        $msg .= ' > Max Selections from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Max Selections to '. $change_item;
                    }
                }elseif($k == 'min_units'){
                    if(!is_null($original[$k])){
                        $msg .= ' > Min Units from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Min Units to '. $change_item;
                    }
                }elseif($k == 'max_units'){
                    if(!is_null($original[$k])){
                        $msg .= ' > Max units from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Max units to '. $change_item;
                    }
                }elseif($k == 'allow_same_code'){
                    if(!is_null($original['allow_same_code'])){
                        $original_text = "";
                        if($original[$k] == 1){
                            $original_text = "Yes";
                        }else if($original[$k] == 0){
                            $original_text = "No";
                        }
                        $item_text = "";
                        if($change_item == 1){
                            $item_text = "Yes";
                        }else if($change_item == 0){
                            $item_text = "No";
                        }
                        $msg .= ' > Allow The Same Code from '. $original_text . ' to '. $item_text;
                    }else{
                        $item_text = "";
                        if($change_item == 1){
                            $item_text = "Yes";
                        }else if($change_item == 0){
                            $item_text = "No";
                        }
                        $msg .= ' > Allow The Same Code to '. $item_text;
                    }
                }elseif($k == 'order_number'){
                    if(!is_null($original[$k])){
                        $msg .= ' > Order Number from '. $original[$k] . ' to '. $change_item;
                    }else{
                        $msg .= ' > Order Number to '. $change_item;
                    }
                }
            }
        }
        return $msg;
    }

    /**
     * Checking Section has two same code selected
     * @param  int  $section_id
     * @return array
     */
    public function checking_has_two_same_code_selected($section_id)
    {
        $response = array('has_some_code' => false, 'selected_s_matrices' => [], 'errors' => []);
        try {
            $student_selections = StudentSelection::where('student_selections.section_id', '=', $section_id)
                ->join('student_selections as second_student_selections', function ($join) use ($section_id) {
                    $join->on('student_selections.student_user_id', '=', 'second_student_selections.student_user_id');
                    $join->where('second_student_selections.section_id', '=', $section_id);
                })
                ->join('selections', function ($join) {
                    $join->on('selections.id', '=', 'student_selections.selection_id');
                })
                ->join('selection_matrices', function ($join) {
                    $join->on('selection_matrices.id', '=', 'selections.selection_matrix_id');
                })
                ->distinct('student_selections.id')
                ->get([
                    'student_selections.id',
                    'student_selections.student_user_id',
                    'selection_matrices.id as sm_id',
                    'selection_matrices.course_code',
                    'selection_matrices.course_name',
                ]);

            $has_same_code_selections = false;
            $selected_sm_ids = [];
            $selected_s_matrices = [];
            $students = [];
            if (!$student_selections->isEmpty()) {
                foreach ($student_selections as $selection) {
                    $students[$selection->student_user_id][$selection->sm_id][] = $selection;
                    if (count($students[$selection->student_user_id][$selection->sm_id]) > 1) {
                        $has_same_code_selections = true;
                        if (!in_array($selection->sm_id, $selected_sm_ids)) {
                            $selected_sm_ids[] = $selection->sm_id;
                            $selected_s_matrices[] = $selection;
                        }
                    }
                }
            }
            $statusCode = Response::HTTP_OK;
            $response['has_some_code'] = $has_same_code_selections;
            $response['selected_s_matrices'] = $selected_s_matrices;
        }catch (\Exception $e){
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response['errors'][] = $e->getMessage();
            $this->addLog(array(
                "log_type_id" => LogType::ERROR,
                "message"     => $e->getMessage(),
                "status_code" => $statusCode
            ));
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
}
