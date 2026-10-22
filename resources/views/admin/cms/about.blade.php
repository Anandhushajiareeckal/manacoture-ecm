@extends('layouts.admin')

@section('title', 'About Page Content Management (CMS)')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">About & Atelier Story CMS</h4>
                <p class="text-muted fs-xs mb-0">Control the brand heritage narrative, founder's quote, craft pillars, and ethical atelier features.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="ti ti-external-link me-1"></i> View Live About Page
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="cms_form_context" value="about">

    <div class="row g-4">
        <!-- 1. Hero Banner -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0 text-primary"><i class="ti ti-photo me-1"></i> 1. Atelier Hero Banner</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Banner Eyebrow Tag</label>
                            <input type="text" name="cms_about_hero_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_about_hero_eyebrow', 'Since 2018') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Main Hero Heading</label>
                            <input type="text" name="cms_about_hero_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_about_hero_heading', 'A Kerala atelier for the modern woman.') }}">
                        </div>
                        <div class="col-12">
                            @php
                                $aboutHeroImg = \App\Models\Setting::get('cms_about_hero_image', 'images/manacoture/festival.jpg');
                                $aboutHeroImgSrc = str_starts_with($aboutHeroImg, 'http') ? $aboutHeroImg : asset($aboutHeroImg);
                            @endphp
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $aboutHeroImgSrc }}" alt="About Hero" class="rounded border" width="120" height="70" style="object-fit: cover;">
                                <div class="flex-grow-1">
                                    <label class="form-label fw-semibold">Replace Hero Background Banner</label>
                                    <input type="file" name="about_hero_image_file" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Founder Quote -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0 text-primary"><i class="ti ti-quote me-1"></i> 2. Founder Quote & Philosophy</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Philosophical Quote Text</label>
                            <textarea name="cms_about_quote" rows="3" class="form-control">{{ \App\Models\Setting::get('cms_about_quote', 'We weave for the women who wear their culture with quiet pride — brides, mothers, daughters, and the friends who dance with them under Onam lamps.') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Author Attribution</label>
                            <input type="text" name="cms_about_quote_author" class="form-control" value="{{ \App\Models\Setting::get('cms_about_quote_author', '— Anjali Menon, Founder & Creative Director') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Pillars of Craft -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0 text-primary"><i class="ti ti-columns me-1"></i> 3. The Three Pillars of Craft</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Pillar 1 -->
                        <div class="col-md-4">
                            <div class="card border p-3 h-100 bg-light">
                                <h6 class="fw-bold mb-2 text-primary">Pillar 1</h6>
                                <div class="mb-2">
                                    <label class="form-label fs-xs fw-semibold">Eyebrow</label>
                                    <input type="text" name="cms_about_pillar1_eyebrow" class="form-control form-control-sm" value="{{ \App\Models\Setting::get('cms_about_pillar1_eyebrow', '01 · Origin') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-xs fw-semibold">Title</label>
                                    <input type="text" name="cms_about_pillar1_title" class="form-control form-control-sm" value="{{ \App\Models\Setting::get('cms_about_pillar1_title', 'The Balaramapuram Loom') }}">
                                </div>
                                <div>
                                    <label class="form-label fs-xs fw-semibold">Description</label>
                                    <textarea name="cms_about_pillar1_desc" rows="4" class="form-control form-control-sm">{{ \App\Models\Setting::get('cms_about_pillar1_desc', 'Every ManaCouture Kasavu begins on a hand-pit loom in Balaramapuram, where third-generation master weavers work exclusively with pure zari and combed high-count cotton.') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Pillar 2 -->
                        <div class="col-md-4">
                            <div class="card border p-3 h-100 bg-light">
                                <h6 class="fw-bold mb-2 text-primary">Pillar 2</h6>
                                <div class="mb-2">
                                    <label class="form-label fs-xs fw-semibold">Eyebrow</label>
                                    <input type="text" name="cms_about_pillar2_eyebrow" class="form-control form-control-sm" value="{{ \App\Models\Setting::get('cms_about_pillar2_eyebrow', '02 · Craftsmanship') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-xs fw-semibold">Title</label>
                                    <input type="text" name="cms_about_pillar2_title" class="form-control form-control-sm" value="{{ \App\Models\Setting::get('cms_about_pillar2_title', 'The Kochi Atelier') }}">
                                </div>
                                <div>
                                    <label class="form-label fs-xs fw-semibold">Description</label>
                                    <textarea name="cms_about_pillar2_desc" rows="4" class="form-control form-control-sm">{{ \App\Models\Setting::get('cms_about_pillar2_desc', 'Our Kochi atelier meticulously finishes each creation — hand-hemmed borders, gentle cold pressing, wrapped in breathable natural muslin, and presented in signature reusable keepsake trunks.') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Pillar 3 -->
                        <div class="col-md-4">
                            <div class="card border p-3 h-100 bg-light">
                                <h6 class="fw-bold mb-2 text-primary">Pillar 3</h6>
                                <div class="mb-2">
                                    <label class="form-label fs-xs fw-semibold">Eyebrow</label>
                                    <input type="text" name="cms_about_pillar3_eyebrow" class="form-control form-control-sm" value="{{ \App\Models\Setting::get('cms_about_pillar3_eyebrow', '03 · Patronage') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-xs fw-semibold">Title</label>
                                    <input type="text" name="cms_about_pillar3_title" class="form-control form-control-sm" value="{{ \App\Models\Setting::get('cms_about_pillar3_title', 'The Client Care') }}">
                                </div>
                                <div>
                                    <label class="form-label fs-xs fw-semibold">Description</label>
                                    <textarea name="cms_about_pillar3_desc" rows="4" class="form-control form-control-sm">{{ \App\Models\Setting::get('cms_about_pillar3_desc', 'From bespoke bridal appointments to festive wardrobe curations, our client atelier offers one-on-one virtual and in-person consultations with insured worldwide delivery.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Ethical Atelier -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0 text-primary"><i class="ti ti-heart-handshake me-1"></i> 4. Ethical Atelier & Preservation</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Eyebrow</label>
                            <input type="text" name="cms_about_ethics_eyebrow" class="form-control" value="{{ \App\Models\Setting::get('cms_about_ethics_eyebrow', 'Ethical Atelier') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Heading</label>
                            <input type="text" name="cms_about_ethics_heading" class="form-control" value="{{ \App\Models\Setting::get('cms_about_ethics_heading', 'Honoring The Hands That Weave') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Narrative Description</label>
                            <textarea name="cms_about_ethics_desc" rows="4" class="form-control">{{ \App\Models\Setting::get('cms_about_ethics_desc', 'We believe authentic luxury begins with fair dignity. ManaCouture pays our master weavers more than double the regional standard wage, sponsors healthcare for weaver families, and reinvests 5% of all bridal proceeds into handloom preservation trusts across Kerala.') }}</textarea>
                        </div>
                        <div class="col-12">
                            @php
                                $ethicsImg = \App\Models\Setting::get('cms_about_ethics_image', 'images/manacoture/hero-saree.jpg');
                                $ethicsImgSrc = str_starts_with($ethicsImg, 'http') ? $ethicsImg : asset($ethicsImg);
                            @endphp
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $ethicsImgSrc }}" alt="Ethics Preview" class="rounded border" width="120" height="70" style="object-fit: cover;">
                                <div class="flex-grow-1">
                                    <label class="form-label fw-semibold">Replace Ethics Feature Image</label>
                                    <input type="file" name="about_ethics_image_file" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary fw-semibold px-4 py-2">
                <i class="ti ti-check me-1"></i> Save About Page CMS Changes
            </button>
        </div>
    </div>
</form>
@endsection
