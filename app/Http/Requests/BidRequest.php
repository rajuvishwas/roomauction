<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $auction = request()->get('auction');

        $min_bid = $auction->room->min_bid;
        if ($auction->latestBid != null)
            $min_bid = $auction->latestBid->price;

        return [
            'auction_id' => 'required|integer',
            'price' => 'required|integer|greater_than:' . $min_bid . ''
        ];
    }
}
