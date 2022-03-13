<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $metadata = json_decode($this->metaData);
        $url_updated = "";
        $url_deleted = "";

        foreach($metadata as $key => $data){
            if($data->type == 'URL_UPDATED'){ $url_updated = 'yes'; }
            else{ $url_deleted = 'yes'; }
        }

        return [
            'Job_Title' => $this->title ? $this->title : '',
            'Url' => $this->url ? $this->url: '',
            'Date' => date("Y-m-d",strtotime($this->date)),
            'Date_Submitted' => date("Y-m-d H:i:s",strtotime($this->created_at)),
            'isIndexed' => $this->is_indexed ? 'yes' : 'no',
            'URL_UPDATED' => $url_updated,
            'URL_DELETED' => $url_deleted,
            'url_status' => $this->urlStatus ? $this->urlStatus->status : null
        ];
    }
}
