<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementBanner;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $placements = AnnouncementBanner::PLACEMENTS;

        return view('admin.announcements.index', compact('banners', 'icons', 'placements'));
    }

    /**
     * Store a new announcement banner
     */
    public function store(Request $request)
    {
        AnnouncementBanner::create($this->validatedData($request));

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Hláška byla úspěšně vytvořena.');
    }

    /**
     * Update an announcement banner
     */
    public function update(Request $request, AnnouncementBanner $announcement)
    {
        $announcement->update($this->validatedData($request));

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

    /**
     * Shared validation for store/update
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'message_cs' => 'required|string|max:500',
            'message_en' => 'nullable|string|max:500',
            'title_cs' => 'nullable|string|max:120',
            'title_en' => 'nullable|string|max:120',
            'icon' => 'required|string|in:' . implode(',', array_keys(AnnouncementBanner::ICONS)),
            'active_from' => 'nullable|date',
            'active_until' => 'nullable|date|after_or_equal:active_from',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Nezaškrtnutý checkbox se neodešle, proto has() místo validace
        $placements = array_keys(AnnouncementBanner::PLACEMENTS);

        foreach ($placements as $placement) {
            $validated[$placement] = $request->has($placement);
        }

        // Hláška bez umístění by se nikde nezobrazila
        if (! collect($placements)->contains(fn ($placement) => $validated[$placement])) {
            throw ValidationException::withMessages([
                AnnouncementBanner::PLACEMENT_HEADER => 'Vyberte alespoň jedno umístění, jinak se hláška nikde nezobrazí.',
            ]);
        }

        return $validated;
    }
}
