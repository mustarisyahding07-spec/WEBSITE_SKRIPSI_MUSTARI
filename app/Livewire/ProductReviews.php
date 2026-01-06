<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductReviews extends Component
{
    public Product $product;
    
    public $name = '';
    public $rating = 5;
    public $comment = '';

    protected function rules()
    {
        return [
            'name' => Auth::check() ? 'nullable' : 'required|string|min:3|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ];
    }

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function submit()
    {
        $this->validate();

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => Auth::id(),
            'customer_name' => Auth::check() ? Auth::user()->name : $this->name,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => true, // Auto-approve for now based on context
        ]);

        $this->reset(['rating', 'comment', 'name']);

        session()->flash('message', 'Terima kasih! Ulasan Anda telah diterbitkan.');
    }

    public function render()
    {
        // Refresh product relations to get latest approved reviews
        $reviews = $this->product->reviews()
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.product-reviews', [
            'reviews' => $reviews,
        ]);
    }
}
