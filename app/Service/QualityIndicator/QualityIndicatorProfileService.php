<?php


namespace App\Service\QualityIndicator;


use App\Models\Table\QualityIndicatorProfileTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        QualityIndicatorProfileTable $model
    )
    {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null)
    {
        $result =   $this->model->newQuery()
                                ->with('program')
                                ->with('subProgram')
                                ->with('pic')
                                ->with('document')
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->with('program')
                                ->with('subProgram')
                                ->with('pic')
                                ->with('document')
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
                                ->with('program')
                                ->with('subProgram')
                                ->with('pic')
                                ->with('document')
                                ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $qualityIndicatorProfile = $this->model->newQuery()->create([
                'program_id'                =>  $data['program_id'],
                'sub_program_id'            =>  $data['sub_program_id'],
                'title'                     =>  $data['title'],
                'indicator_selection_based' =>  $data['indicator_selection_based'],
                'objective'                 =>  $data['objective'],
                'operational_definition'    =>  $data['operational_definition'],
                'indicator_type'            =>  $data['indicator_type'],
                'measurement_status'        =>  $data['measurement_status'],
                'numerator'                 =>  $data['numerator'],
                'denominator'               =>  $data['denominator'],
                'achievement_target'        =>  $data['achievement_target'],
                'criteria'                  =>  $data['criteria'],
                'measurement_formula'       =>  $data['measurement_formula'],
                'data_collection_design'    =>  $data['data_collection_design'],
                'data_source'               =>  $data['data_source'],
                'population'                =>  $data['population'],
                'data_collection_frequency' =>  $data['data_collection_frequency'],
                'data_collection_period'    =>  $data['data_collection_period'],
                'data_analyst_period'       =>  $data['data_analyst_period'],
                'data_presentation'         =>  $data['data_presentation'],
                'pic_id'                    =>  $data['pic_id'],
            ]);

            if (!empty($data['document_id'])) {
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($qualityIndicatorProfile),
                    'fileable_id'   => $qualityIndicatorProfile->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($qualityIndicatorProfile);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $qualityIndicatorProfile   =   $this->model->newQuery()->find($id);
        $oldImage                  =   $qualityIndicatorProfile->document()->first();

        \DB::beginTransaction();

        try {
            $qualityIndicatorProfile->program_id        =   $data['program_id'];
            $qualityIndicatorProfile->sub_program_id    =   $data['sub_program_id'];
            $qualityIndicatorProfile->title             =   $data['title'];
            $qualityIndicatorProfile->indicator_selection_based =   $data['indicator_selection_based'];
            $qualityIndicatorProfile->quality_dimension =   $data['quality_dimension'];
            $qualityIndicatorProfile->objective         =   $data['objective'];
            $qualityIndicatorProfile->operational_definition =   $data['operational_definition'];
            $qualityIndicatorProfile->indicator_type    =   $data['indicator_type'];
            $qualityIndicatorProfile->measurement_status    =   $data['measurement_status'];
            $qualityIndicatorProfile->numerator         =   $data['numerator'];
            $qualityIndicatorProfile->denominator       =   $data['denominator'];
            $qualityIndicatorProfile->achievement_target   =  $data['achievement_target'];
            $qualityIndicatorProfile->criteria    =   $data['criteria'];
            $qualityIndicatorProfile->measurement_formula   =   $data['measurement_formula'];
            $qualityIndicatorProfile->data_collection_design    =   $data['data_collection_design'];
            $qualityIndicatorProfile->data_source       =   $data['data_source'];
            $qualityIndicatorProfile->population        =   $data['population'];
            $qualityIndicatorProfile->data_collection_frequency   =   $data['data_collection_frequency'];
            $qualityIndicatorProfile->data_collection_period    =   $data['data_collection_period'];
            $qualityIndicatorProfile->data_analyst_period   =   $data['data_analyst_period'];
            $qualityIndicatorProfile->data_presentation  =   $data['data_presentation'];
            $qualityIndicatorProfile->data_collection_instrument    =   $data['data_collection_instrument'];
            $qualityIndicatorProfile->pic_id            =   $data['pic_id'];
            $qualityIndicatorProfile->save();

            if (!empty($data['document_id'])) {
                if (!empty($oldImage)) {
                    $oldImage->delete();
                }
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($qualityIndicatorProfile),
                    'fileable_id'   => $qualityIndicatorProfile->id,
                ]);
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($qualityIndicatorProfile);
        } catch (\Exception $exception) {

            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function delete($id)
    {
        $read   =   $this->model->newQuery()->find($id);
        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function getAllQualityGoal($search = null, $year = null)
    {
        $result =   $this->model->newQuery()
                                ->select('title')
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginatedQualityGoal($search = null, $year = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->select('id', 'title')
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }
}
