<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementBanner;
use Illuminate\Http\Request;

class AnnouncementBannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display listing of announcement banners
     */
    public function index()
    {
        $banners = AnnouncementBanner::orderBy('created_at', 'desc')->get();
        $icons = AnnouncementBanner::ICONS;
        
        return view('admin.announcements.index', compact('banners', 'icons'));
    }

    /**
     * Store a new announcement banner
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message_cs' => 'required|string|max:500',
            'message_en' => 'nullable|string|max:500',
            'icon' => 'required|string|in:' . implode(',', array_keys(AnnouncementBanner::ICONS)),
            'active_from' => 'nullable|date',
            'active_until' => 'nullable|date|after_or_equal:active_from',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        AnnouncementBanner::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Hláška byla úspěšně vytvořena.');
    }

    /**
     * Update an announcement banner
     */
    public function update(Request $request, AnnouncementBanner $announcement)
    {
        $validated = $request->validate([
            'message_cs' => 'required|string|max:500',
            'message_en' => 'nullable|string|max:500',
            'icon' => 'required|string|in:' . implode(',', array_keys(AnnouncementBanner::ICONS)),
            'active_from' => 'nullable|date',
            'active_until' => 'nullable|date|after_or_equal:active_from',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Hláška byla úspěšně aktualizována.');
    }

    /**
     * Delete an announcement banner
     */
    public function destroy(AnnouncementBanner $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Hláška byla úspěšně smazána.');
    }

    /**
     * Toggle the active state of a banner
     */
    public function toggle(AnnouncementBanner $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        $status = $announcement->is_active ? 'aktivována' : 'deaktivována';
        
        return redirect()->route('admin.announcements.index')
            ->with('success', "Hláška byla úspěšně {$status}.");
    }
}

