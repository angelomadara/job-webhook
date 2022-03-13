<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title ? $this->title : '',
            'url' => $this->url ? $this->url: '',
            'readable_time' => $this->date ? Carbon::parse($this->date)->diffForHumans() : '',
            'date_from_data' => date("Y-m-d",strtotime($this->date)),
            'date_submitted' => Carbon::parse($this->date_submitted)->diffForHumans(),
            'url_status' => $this->url_status,
            // 'isIndexed' => $this->is_indexed ? 'yes' : 'no',
            // 'update_response' => $this->update_url_response,
            // 'remove_response' => $this->delete_url_response,
            // 'date_modified' => Carbon::parse($this->updated_at)->diffForHumans(),
            // 'job_response' => $this->metaData,
        ];
    }
}
