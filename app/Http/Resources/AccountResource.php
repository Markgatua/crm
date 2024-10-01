<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
			"client_id"=>$this->client_id,
			"client_type_id"=>$this->client_type_id,
			"user_id"=>$this->user_id,
            "solution_type_id"=>$this->solution_type_id,
			"business_name"=>$this->business_name,
            "solution_name"=>$this->solution_name,
			"comments"=>$this->comments,
			"contact_information"=>$this->contact_information,
			"accountmanagerfirstname"=>$this->accountmanagerfirstname,
			"accountmanagerlastname"=>$this->accountmanagerlastname,
			"accountmanageremail"=>$this->accountmanageremail,
			"accountmanagerphone"=>$this->accountmanagerphone,
			"clienttype"=>$this->clienttype,
			"clientname"=>$this->clientname,
			"clientid"=>$this->clientid,
			"clientemail"=>$this->clientemail,
			"clientphone"=>$this->clientphone,
			"clientlocation"=>$this->clientlocation,
			"clientwebsiteurl"=>$this->clientwebsiteurl,
            "accountmaincontacts"=>$this->accountmaincontacts,
        ];
    }
}
