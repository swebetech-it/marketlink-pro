<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use App\Models\Course;
use App\Models\FinancialProduct;
use App\Models\NotificationItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic Tanzania market opportunities.
     */
    public function run(): void
    {
        // 1. Seed System Users
        $admin = User::updateOrCreate(
            ['phone' => '+255 700 000 000'],
            [
                'id' => 'admin_portal',
                'full_name' => 'Swebetech System Admin',
                'phone' => '+255 700 000 000',
                'email' => 'admin@swebetech.co.tz',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'ADMIN',
                'verification_status' => 'VERIFIED',
                'nida_number' => 'ADMIN-SYS-01',
                'location' => 'Mbeya City, Tanzania',
                'bio' => 'MarketLink Pro National System Operations & Verification Center',
                'skills' => ['System Administration', 'KYC Verification', 'Escrow Arbitration'],
                'rating' => 5.0,
                'reputation_score' => 1000,
                'community_vouches' => 50,
                'badges' => ['System Administrator', 'Verified Ops']
            ]
        );

        $amani = User::updateOrCreate(
            ['phone' => '+255 754 112 233'],
            [
                'id' => 'usr_amani_01',
                'full_name' => 'Amani Juma Swebetech',
                'phone' => '+255 754 112 233',
                'email' => 'amani.juma@swebetech.co.tz',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'JOB_SEEKER',
                'account_type' => 'INDIVIDUAL',
                'primary_profession' => 'ELECTRICAL',
                'district' => 'Uyole, Mbeya Mjini',
                'verification_status' => 'VERIFIED',
                'nida_number' => '19950101-12345-00001-22',
                'location' => 'Mbeya City (Uyole)',
                'bio' => 'Certified Solar Power Technician and Agri-Equipment Operator with 6+ years experience.',
                'skills' => ['Solar Grid', 'Tractor Driving', 'Electrical Wiring', 'Inverter Repairs'],
                'rating' => 4.9,
                'total_transactions' => 14,
                'completed_gigs' => 12,
                'reputation_score' => 880,
                'community_vouches' => 8,
                'badges' => ['NIDA Verified', 'Solar Specialist', 'Top Rated 2026']
            ]
        );

        // Dedicated Cleaning / Sanitation Worker (Usafi) in Mbeya
        $asha = User::updateOrCreate(
            ['phone' => '+255 755 223 344'],
            [
                'id' => 'usr_asha_04',
                'full_name' => 'Asha Bakari Mwansasu',
                'phone' => '+255 755 223 344',
                'email' => 'asha.bakari@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'JOB_SEEKER',
                'account_type' => 'INDIVIDUAL',
                'primary_profession' => 'CLEANING_SANITATION',
                'district' => 'Mwanjelwa, Mbeya Mjini',
                'verification_status' => 'VERIFIED',
                'nida_number' => '19980312-44122-00005-19',
                'location' => 'Mbeya City (Mwanjelwa)',
                'bio' => 'Mtaalamu wa usafi wa kina wa majumbani na maofisini (Deep Cleaning, Fumigation, and Floor Polish). Uzoefu miaka 4.',
                'skills' => ['Usafi wa Ofisi', 'Usafi wa Majumbani', 'Kupiga Dawa ya Wadudu', 'Kuosha Mazulia', 'Housekeeping', 'Sanitation'],
                'rating' => 5.0,
                'total_transactions' => 19,
                'completed_gigs' => 18,
                'reputation_score' => 920,
                'community_vouches' => 14,
                'badges' => ['NIDA Verified', 'Super Cleaner', 'Top Rated 2026']
            ]
        );

        // Dedicated Security Guard (Ulinzi) in Mbeya
        $bakari = User::updateOrCreate(
            ['phone' => '+255 712 334 455'],
            [
                'id' => 'usr_bakari_05',
                'full_name' => 'Bakari Salum Mussa',
                'phone' => '+255 712 334 455',
                'email' => 'bakari.salum@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'JOB_SEEKER',
                'account_type' => 'INDIVIDUAL',
                'primary_profession' => 'SECURITY',
                'district' => 'Mbalizi, Mbeya Mjini',
                'verification_status' => 'VERIFIED',
                'nida_number' => '19920914-11233-00006-25',
                'location' => 'Mbeya City (Mbalizi)',
                'bio' => 'Askari wa ulinzi mwenye cheti cha JKT na mafunzo ya usalama wa majengo na CCTV.',
                'skills' => ['Ulinzi wa Majengo', 'CCTV Monitoring', 'Doria za Usiku', 'First Aid'],
                'rating' => 4.9,
                'total_transactions' => 11,
                'completed_gigs' => 10,
                'reputation_score' => 870,
                'community_vouches' => 9,
                'badges' => ['NIDA Verified', 'Certified Guard']
            ]
        );

        // Professional Accountant / CPA in Dar es Salaam
        $grace = User::updateOrCreate(
            ['phone' => '+255 768 556 677'],
            [
                'id' => 'usr_grace_06',
                'full_name' => 'Grace Mushi (CPA-T)',
                'phone' => '+255 768 556 677',
                'email' => 'grace.mushi@cpa.co.tz',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'JOB_SEEKER',
                'account_type' => 'INDIVIDUAL',
                'primary_profession' => 'ACCOUNTING_FINANCE',
                'district' => 'Kinondoni, Dar es Salaam',
                'verification_status' => 'VERIFIED',
                'nida_number' => '19910520-22114-00007-16',
                'location' => 'Dar es Salaam (Kinondoni)',
                'bio' => 'CPA Certified Senior Accountant with 7 years experience in TRA Tax Returns, Tally Prime, and Financial Auditing.',
                'skills' => ['Uhasibu', 'Tax Returns', 'TRA EFD Management', 'Financial Auditing', 'Tally Prime'],
                'rating' => 5.0,
                'total_transactions' => 15,
                'completed_gigs' => 15,
                'reputation_score' => 950,
                'community_vouches' => 18,
                'badges' => ['NIDA Verified', 'CPA-T', 'Finance Specialist']
            ]
        );

        // Organization / Company Employer: Mbeya Commercial Complex
        $mall = User::updateOrCreate(
            ['phone' => '+255 759 999 888'],
            [
                'id' => 'org_mbeya_complex',
                'full_name' => 'Mbeya City Commercial Complex Ltd',
                'organization_name' => 'Mbeya City Commercial Complex Ltd',
                'tin_number' => '142-980-311',
                'industry_sector' => 'Real Estate & Facility Management',
                'phone' => '+255 759 999 888',
                'email' => 'operations@mbeyacitycomplex.co.tz',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'EMPLOYER',
                'account_type' => 'ORGANIZATION',
                'primary_profession' => 'MANAGEMENT',
                'district' => 'Sokoine, Mbeya Mjini',
                'verification_status' => 'VERIFIED',
                'nida_number' => 'ORG-MBY-2026-01',
                'location' => 'Mbeya City, Tanzania',
                'bio' => 'Wamiliki na wasimamizi wa majengo ya kibiashara, ofisi na maduka jijini Mbeya.',
                'skills' => ['Facility Management', 'Commercial Leasing', 'Corporate Contracts'],
                'rating' => 4.9,
                'total_transactions' => 35,
                'completed_gigs' => 32,
                'reputation_score' => 960,
                'community_vouches' => 22,
                'badges' => ['Verified Organization', 'BRELA Verified', 'Escrow Trusted']
            ]
        );

        $rehema = User::updateOrCreate(
            ['phone' => '+255 784 998 877'],
            [
                'id' => 'usr_rehema_02',
                'full_name' => 'Rehema Mwangi (Kilombero AMCOS)',
                'organization_name' => 'Kilombero AMCOS Cooperative Society',
                'tin_number' => '118-452-901',
                'phone' => '+255 784 998 877',
                'email' => 'rehema@kilomberoamcos.co.tz',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'FARMER_COOP',
                'account_type' => 'ORGANIZATION',
                'primary_profession' => 'AGRICULTURE',
                'district' => 'Kilombero Valley',
                'verification_status' => 'VERIFIED',
                'nida_number' => '19890415-54321-00002-18',
                'location' => 'Kilombero, Morogoro',
                'bio' => 'Operations Manager at Kilombero Valley Agricultural Cooperative.',
                'skills' => ['AMCOS Logistics', 'Rice Production', 'Agri-Labor Sourcing'],
                'rating' => 5.0,
                'total_transactions' => 28,
                'completed_gigs' => 25,
                'reputation_score' => 940,
                'community_vouches' => 19,
                'badges' => ['NIDA Verified', 'AMCOS Leader', 'Escrow Trusted']
            ]
        );

        $joseph = User::updateOrCreate(
            ['phone' => '+255 767 334 455'],
            [
                'id' => 'usr_joseph_03',
                'full_name' => 'Joseph Tarimo Heavy Machinery',
                'organization_name' => 'Tarimo Heavy Machinery & Logistics',
                'tin_number' => '130-771-429',
                'phone' => '+255 767 334 455',
                'email' => 'joseph.tarimo@mbeyamachines.co.tz',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'EMPLOYER',
                'account_type' => 'ORGANIZATION',
                'primary_profession' => 'MECHANICS',
                'district' => 'Mbarali District',
                'verification_status' => 'VERIFIED',
                'nida_number' => '19820708-33211-00003-14',
                'location' => 'Mbarali, Mbeya',
                'bio' => 'Fleet owner of tractors, combine harvesters, and precision irrigation rigs.',
                'skills' => ['Tractor Fleet Management', 'Heavy Equipment Leasing', 'Diesel Mechanics'],
                'rating' => 4.8,
                'total_transactions' => 42,
                'completed_gigs' => 39,
                'reputation_score' => 910,
                'community_vouches' => 15,
                'badges' => ['NIDA Verified', 'Verified Fleet Partner']
            ]
        );

        // 2. Seed Listings Across All Categories
        $listings = [
            // SKILLED SERVICE
            [
                'id' => 'lst_solar_01',
                'poster_id' => $admin->id,
                'poster_name' => 'Swebetech Energy Solutions',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 5.0,
                'category' => 'SKILLED_SERVICE',
                'title' => 'Solar Micro-Grid Installation & Inverter Technician',
                'description' => 'Looking for certified solar technicians to install a 50kVA hybrid solar system with lithium battery storage in Mbeya agricultural processing hub.',
                'location' => 'Mbeya City (Mbalizi)',
                'compensation' => 'TZS 1,200,000 / project',
                'compensation_amount_tzs' => 1200000,
                'required_skills' => ['Solar Grid', 'Inverter Repairs', 'Electrical Wiring', 'Safety Protocols'],
                'date_posted' => date('Y-m-d', strtotime('-2 days')),
                'deadline' => date('Y-m-d', strtotime('+28 days')),
                'is_featured' => true,
                'is_escrow_required' => true,
                'match_score' => 95,
                'status' => 'ACTIVE',
            ],
            [
                'id' => 'lst_weld_02',
                'poster_id' => $joseph->id,
                'poster_name' => 'Joseph Tarimo Heavy Machinery',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 4.8,
                'category' => 'SKILLED_SERVICE',
                'title' => 'Certified Welder for Grain Storage Silos & Feed Mixers',
                'description' => 'Urgent requirement for an ARC/MIG welder to assemble structural steel frames and fabricate 50-ton grain storage silos.',
                'location' => 'Makambako, Njombe',
                'compensation' => 'TZS 850,000 / unit',
                'compensation_amount_tzs' => 850000,
                'required_skills' => ['MIG/TIG Welding', 'Structural Steel', 'Blueprint Reading'],
                'date_posted' => date('Y-m-d', strtotime('-1 days')),
                'deadline' => date('Y-m-d', strtotime('+20 days')),
                'is_featured' => false,
                'is_escrow_required' => true,
                'match_score' => 88,
                'status' => 'ACTIVE',
            ],
            [
                'id' => 'lst_irrigation_03',
                'poster_id' => $admin->id,
                'poster_name' => 'Rungwe Horticulture Alliance',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 5.0,
                'category' => 'SKILLED_SERVICE',
                'title' => 'Smart IoT Drip Irrigation System Setup Specialist',
                'description' => 'Install solar-powered automated drip irrigation lines, moisture sensors, and pressure valves for 25 acres of commercial avocado farming.',
                'location' => 'Tukuyu, Rungwe, Mbeya',
                'compensation' => 'TZS 950,000 / installation',
                'compensation_amount_tzs' => 950000,
                'required_skills' => ['Drip Irrigation', 'Solar Pumps', 'IoT Sensors', 'Plumbing'],
                'date_posted' => date('Y-m-d'),
                'deadline' => date('Y-m-d', strtotime('+25 days')),
                'is_featured' => true,
                'is_escrow_required' => true,
                'match_score' => 92,
                'status' => 'ACTIVE',
            ],

            // AGRI LABOR
            [
                'id' => 'lst_paddy_04',
                'poster_id' => $rehema->id,
                'poster_name' => 'Rehema Mwangi (Kilombero AMCOS)',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 5.0,
                'category' => 'AGRI_LABOR',
                'title' => 'Paddy Rice Seasonal Harvesters (35 Workers Required)',
                'description' => 'Recruiting agricultural seasonal laborers for rice cutting, threshing, and bagging in Kilombero valley basin. Meals and camp provided.',
                'location' => 'Kilombero, Morogoro',
                'compensation' => 'TZS 25,000 / day',
                'compensation_amount_tzs' => 25000,
                'required_skills' => ['Paddy Harvesting', 'Threshing', 'Bagging', 'Field Labor'],
                'date_posted' => date('Y-m-d', strtotime('-3 days')),
                'deadline' => date('Y-m-d', strtotime('+15 days')),
                'is_featured' => true,
                'is_escrow_required' => true,
                'match_score' => 90,
                'agri_crop_type' => 'Paddy Rice (Super India)',
                'status' => 'ACTIVE',
            ],
            [
                'id' => 'lst_tea_05',
                'poster_id' => $admin->id,
                'poster_name' => 'Southern Highlands Tea Estate',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 4.9,
                'category' => 'AGRI_LABOR',
                'title' => 'Two-Leaf Green Tea Pluckers & Quality Sorters',
                'description' => 'Immediate positions for experienced green tea pluckers in Rungwe tea plantations. Daily digital weight recording and weekly escrow payouts.',
                'location' => 'Rungwe District, Mbeya',
                'compensation' => 'TZS 350 / kg plucked',
                'compensation_amount_tzs' => 350,
                'required_skills' => ['Tea Plucking', 'Quality Grading', 'Harvest Speed'],
                'date_posted' => date('Y-m-d', strtotime('-1 days')),
                'deadline' => date('Y-m-d', strtotime('+30 days')),
                'is_featured' => false,
                'is_escrow_required' => true,
                'match_score' => 86,
                'agri_crop_type' => 'Green Tea Leaf',
                'status' => 'ACTIVE',
            ],
            [
                'id' => 'lst_avo_06',
                'poster_id' => $rehema->id,
                'poster_name' => 'Mbeya Avocado Exporters Cooperative',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 5.0,
                'category' => 'AGRI_LABOR',
                'title' => 'Export-Grade Hass Avocado Picking & Packing Laborers',
                'description' => 'Careful harvesting and sorting of Hass avocados meeting GlobalGAP export standards. Must use sanitized clippers and padded crates.',
                'location' => 'Uyole & Kyela, Mbeya',
                'compensation' => 'TZS 30,000 / day',
                'compensation_amount_tzs' => 30000,
                'required_skills' => ['Fruit Picking', 'GlobalGAP Standards', 'Sorting & Packing'],
                'date_posted' => date('Y-m-d'),
                'deadline' => date('Y-m-d', strtotime('+22 days')),
                'is_featured' => true,
                'is_escrow_required' => true,
                'match_score' => 91,
                'agri_crop_type' => 'Hass Avocado',
                'status' => 'ACTIVE',
            ],

            // EQUIPMENT RENTAL
            [
                'id' => 'lst_trac_07',
                'poster_id' => $joseph->id,
                'poster_name' => 'Joseph Tarimo Heavy Machinery',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 4.8,
                'category' => 'EQUIPMENT_RENTAL',
                'title' => 'John Deere 75HP 4WD Tractor with 3-Disc Plough',
                'description' => 'Available for heavy land preparation, deep harrowing, and ridge making in Mbarali plains. Includes fuel and licensed operator.',
                'location' => 'Mbarali, Mbeya',
                'compensation' => 'TZS 85,000 / acre',
                'compensation_amount_tzs' => 85000,
                'required_skills' => ['Tractor Operation', 'Field Ploughing', 'Land Harrowing'],
                'date_posted' => date('Y-m-d', strtotime('-4 days')),
                'deadline' => date('Y-m-d', strtotime('+45 days')),
                'is_featured' => true,
                'is_escrow_required' => true,
                'match_score' => 96,
                'equipment_details' => 'John Deere 5075E 4WD with Heavy Disc Plough',
                'status' => 'ACTIVE',
            ],
            [
                'id' => 'lst_combine_08',
                'poster_id' => $joseph->id,
                'poster_name' => 'Joseph Tarimo Heavy Machinery',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 4.8,
                'category' => 'EQUIPMENT_RENTAL',
                'title' => 'Yanmar Crawler Combine Harvester for Wet Paddy',
                'description' => 'High efficiency tracked combine harvester for wet and muddy rice fields in Morogoro and Mbeya. 98% grain recovery guarantee.',
                'location' => 'Kilombero, Morogoro',
                'compensation' => 'TZS 115,000 / acre',
                'compensation_amount_tzs' => 115000,
                'required_skills' => ['Combine Operation', 'Grain Cleaning', 'Track Maintenance'],
                'date_posted' => date('Y-m-d', strtotime('-2 days')),
                'deadline' => date('Y-m-d', strtotime('+35 days')),
                'is_featured' => true,
                'is_escrow_required' => true,
                'match_score' => 93,
                'equipment_details' => 'Yanmar AW70V Full-Feed Combine Harvester',
                'status' => 'ACTIVE',
            ],

            // EMPLOYMENT
            [
                'id' => 'lst_emp_09',
                'poster_id' => $admin->id,
                'poster_name' => 'Highland Agri-Logistics Co.',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 5.0,
                'category' => 'EMPLOYMENT',
                'title' => 'Full-Time Agricultural Operations & Cold Chain Supervisor',
                'description' => 'Managing post-harvest aggregation centers, temperature logs, driver manifests, and dispatch to Dar es Salaam ports.',
                'location' => 'Iringa Municipality, Tanzania',
                'compensation' => 'TZS 1,600,000 / month',
                'compensation_amount_tzs' => 1600000,
                'required_skills' => ['Supply Chain', 'Cold Chain Management', 'Inventory Control', 'Driver Coordination'],
                'date_posted' => date('Y-m-d', strtotime('-5 days')),
                'deadline' => date('Y-m-d', strtotime('+20 days')),
                'is_featured' => false,
                'is_escrow_required' => false,
                'match_score' => 87,
                'status' => 'ACTIVE',
            ],
            [
                'id' => 'lst_emp_10',
                'poster_id' => $admin->id,
                'poster_name' => 'Tanzania Bio-Crop Labs',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 5.0,
                'category' => 'EMPLOYMENT',
                'title' => 'Field Agronomist & Soil Nutrition Advisor',
                'description' => 'Conduct soil pH testing, formulate organic compost regimens, and guide farmers on pest IPM protocols in Arusha region.',
                'location' => 'Arusha City, Tanzania',
                'compensation' => 'TZS 1,800,000 / month',
                'compensation_amount_tzs' => 1800000,
                'required_skills' => ['Soil Science', 'Crop Pathology', 'Agronomy', 'Field Advisory'],
                'date_posted' => date('Y-m-d', strtotime('-1 days')),
                'deadline' => date('Y-m-d', strtotime('+30 days')),
                'is_featured' => true,
                'is_escrow_required' => false,
                'match_score' => 90,
                'status' => 'ACTIVE',
            ],

            // CASUAL GIG
            [
                'id' => 'lst_gig_11',
                'poster_id' => $rehema->id,
                'poster_name' => 'Mwanjelwa Grain Wholesalers',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 4.9,
                'category' => 'CASUAL_GIG',
                'title' => 'Grain Bag Loading & Truck Offloading Crew',
                'description' => 'Seeking energetic laborers for loading 100kg maize and bean sacks onto long-haul cargo trucks in Mwanjelwa commercial market.',
                'location' => 'Mbeya City (Mwanjelwa)',
                'compensation' => 'TZS 40,000 / day',
                'compensation_amount_tzs' => 40000,
                'required_skills' => ['Physical Stamina', 'Warehouse Stacking', 'Safe Handling'],
                'date_posted' => date('Y-m-d'),
                'deadline' => date('Y-m-d', strtotime('+10 days')),
                'is_featured' => false,
                'is_escrow_required' => true,
                'match_score' => 84,
                'status' => 'ACTIVE',
            ],

            // BUSINESS OPPORTUNITY
            [
                'id' => 'lst_biz_12',
                'poster_id' => $admin->id,
                'poster_name' => 'Swebetech Eco-Inputs Tanzania',
                'poster_verification' => 'VERIFIED',
                'poster_rating' => 5.0,
                'category' => 'BUSINESS_OPP',
                'title' => 'Regional Bio-Fertilizer Sub-Distributor Franchise',
                'description' => 'Exclusive distribution rights for certified eco-friendly organic fertilizers in Dodoma and Singida districts. Marketing collateral and technical training included.',
                'location' => 'Dodoma Capital, Tanzania',
                'compensation' => 'TZS 4,500,000 initial stock',
                'compensation_amount_tzs' => 4500000,
                'required_skills' => ['Agro-Dealer License', 'B2B Sales', 'Retail Network'],
                'date_posted' => date('Y-m-d', strtotime('-3 days')),
                'deadline' => date('Y-m-d', strtotime('+60 days')),
                'is_featured' => true,
                'is_escrow_required' => true,
                'match_score' => 94,
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($listings as $listData) {
            Listing::updateOrCreate(['id' => $listData['id']], $listData);
        }

        // 3. Seed Vocational Courses
        $courses = [
            [
                'id' => 'crs_01',
                'title' => 'Solar Micro-Grid & Inverter Sizing Masterclass',
                'provider_name' => 'VETA Mbeya Regional Centre',
                'category' => 'Renewable Energy',
                'duration' => '4 Weeks',
                'price_tzs' => 180000,
                'rating' => 4.9,
                'skill_badge_name' => 'Certified Solar Tech',
            ],
            [
                'id' => 'crs_02',
                'title' => 'Modern Drip Irrigation & IoT Soil Sensors',
                'provider_name' => 'SIDO Industrial Incubator Morogoro',
                'category' => 'Agri-Tech',
                'duration' => '2 Weeks',
                'price_tzs' => 120000,
                'rating' => 4.8,
                'skill_badge_name' => 'Precision Irrigation Specialist',
            ],
            [
                'id' => 'crs_03',
                'title' => 'Heavy Tractor Operation & Daily Maintenance',
                'provider_name' => 'Tanzania Agri-Machinery Institute (Iringa)',
                'category' => 'Heavy Equipment',
                'duration' => '3 Weeks',
                'price_tzs' => 250000,
                'rating' => 4.95,
                'skill_badge_name' => 'Licensed Tractor Operator',
            ],
            [
                'id' => 'crs_04',
                'title' => 'GlobalGAP Horticultural Export Quality Standards',
                'provider_name' => 'Tanzania Horticultural Association (TAHA)',
                'category' => 'Food Safety & Export',
                'duration' => '1 Week',
                'price_tzs' => 80000,
                'rating' => 5.0,
                'skill_badge_name' => 'GlobalGAP Certified Auditor',
            ]
        ];

        foreach ($courses as $c) {
            Course::updateOrCreate(['id' => $c['id']], $c);
        }

        // 4. Seed Financial Partner Products
        $products = [
            [
                'id' => 'fin_01',
                'provider_name' => 'CRDB Bank Tanzania',
                'product_name' => 'Mkulima Kilimo Biashara Input Facility',
                'category' => 'Agri-Input Microloan',
                'max_amount_tzs' => 15000000,
                'interest_rate' => '9.5% p.a.',
                'min_reputation_score' => 750,
                'description' => 'Fast working capital loan for seeds, solar irrigation pumps, and organic fertilizers with harvest-season flexible repayment.',
            ],
            [
                'id' => 'fin_02',
                'provider_name' => 'NMB Bank Plc',
                'product_name' => 'Wakulima Equipment & Tractor Lease',
                'category' => 'Equipment Lease',
                'max_amount_tzs' => 45000000,
                'interest_rate' => '11.0% p.a.',
                'min_reputation_score' => 820,
                'description' => 'Up to 36 months lease financing for tractors, combined harvesters, and solar cold rooms for AMCOS cooperatives and individual contractors.',
            ],
            [
                'id' => 'fin_03',
                'provider_name' => 'Vodacom M-Pesa Micro-Finance',
                'product_name' => 'NIDA Instant Gig Float',
                'category' => 'Working Capital',
                'max_amount_tzs' => 1000000,
                'interest_rate' => '2.0% per gig',
                'min_reputation_score' => 700,
                'description' => 'Instant mobile money advance for transport and equipment rental for NIDA-verified workers undertaking escrow gigs.',
            ]
        ];

        foreach ($products as $p) {
            FinancialProduct::updateOrCreate(['id' => $p['id']], $p);
        }
    }
}

