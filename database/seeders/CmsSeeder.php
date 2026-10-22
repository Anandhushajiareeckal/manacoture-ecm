<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\Page;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Initial CMS Settings for Homepage and Global Storefront
        $settings = [
            // Global Branding & Announcement
            'cms_site_title' => ['ManaCouture', 'string', 'Brand Title'],
            'cms_site_tagline' => ['Handwoven Kerala Sarees & Bridal Couture', 'string', 'Storefront Tagline'],
            'cms_announcement_active' => ['1', 'boolean', 'Announcement Bar Active'],
            'cms_announcement_text' => ['Complimentary shipping across India · Handwoven in Kerala · New Onam Collection 2026', 'string', 'Announcement Bar Text'],
            'cms_footer_bio' => ['Contemporary heirlooms rooted in the handloom traditions of Kerala. Every weave is crafted by master artisans and delivered with care.', 'string', 'Footer Brand Bio'],
            'cms_social_instagram' => ['https://instagram.com/manacouture', 'string', 'Instagram URL'],
            'cms_social_facebook' => ['https://facebook.com/manacouture', 'string', 'Facebook URL'],
            'cms_social_youtube' => ['https://youtube.com/@manacouture', 'string', 'YouTube URL'],
            'cms_social_whatsapp' => ['https://wa.me/919876543210', 'string', 'WhatsApp Contact URL'],
            'cms_contact_email' => ['atelier@manacouture.com', 'string', 'Contact Email'],
            'cms_contact_phone' => ['+91 (484) 234-5678', 'string', 'Contact Phone'],
            'cms_contact_address' => ['ManaCouture Atelier, Marine Drive, Kochi, Kerala 682031, India', 'string', 'Atelier Address'],

            // Hero Section
            'cms_hero_eyebrow' => ['Onam Collection · 2026', 'string', 'Hero Eyebrow Tag'],
            'cms_hero_title_1' => ['Woven in gold.', 'string', 'Hero Title Line 1'],
            'cms_hero_title_2' => ['Worn like poetry.', 'string', 'Hero Title Line 2 (Italic Serif)'],
            'cms_hero_description' => ['A limited edition of hand-loomed Kasavu sarees, dyed in ivory and edged with antique gold — crafted by third-generation weavers in Balaramapuram.', 'string', 'Hero Paragraph'],
            'cms_hero_btn1_text' => ['Shop New Collection', 'string', 'Hero Button 1 Text'],
            'cms_hero_btn1_url' => ['/shop', 'string', 'Hero Button 1 URL'],
            'cms_hero_btn2_text' => ['Explore Sarees', 'string', 'Hero Button 2 Text'],
            'cms_hero_btn2_url' => ['/shop?c=kerala-sarees', 'string', 'Hero Button 2 URL'],
            'cms_hero_badge_1' => ['Handloom certified', 'string', 'Hero Trust Badge 1'],
            'cms_hero_badge_2' => ['4.9 · 12,400 reviews', 'string', 'Hero Trust Badge 2'],
            'cms_hero_image' => ['images/manacoture/hero-saree.jpg', 'string', 'Hero Feature Image'],

            // Marquee Strip
            'cms_marquee_words' => ['Kasavu, Bridal Silks, Set Mundu, Handloom, Kanchipuram, Onam, Vishu, Temple Wear', 'string', 'Marquee Keywords'],

            // Curated Categories Section
            'cms_categories_eyebrow' => ['Curated Categories', 'string', 'Categories Eyebrow'],
            'cms_categories_heading' => ['The Kerala Weaves', 'string', 'Categories Heading'],

            // Featured Heirlooms Section
            'cms_featured_eyebrow' => ['Featured Heirlooms', 'string', 'Featured Eyebrow'],
            'cms_featured_heading' => ['Handloom Masterpieces', 'string', 'Featured Heading'],
            'cms_featured_btn_text' => ['View Entire Edit', 'string', 'Featured Button Text'],

            // Bridal Spotlight Section
            'cms_bridal_eyebrow' => ['The Bridal Atelier', 'string', 'Bridal Eyebrow'],
            'cms_bridal_heading' => ['Gold woven for a lifetime.', 'string', 'Bridal Heading'],
            'cms_bridal_description' => ['Every Kerala bride deserves a weave that honors tradition while commanding the room. Our bridal collection unites heirloom Kanchipuram silk with hand-beaten certified zari, finished by master artisans in our Kochi studio.', 'string', 'Bridal Description'],
            'cms_bridal_btn1_text' => ['Discover Bridal Silks', 'string', 'Bridal Button 1 Text'],
            'cms_bridal_btn1_url' => ['/shop?c=bridal', 'string', 'Bridal Button 1 URL'],
            'cms_bridal_btn2_text' => ['Book Atelier Appointment', 'string', 'Bridal Button 2 Text'],
            'cms_bridal_btn2_url' => ['/about', 'string', 'Bridal Button 2 URL'],
            'cms_bridal_image' => ['images/manacoture/bridal.jpg', 'string', 'Bridal Editorial Image'],

            // Festival Banner Section
            'cms_festival_eyebrow' => ['Festival · Onam · Vishu', 'string', 'Festival Eyebrow'],
            'cms_festival_heading' => ['A courtyard lit for celebration.', 'string', 'Festival Heading'],
            'cms_festival_description' => ['Discover our festival edit — Kasavu for Onam, silks for Vishu, and temple couture for every sacred occasion.', 'string', 'Festival Description'],
            'cms_festival_btn_text' => ['Shop The Edit', 'string', 'Festival Button Text'],
            'cms_festival_btn_url' => ['/shop?occasion=Festival', 'string', 'Festival Button URL'],
            'cms_festival_image' => ['images/manacoture/festival.jpg', 'string', 'Festival Banner Image'],

            // Best Sellers Section
            'cms_bestsellers_eyebrow' => ['Best Sellers', 'string', 'Best Sellers Eyebrow'],
            'cms_bestsellers_heading' => ['Loved By Many', 'string', 'Best Sellers Heading'],

            // Values & Promise Section
            'cms_promise_eyebrow' => ['The ManaCouture Promise', 'string', 'Promise Eyebrow'],
            'cms_promise_heading' => ['Craft That Lasts A Lifetime', 'string', 'Promise Heading'],

            // About Page
            'cms_about_hero_eyebrow' => ['Since 2018', 'string', 'About Hero Eyebrow'],
            'cms_about_hero_heading' => ['A Kerala atelier for the modern woman.', 'string', 'About Hero Heading'],
            'cms_about_hero_image' => ['images/manacoture/festival.jpg', 'string', 'About Hero Image'],
            'cms_about_quote' => ['We weave for the women who wear their culture with quiet pride — brides, mothers, daughters, and the friends who dance with them under Onam lamps.', 'string', 'Founder Quote'],
            'cms_about_quote_author' => ['— Anjali Menon, Founder & Creative Director', 'string', 'Quote Author'],
            'cms_about_pillar1_eyebrow' => ['01 · Origin', 'string', 'Pillar 1 Eyebrow'],
            'cms_about_pillar1_title' => ['The Balaramapuram Loom', 'string', 'Pillar 1 Title'],
            'cms_about_pillar1_desc' => ['Every ManaCouture Kasavu begins on a hand-pit loom in Balaramapuram, where third-generation master weavers work exclusively with pure zari and combed high-count cotton.', 'string', 'Pillar 1 Description'],
            'cms_about_pillar2_eyebrow' => ['02 · Craftsmanship', 'string', 'Pillar 2 Eyebrow'],
            'cms_about_pillar2_title' => ['The Kochi Atelier', 'string', 'Pillar 2 Title'],
            'cms_about_pillar2_desc' => ['Our Kochi atelier meticulously finishes each creation — hand-hemmed borders, gentle cold pressing, wrapped in breathable natural muslin, and presented in signature reusable keepsake trunks.', 'string', 'Pillar 2 Description'],
            'cms_about_pillar3_eyebrow' => ['03 · Patronage', 'string', 'Pillar 3 Eyebrow'],
            'cms_about_pillar3_title' => ['The Client Care', 'string', 'Pillar 3 Title'],
            'cms_about_pillar3_desc' => ['From bespoke bridal appointments to festive wardrobe curations, our client atelier offers one-on-one virtual and in-person consultations with insured worldwide delivery.', 'string', 'Pillar 3 Description'],
            'cms_about_ethics_eyebrow' => ['Ethical Atelier', 'string', 'Ethics Eyebrow'],
            'cms_about_ethics_heading' => ['Honoring The Hands That Weave', 'string', 'Ethics Heading'],
            'cms_about_ethics_desc' => ['We believe authentic luxury begins with fair dignity. ManaCouture pays our master weavers more than double the regional standard wage, sponsors healthcare for weaver families, and reinvests 5% of all bridal proceeds into handloom preservation trusts across Kerala.', 'string', 'Ethics Description'],
            'cms_about_ethics_image' => ['images/manacoture/hero-saree.jpg', 'string', 'Ethics Section Image'],
        ];

        foreach ($settings as $key => [$value, $type, $label]) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'cms',
                    'type' => $type,
                    'label' => $label,
                    'is_public' => true,
                ]
            );
        }

        // 2. Default Standard CMS Pages
        $pages = [
            [
                'title' => 'Shipping & Delivery',
                'slug' => 'shipping-delivery',
                'excerpt' => 'Details on our complimentary domestic shipping, insured international transit, and discreet packaging.',
                'content' => '<h2>Complimentary Domestic Delivery</h2>
<p>ManaCouture is pleased to provide complimentary fully insured delivery across India on all orders. Each garment is encased in unbleached Kerala cotton muslin bags and protected within our custom rigid atelier keepsake box.</p>
<h3>Dispatch Timeline</h3>
<p>Ready-to-wear items, Kasavu sarees, and dupattas are dispatched from our Kochi atelier within <strong>24 to 48 business hours</strong>. Bespoke bridal orders and customized blouse pieces require <strong>7 to 14 business days</strong> for artisan hand-finishing.</p>
<h3>Express & Worldwide Shipping</h3>
<p>We partner with DHL Express and FedEx International Priority for secure global shipping across the UAE, USA, UK, Singapore, Malaysia, and Australia. Transit typically takes 3 to 5 business days after dispatch.</p>',
                'meta_title' => 'Shipping & Delivery — ManaCouture Kerala',
                'meta_description' => 'Learn about ManaCouture shipping timelines, packaging, and international delivery options.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Returns & Exchanges',
                'slug' => 'returns-exchanges',
                'excerpt' => 'Effortless 14-day return and exchange policy for unworn heirlooms.',
                'content' => '<h2>Our Return Guarantee</h2>
<p>We want you to treasure every heirloom you receive from ManaCouture. If your piece does not completely enchant you, we are honored to offer a <strong>14-day complimentary exchange or full refund</strong> on all eligible items.</p>
<h3>Conditions for Return</h3>
<ul>
    <li>The item must be unworn, unwashed, and undamaged with all original security tags and fabric labels intact.</li>
    <li>Must include the signature wooden presentation box and muslin storage cover.</li>
    <li>Custom-cut blouse fabrics and personalized bridal monograms are final sale.</li>
</ul>
<h3>How to Initiate</h3>
<p>Please log in to your Atelier Account or write to <strong>atelier@manacoture.com</strong> with your order number. Our client concierge will arrange doorstep courier pickup at no cost to you.</p>',
                'meta_title' => 'Returns & Exchanges — ManaCouture',
                'meta_description' => 'Effortless 14-day returns and exchanges for your handloom saree and couture orders.',
                'sort_order' => 2,
            ],
            [
                'title' => 'Handloom Care Guide',
                'slug' => 'handloom-care-guide',
                'excerpt' => 'Preserving the luster of your Kasavu zari, pure cottons, and heirloom silks for generations.',
                'content' => '<h2>Preserving Your Heirlooms</h2>
<p>A handloom saree woven with genuine metallic zari is a living textile that softens and grows more radiant over time when tended with gentle respect.</p>
<h3>1. Storage</h3>
<p>Always store your Kasavu sarees and silks wrapped in clean, dry cotton or unbleached muslin cloths. Avoid plastic covers, which trap moisture and can tarnish metallic threads.</p>
<h3>2. Folding & Refolding</h3>
<p>Refold your sarees along different creases every 3 to 4 months to prevent stress wear along the fold lines.</p>
<h3>3. Cleaning & Washing</h3>
<p>Pure silk and high-zari bridal weaves must exclusively be dry-cleaned by a heritage textile specialist. For cotton Kasavu, cold hand rinse with mild fabric soap is permissible. Never wring or spin-dry; dry flat in shaded breeze.</p>',
                'meta_title' => 'Handloom Care Guide — ManaCouture',
                'meta_description' => 'Care instructions for handwoven Kasavu sarees and pure silk garments.',
                'sort_order' => 3,
            ],
            [
                'title' => 'Authenticity Certificate',
                'slug' => 'authenticity-certificate',
                'excerpt' => 'Understanding our certified handloom origin, pure zari purity, and artisan heritage.',
                'content' => '<h2>Craft Integrity & Handloom Mark</h2>
<p>Every ManaCouture creation is accompanied by our serialized Certificate of Authenticity. We are a registered patron of the Ministry of Textiles Handloom Mark and Silk Mark of India initiatives.</p>
<h3>Our Three Guarantees</h3>
<ol>
    <li><strong>Authentic Origin:</strong> Woven exclusively on traditional pit-looms in Kerala by recognized weaver clusters in Balaramapuram, Chendamangalam, and Kanchipuram.</li>
    <li><strong>Tested Zari Purity:</strong> Certified electroplated metallic silver and gold threads that withstand decades without flaking.</li>
    <li><strong>Ethical Wages:</strong> We directly support 48 master weaver households with wages exceeding fair-trade artisan minimums.</li>
</ol>',
                'meta_title' => 'Authenticity & Handloom Certification — ManaCouture',
                'meta_description' => 'Official certificate of authenticity, handloom mark and artisan provenance.',
                'sort_order' => 4,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'How we protect your personal information, measurements, and payment data.',
                'content' => '<h2>Your Privacy Matters</h2>
<p>ManaCouture is committed to safeguarding the privacy and confidential personal details of all our patrons. We never sell, rent, or trade your contact information with external marketing brokers.</p>
<h3>Data We Collect</h3>
<p>We only collect the details necessary to fulfill your orders, arrange bespoke fitting consultations, and deliver transactional notifications (such as shipment tracking numbers and invoice PDFs).</p>
<h3>Secure Payments</h3>
<p>All credit card and UPI transactions are encrypted via industry-leading PCI-DSS certified payment gateways (Razorpay, Stripe). We do not store raw card numbers on our servers.</p>',
                'meta_title' => 'Privacy Policy — ManaCouture',
                'meta_description' => 'Privacy policy detailing data security and patron confidentiality at ManaCouture.',
                'sort_order' => 5,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'excerpt' => 'General terms governing purchases, appointments, and use of the ManaCouture storefront.',
                'content' => '<h2>Atelier Terms & Conditions</h2>
<p>Welcome to ManaCouture. By accessing our website and placing orders with our atelier, you agree to be bound by the following standard terms.</p>
<h3>Handloom Variations</h3>
<p>Because every saree is hand-woven by human artisans on non-mechanized looms, subtle variations in thread tension, selvedge texture, and zari hue are not defects — they are the distinctive hallmarks of genuine handloom heritage.</p>
<h3>Pricing & Currency</h3>
<p>All prices are listed in Indian Rupees (INR) and include applicable Goods and Services Tax (GST). International orders may be subject to local import customs duties upon entry into your country.</p>',
                'meta_title' => 'Terms of Service — ManaCouture',
                'meta_description' => 'Terms of service and purchasing agreements for ManaCouture patrons.',
                'sort_order' => 6,
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'content' => $p['content'],
                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],
                    'sort_order' => $p['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
