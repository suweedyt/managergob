<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\FeatureSetting;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\DB as FacadeDB;
use Carbon\Carbon;
use App\Models\Section;
use App\Models\SectionSetting;
use App\Models\Tramite;
use App\Models\TramiteSetting;
use App\Models\Location;
use App\Models\ContactSetting;
use App\Models\NewsShowcaseItem;
use App\Models\NewsShowcaseSetting;

class WebsiteController extends Controller
{
    public function home()
    {
        $sectionsSettings = SectionSetting::first();
        $sections = $this->generateSections();
        $tramites = Tramite::where('is_published', true)
            ->orderBy('id', 'asc')
            ->take(8)
            ->get();
        $tramiteSettings = TramiteSetting::first();
        $featureSetting = FeatureSetting::first();
        $showcasePosts = NewsShowcaseItem::with('post.gallery')
            ->latest()
            ->take(8)
            ->get();
        $showcaseSettings = NewsShowcaseSetting::first();

        return view('website.index', [
            'sectionsHome' => $sections,
            'sectionsSettings' => $sectionsSettings,
            'tramites' => $tramites,
            'tramiteSettings' => $tramiteSettings,
            'featureSetting' => $featureSetting,
            'bannerSliders' => $this->getBannerSliders(),
            'showcaseItems' => $showcasePosts,
            'showcaseSettings' => $showcaseSettings,
        ]);
    }

    protected function generateSections()
    {
        $sections = collect();
        $tramiteSettings = TramiteSetting::first();
        $tramiteTitle = optional($tramiteSettings)->title ?? 'Trámites y Servicios';
        $tramiteLogoImage = optional($tramiteSettings)->logo_image ?? null;
        $tramiteLogoClass = optional($tramiteSettings)->logo_class ?? 'mdi mdi-file-document-box';
        $tramitesCount = Tramite::where('is_published', true)->count();
        $sections = Section::where('is_published', true)
            ->orderBy('order')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        // Insert virtual "Trámites y Servicios" at top only when there are tramites
        if ($tramitesCount > 0) {
            $virtualTramites = (object) [
                'id' => 'tramites-virtual',
                'title_full' => $tramiteTitle,
                'title_short' => null,
                'logo_class' => $tramiteLogoClass,
                'logo_image' => $tramiteLogoImage,
                'description' => null,
                'content' => null,
                'mode' => 'link',
                'redirect_url' => route('tramites'),
            ];

            $sections = $sections->prepend($virtualTramites);
        }

        return $sections;
    }

    public function news()
    {
        $latestsPosts = Post::where('is_published', Post::Published)
            ->where('is_news_slider', true)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Build base query for listing with optional filters
        $query = Post::where('is_published', Post::Published)->orderByDesc('id');

        $filterYear = request()->query('year');
        $filterMonth = request()->query('month');
        $filterCategory = request()->query('category');

        if ($filterYear && $filterMonth) {
            $query->whereYear('created_at', (int) $filterYear)
                  ->whereMonth('created_at', (int) $filterMonth);
        }

        if ($filterCategory) {
            $query->where('category_id', (int) $filterCategory);
        }

        // Paginar 2 noticias por página como se desea
        $posts = $query->paginate(2)->withQueryString();

        // Archive months: get distinct year-month combinations from published posts
        $months = Post::where('is_published', Post::Published)
            ->select(FacadeDB::raw("YEAR(created_at) as year"), FacadeDB::raw("MONTH(created_at) as month"))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->distinct()
            ->get();

        // Map months into a simple array with labels
        $archiveMonths = $months->map(function ($row) {
            $year = $row->year;
            $month = $row->month;
            $monthName = Carbon::createFromDate($year, $month, 1)->locale('es')->isoFormat('MMMM');
            $label = ucfirst($monthName) . ' ' . $year;
            return [
                'year' => $year,
                'month' => $month,
                'label' => $label,
            ];
        })->unique();

        $categories = Category::orderBy('name')->get();

        return view('website.news.index', [
            'posts' => $posts,
            'latestsPosts' => $latestsPosts,
            'archiveMonths' => $archiveMonths,
            'categories' => $categories,
        ]);
    }

    public function show(Post $new)
    {
        return view('website.news.single', ['new' => $new]);
    }

    public function tramites()
    {
        $tramites = Tramite::where('is_published', true)->orderBy('id', 'asc')->get();
        $tramiteSettings = TramiteSetting::first();
        $featureSetting = FeatureSetting::first();
        return view('website.tramites.index', ['tramites' => $tramites, 'tramiteSettings' => $tramiteSettings, 'featureSetting' => $featureSetting]);
    }

    public function tramiteLink(Tramite $tramite)
    {
        if ($tramite->mode !== 'link' || empty($tramite->redirect_url)) {
            // Not configured as link — redirect to the tramite public view
            return to_route('website.tramites.show', ['tramite' => $tramite->id]);
        }

        // Pass the redirect URL to a dedicated view which will embed or load it
        return view('website.tramites.link', ['tramite' => $tramite]);
    }

    public function tramiteShow(Tramite $tramite)
    {
        // Show the tramites index but ask it to open the selected item
        $tramites = Tramite::where('is_published', true)->orderBy('id', 'asc')->get();
        $tramiteSettings = TramiteSetting::first();
        $featureSetting = FeatureSetting::first();
        return view('website.tramites.index', ['tramites' => $tramites, 'openId' => $tramite->id, 'tramiteSettings' => $tramiteSettings]);
    }

    public function contact()
    {
        $locations = Location::where('is_published', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $contactSettings = ContactSetting::first();

        return view('website.locations.contact', ['locations' => $locations, 'contactSettings' => $contactSettings]);
    }

    private function getBannerSliders()
    {
        $newsBanners = Post::where('is_published', Post::Published)
            ->where('is_slider', true)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $customBanners = Banner::where('is_published', true)
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return $customBanners->concat($newsBanners)->values();
    }

    public function sections()
    {
        $sections = $this->generateSections();
        $collapse = request()->query('collapse');
        return view('website.sections.index', ['sections' => $sections, 'collapse' => $collapse]);
    }

    public function featureLanding(?string $slug = null)
    {
        $settings = FeatureSetting::first();
        if (!$settings || ($settings->link_type !== 'landing')) {
            return to_route('home');
        }

        $targetSlug = $settings->slug;
        if ($slug && $slug !== $targetSlug) {
            return to_route('feature.landing', ['slug' => $targetSlug]);
        }

        return view('website.feature-landing', [
            'settings' => $settings,
        ]);
    }
}