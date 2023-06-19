<?php

namespace App\Http\Requests\Artist;

use App\Rules\ValidationArtist;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachEventArtistsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check() == true;
    }

    public function rules(): array
    {
        $event_id = request()->route('event')->id;
        $artists_id = request()->input('artists.*');

        return [
            'artists' => ['required', 'array'],
            'artists.*' => ['required', 'exists:artists,id', new ValidationArtist(), 'distinct',
                Rule::unique('artist_events', 'artist_id')
                    ->where(function (Builder $query) use (
                        $event_id,
                        $artists_id
                    ) {
                        $query->whereIn('artist_id', $artists_id)
                        ->where('event_id', '=', $event_id);
                    }),
            ],
        ];
    }
}
