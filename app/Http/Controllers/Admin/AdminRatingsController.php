<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminRatingsController extends BaseAdminController
{
    /**
     * Show admin ratings page
     */
    public function index(): View
    {
        $ratings = Rating::with(['book'])->paginate(10);
        return view('admin.ratings', compact('ratings'));
    }

    /**
     * Delete rating
     */
    public function destroy(Rating $rating): RedirectResponse
    {
        $rating->delete();
        
        return redirect()->route('admin.ratings')->with('success', 'Rating deleted successfully.');
    }
}