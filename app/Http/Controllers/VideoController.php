<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VideoRepositoryEloquent;
use App\Http\Requests\CreateVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Services\VideoService;

class VideoController extends Controller
{
    protected $videoRepository;
    public function __construct(VideoRepositoryEloquent $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $conditions = [];
        if ($request->has('title')) {
            $conditions[] = ['title', 'LIKE', "%{$request->title}%"];
        }
        if ($request->has('category')) {
            $conditions[] = ['category_id', '=', $request->category];
        }
        if ($request->has('active')) {
            $conditions[] = ['active', '=', $request->active];
        }
        if ($conditions) {
            $data = $this->videoRepository->findWhere($conditions);
        } else {
            $data = $this->videoRepository->all();
        }
        return VideoResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVideoRequest $request, VideoService $videoService)
    {
        $data   = $videoService->create($request);
        return new VideoResource($this->videoRepository->create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new VideoResource($this->videoRepository->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVideoRequest $request, $id, VideoService $videoService)
    {
        $data   = $videoService->update($request);
        return new VideoResource($this->videoRepository->update($data, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->videoRepository->delete($id);
    }
}