@extends('layouts.admin')

@section('title', 'Storefront Content Management (CMS)')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Storefront & Home CMS</h4>
                <p class="text-muted fs-xs mb-0">Control all homepage banners, headlines, buttons, imagery, and promotional content dynamically.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="ti ti-external-link me-1"></i> View Live Storefront
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="cms_form_context" value="home">

    <!-- CMS Navigation Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent p-0 border-bottom">
            <ul class="nav nav-tabs card-header-tabs m-0 px-3 pt-2" id="cmsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="hero-tab" data-bs-toggle="tab" data-bs-target="#tab-hero" type="button" role="tab">
                        <i class="ti ti-photo me-1 text-primary"></i> Hero Showcase
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="ticker-tab" data-bs-toggle="tab" data-bs-target="#tab-ticker" type="button" role="tab">
                        <i class="ti ti-broadcast me-1 text-info"></i> Top Bar & Ticker
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="spotlight-tab" data-bs-toggle="tab" data-bs-target="#tab-spotlight" type="button" role="tab">
                        <i class="ti ti-sparkles me-1 text-warning"></i> Spotlight Editorials
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="sections-tab" data-bs-toggle="tab" data-bs-target="#tab-sections" type="button" role="tab">
                        <i class="ti ti-layout-grid me-1 text-success"></i> Section Titles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="footer-tab" data-bs-toggle="tab" data-bs-target="#tab-footer" type="button" role="tab">
                        <i class="ti ti-brand-instagram me-1 text-danger"></i> Footer & Social
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="cmsTabsContent">
                
                <!-- TAB 1: HERO SHOWCASE -->
                <div class="tab-pane fade show active" id="tab-hero" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-sparkles me-1"></i> Main Hero Narrative</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Hero Eyebrow Tag</label>
                                    <input type="text" name="cms_hero_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_eyebrow', 'Onam Collection · 2026') }}">
                                    <div class="form-text">Small uppercase badge shown above the main title.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Title Line 1</label>
                                    <input type="text" name="cms_hero_title_1" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_title_1', 'Woven in gold.') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Title Line 2 (Italic Accent Serif)</label>
                                    <input type="text" name="cms_hero_title_2" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_title_2', 'Worn like poetry.') }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Hero Description / Story</label>
                                    <textarea name="cms_hero_description" rows="3" class="form-control">{{ \App\Models\Setting::get('cms_hero_description', 'A limited edition of hand-loomed Kasavu sarees, dyed in ivory and edged with antique gold — crafted by third-generation weavers in Balaramapuram.') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Primary Button Text</label>
                                    <input type="text" name="cms_hero_btn1_text" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_btn1_text', 'Shop New Collection') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Primary Button URL</label>
                                    <input type="text" name="cms_hero_btn1_url" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_btn1_url', '/shop') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Secondary Button Text</label>
                                    <input type="text" name="cms_hero_btn2_text" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_btn2_text', 'Explore Sarees') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Secondary Button URL</label>
                                    <input type="text" name="cms_hero_btn2_url" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_btn2_url', '/shop?c=kerala-sarees') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Trust Badge 1 (Left)</label>
                                    <input type="text" name="cms_hero_badge_1" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_badge_1', 'Handloom certified') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Trust Badge 2 (Right / Reviews)</label>
                                    <input type="text" name="cms_hero_badge_2" class="form-control" value="{{ \App\Models\Setting::get('cms_hero_badge_2', '4.9 · 12,400 reviews') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-photo me-1"></i> Hero Editorial Image</h5>
                            <div class="card border p-3 bg-light text-center">
                                @php
                                    $heroImg = \App\Models\Setting::get('cms_hero_image', 'images/manacoture/hero-saree.jpg');
                                    $heroImgSrc = str_starts_with($heroImg, 'http') ? $heroImg : asset($heroImg);
                                @endphp
                                <img src="{{ $heroImgSrc }}" alt="Hero Preview" class="img-fluid rounded mb-3 shadow-sm" style="max-height: 260px; object-fit: cover; width: 100%;">
                                <div class="text-start">
                                    <label class="form-label fw-semibold">Upload Replacement Image</label>
                                    <input type="file" name="hero_image_file" class="form-control" accept="image/*">
                                    <div class="form-text">Recommended: High-resolution portrait or square image (e.g. 1200x1400px JPG/WebP).</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: TOP BAR & TICKER -->
                <div class="tab-pane fade" id="tab-ticker" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card border p-3 h-100">
                                <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-bell-ringing me-1"></i> Top Announcement Bar</h5>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="cms_announcement_active" name="cms_announcement_active" value="1" {{ \App\Models\Setting::get('cms_announcement_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="cms_announcement_active">Display Announcement Bar at top of storefront</label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Announcement Text</label>
                                    <textarea name="cms_announcement_text" rows="3" class="form-control">{{ \App\Models\Setting::get('cms_announcement_text', 'Complimentary shipping across India · Handwoven in Kerala · New Onam Collection 2026') }}</textarea>
                                    <div class="form-text">Use ' · ' (middle dot) to separate rotating bullet messages.</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card border p-3 h-100">
                                <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-marquee me-1"></i> Animated Marquee Strip</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Marquee Keywords (Comma Separated)</label>
                                    <textarea name="cms_marquee_words" rows="4" class="form-control">{{ \App\Models\Setting::get('cms_marquee_words', 'Kasavu, Bridal Silks, Set Mundu, Handloom, Kanchipuram, Onam, Vishu, Temple Wear') }}</textarea>
                                    <div class="form-text">These words scroll continuously in an elegant italic gold typography below the hero.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: SPOTLIGHT EDITORIALS -->
                <div class="tab-pane fade" id="tab-spotlight" role="tabpanel">
                    <!-- Bridal Section -->
                    <div class="card border p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0 text-primary"><i class="ti ti-crown me-1 text-warning"></i> Bridal Spotlight Editorial</h5>
                            <span class="badge bg-warning-subtle text-warning">Luxury Highlight</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Section Eyebrow</label>
                                <input type="text" name="cms_bridal_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_bridal_eyebrow', 'The Bridal Atelier') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Editorial Heading</label>
                                <input type="text" name="cms_bridal_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_bridal_heading', 'Gold woven for a lifetime.') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Editorial Description</label>
                                <textarea name="cms_bridal_description" rows="3" class="form-control">{{ \App\Models\Setting::get('cms_bridal_description', 'Every Kerala bride deserves a weave that honors tradition while commanding the room. Our bridal collection unites heirloom Kanchipuram silk with hand-beaten certified zari, finished by master artisans in our Kochi studio.') }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Button 1 Label</label>
                                <input type="text" name="cms_bridal_btn1_text" class="form-control" value="{{ \App\Models\Setting::get('cms_bridal_btn1_text', 'Discover Bridal Silks') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Button 1 URL</label>
                                <input type="text" name="cms_bridal_btn1_url" class="form-control" value="{{ \App\Models\Setting::get('cms_bridal_btn1_url', '/shop?c=bridal') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Button 2 Label</label>
                                <input type="text" name="cms_bridal_btn2_text" class="form-control" value="{{ \App\Models\Setting::get('cms_bridal_btn2_text', 'Book Atelier Appointment') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Button 2 URL</label>
                                <input type="text" name="cms_bridal_btn2_url" class="form-control" value="{{ \App\Models\Setting::get('cms_bridal_btn2_url', '/about') }}">
                            </div>
                            <div class="col-md-12">
                                @php
                                    $bridalImg = \App\Models\Setting::get('cms_bridal_image', 'images/manacoture/bridal.jpg');
                                    $bridalImgSrc = str_starts_with($bridalImg, 'http') ? $bridalImg : asset($bridalImg);
                                @endphp
                                <div class="d-flex align-items-center gap-3 mt-2">
                                    <img src="{{ $bridalImgSrc }}" alt="Bridal Preview" class="rounded border" width="100" height="100" style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <label class="form-label fw-semibold">Replace Bridal Editorial Image</label>
                                        <input type="file" name="bridal_image_file" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Festival Section -->
                    <div class="card border p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0 text-primary"><i class="ti ti-flame me-1 text-danger"></i> Festival Full Bleed Banner</h5>
                            <span class="badge bg-danger-subtle text-danger">Celebration Edit</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Section Eyebrow</label>
                                <input type="text" name="cms_festival_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_festival_eyebrow', 'Festival · Onam · Vishu') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Banner Heading</label>
                                <input type="text" name="cms_festival_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_festival_heading', 'A courtyard lit for celebration.') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Banner Narrative</label>
                                <textarea name="cms_festival_description" rows="2" class="form-control">{{ \App\Models\Setting::get('cms_festival_description', 'Discover our festival edit — Kasavu for Onam, silks for Vishu, and temple couture for every sacred occasion.') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Action Button Text</label>
                                <input type="text" name="cms_festival_btn_text" class="form-control" value="{{ \App\Models\Setting::get('cms_festival_btn_text', 'Shop The Edit') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Action Button Link</label>
                                <input type="text" name="cms_festival_btn_url" class="form-control" value="{{ \App\Models\Setting::get('cms_festival_btn_url', '/shop?occasion=Festival') }}">
                            </div>
                            <div class="col-md-12">
                                @php
                                    $festImg = \App\Models\Setting::get('cms_festival_image', 'images/manacoture/festival.jpg');
                                    $festImgSrc = str_starts_with($festImg, 'http') ? $festImg : asset($festImg);
                                @endphp
                                <div class="d-flex align-items-center gap-3 mt-2">
                                    <img src="{{ $festImgSrc }}" alt="Festival Preview" class="rounded border" width="100" height="100" style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <label class="form-label fw-semibold">Replace Festival Background Banner</label>
                                        <input type="file" name="festival_image_file" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: SECTION HEADINGS -->
                <div class="tab-pane fade" id="tab-sections" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border p-3 h-100">
                                <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-category me-1"></i> Categories Grid Header</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Eyebrow</label>
                                    <input type="text" name="cms_categories_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_categories_eyebrow', 'Curated Categories') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Section Heading</label>
                                    <input type="text" name="cms_categories_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_categories_heading', 'The Kerala Weaves') }}">
                                </div>
                                <p class="fs-xs text-muted">Currently displays {{ $categoriesCount }} active categories dynamically from your Catalog.</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border p-3 h-100">
                                <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-star me-1"></i> Featured Heirlooms Header</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Eyebrow</label>
                                    <input type="text" name="cms_featured_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_featured_eyebrow', 'Featured Heirlooms') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Section Heading</label>
                                    <input type="text" name="cms_featured_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_featured_heading', 'Handloom Masterpieces') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Button Label</label>
                                    <input type="text" name="cms_featured_btn_text" class="form-control" value="{{ \App\Models\Setting::get('cms_featured_btn_text', 'View Entire Edit') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border p-3 h-100">
                                <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-heart me-1"></i> Best Sellers Header</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Eyebrow</label>
                                    <input type="text" name="cms_bestsellers_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_bestsellers_eyebrow', 'Best Sellers') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Section Heading</label>
                                    <input type="text" name="cms_bestsellers_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_bestsellers_heading', 'Loved By Many') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border p-3 h-100">
                                <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-shield-check me-1"></i> The Promise & Values Header</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Eyebrow</label>
                                    <input type="text" name="cms_promise_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_promise_eyebrow', 'The ManaCouture Promise') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Section Heading</label>
                                    <input type="text" name="cms_promise_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_promise_heading', 'Craft That Lasts A Lifetime') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 5: FOOTER & SOCIAL -->
                <div class="tab-pane fade" id="tab-footer" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-building me-1"></i> Store Bio & Branding</h5>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Brand Title</label>
                                <input type="text" name="cms_site_title" class="form-control" value="{{ \App\Models\Setting::get('cms_site_title', 'ManaCouture') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Brand Tagline</label>
                                <input type="text" name="cms_site_tagline" class="form-control" value="{{ \App\Models\Setting::get('cms_site_tagline', 'Handwoven Kerala Sarees & Bridal Couture') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Footer Brand Biography</label>
                                <textarea name="cms_footer_bio" rows="4" class="form-control">{{ \App\Models\Setting::get('cms_footer_bio', 'Contemporary heirlooms rooted in the handloom traditions of Kerala. Every weave is crafted by master artisans and delivered with care.') }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h5 class="fw-bold mb-3 text-primary"><i class="ti ti-phone-call me-1"></i> Contact & Social Media Links</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Atelier Contact Email</label>
                                    <input type="email" name="cms_contact_email" class="form-control" value="{{ \App\Models\Setting::get('cms_contact_email', 'atelier@manacouture.com') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Concierge Phone</label>
                                    <input type="text" name="cms_contact_phone" class="form-control" value="{{ \App\Models\Setting::get('cms_contact_phone', '+91 (484) 234-5678') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Physical Atelier Address</label>
                                    <input type="text" name="cms_contact_address" class="form-control" value="{{ \App\Models\Setting::get('cms_contact_address', 'ManaCouture Atelier, Marine Drive, Kochi, Kerala 682031, India') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><i class="ti ti-brand-instagram text-danger"></i> Instagram Profile URL</label>
                                    <input type="url" name="cms_social_instagram" class="form-control" value="{{ \App\Models\Setting::get('cms_social_instagram', 'https://instagram.com/manacouture') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><i class="ti ti-brand-facebook text-primary"></i> Facebook Page URL</label>
                                    <input type="url" name="cms_social_facebook" class="form-control" value="{{ \App\Models\Setting::get('cms_social_facebook', 'https://facebook.com/manacouture') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><i class="ti ti-brand-youtube text-danger"></i> YouTube Channel URL</label>
                                    <input type="url" name="cms_social_youtube" class="form-control" value="{{ \App\Models\Setting::get('cms_social_youtube', 'https://youtube.com/@manacouture') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><i class="ti ti-brand-whatsapp text-success"></i> WhatsApp Chat Link</label>
                                    <input type="text" name="cms_social_whatsapp" class="form-control" value="{{ \App\Models\Setting::get('cms_social_whatsapp', 'https://wa.me/919876543210') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sticky Footer Action -->
        <div class="card-footer bg-light p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted fs-xs"><i class="ti ti-info-circle me-1"></i> Click save below to immediately publish updates to the live storefront.</span>
            <button type="submit" class="btn btn-primary fw-semibold px-4 py-2">
                <i class="ti ti-check me-1"></i> Save All CMS Changes
            </button>
        </div>
    </div>
</form>
@endsection
