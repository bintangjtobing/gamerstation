<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
            'id'                => $this->id,
            'first_name'        => $this->firstname,
            'last_name'         => $this->lastname,
            'username'          => $this->username,
            'email'             => $this->email ?? null,
            'status'            => $this->status,
            'mobile'            => $this->mobile ?? null,
            'address'            => $this->address ?? null,
            'image'             => $this->image ? $this->image : null,
            'two_factor_status' => $this->two_factor_status ?? null,
            'two_factor_verified' => $this->two_factor_verified ?? null,
            'email_verified_at' => $this->email_verified_at ?? null,
            'email_verified'    => $this->email_verified ?? null,
            'sms_verified'      => $this->sms_verified ?? 0,
            'kyc_verified'      => $this->kyc_verified ?? 0,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
