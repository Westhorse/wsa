<?php

namespace App\Http\Resources\Dashboard;

use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        $networkSlug = Network::where('domain', $request->query('domain'))->value('slug');
        $networkId = Network::where('domain', $request->query('domain'))->value('id');
        $networks = $this->networks->where('pivot.network_id', $networkId)->map->pivot->first();
        $networkValue = $networks ? $networks->value : null;

        $value = null;
        if ($this->type === 'uploader') {
            $value = $this->getFirstMediaUrl($networkSlug);
        } else {
            $value = $networkValue ? ($this->type === 'select')
                ? (int)$networkValue : (($this->type === 'boolean')
                    ? ($networkValue == 1 ? true : false) :
                    $networkValue) : null;
        }

        $rules = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'value' => $value,
        ];

        $excludedKeys = [
            // Email SMTP Data
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',

            // Email Templates
            'contact_us_email_template',
            'approved_company_email_template',
            'reset_password_email_template',
            'new_application_confirmation_email_template',
            'birthdate_email_template',
        ];

        if (in_array($this->name, $excludedKeys)) {
            return [];
        }

        return $rules;
    }
}
