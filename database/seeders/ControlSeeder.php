<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Control;
use App\Models\ItCategory;
use Illuminate\Database\Seeder;

class ControlSeeder extends Seeder
{
    /**
     * Seed control rows sourced from the Excel template screenshot.
     *
     * Application : SAP S/4HANA
     * Period      : Year 2026, Quarter Q1
     *
     * Columns (mirror the Excel file):
     *   uic | application_id | it_category_id |
     *   it_control_id | control_description | status_control
     *
     * Status Control values:
     *   not_started      → "Not Started Yet"
     *   ongoing_review   → "On Going Review"
     *   ongoing_approval → "On Going Approval"
     *   completed        → "Completed"
     */
    public function run(): void
    {
        $app  = Application::where('name', 'SAP S/4HANA')->firstOrFail();
        $year    = '2026';
        $quarter = 'q1';

        // Resolve category IDs once
        $catAccess = ItCategory::where('name', 'Access to Programs & Data')->firstOrFail();
        $catChange = ItCategory::where('name', 'Program Change')->firstOrFail();
        $catOps    = ItCategory::where('name', 'Computer Operations')->firstOrFail();
        $catDev    = ItCategory::where('name', 'Program Development')->firstOrFail();

        /*
         * ─────────────────────────────────────────────────────────────────
         * Control rows — transcribed from the Excel template screenshot.
         * UIC field is left blank; fill once actual UIC codes are available.
         * ─────────────────────────────────────────────────────────────────
         */
        $controls = [

            /* ── Access to Programs & Data ──────────────────────────── */
            [
                'uic'                 => '',
                'it_category_id'      => $catAccess->id,
                'it_control_id'       => 'C-IT-01',
                'control_description' => 'Pembuatan, suspending (penghentian hak akses), dan penutupan user accounts dilakukan sesuai dengan prosedur dan',
                'status_control'      => 'not_started',
                'status_it_category'  => 'partial_completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catAccess->id,
                'it_control_id'       => 'C-IT-02',
                'control_description' => 'Proses review user akses dilakukan secara periodik untuk memastikan pengendalian hak akses user.',
                'status_control'      => 'ongoing_review',
                'status_it_category'  => 'partial_completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catAccess->id,
                'it_control_id'       => 'C-IT-03',
                'control_description' => 'Aktivitas yang berdampak pada keamanan IT dimonitor, dicatat dan didokumentasikan oleh administrator IT untuk mengidentifikasi adanya pelanggaran keamanan.',
                'status_control'      => 'ongoing_approval',
                'status_it_category'  => 'partial_completed',
            ],

            /* ── Program Change ─────────────────────────────────────── */
            [
                'uic'                 => '',
                'it_category_id'      => $catChange->id,
                'it_control_id'       => 'C-IT-05',
                'control_description' => 'Dokumen permintaan program changes, dan pemeliharaan aplikasi telah distandarkan, tercatat dalam log, disetujui, didokumentasikan ke dalam prosedur formal',
                'status_control'      => 'completed',
                'status_it_category'  => 'completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catChange->id,
                'it_control_id'       => 'C-IT-06',
                'control_description' => 'Emergency changes dilakukan dan didokumentasi secara lengkap dengan persetujuan dari manajemen IT serta direview',
                'status_control'      => 'completed',
                'status_it_category'  => 'completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catChange->id,
                'it_control_id'       => 'C-IT-07',
                'control_description' => 'Dilakukan pemisahan Aplikasi lingkungan pengembangan dan lingkungan produksi.',
                'status_control'      => 'completed',
                'status_it_category'  => 'completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catChange->id,
                'it_control_id'       => 'C-IT-08',
                'control_description' => 'Pemisahan tugas dan fungsi (segregation of duties) yg memadai antara personil yg bertanggung jawab utk memindahkan suatu program ke Lingkungan Produksi dari personil yg bertanggungjawab di Lingkungan Pengembangan serta direview',
                'status_control'      => 'completed',
                'status_it_category'  => 'completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catChange->id,
                'it_control_id'       => 'C-IT-09',
                'control_description' => 'Pengujian dilakukan untuk mengkonfirmasi bahwa software dan infrastruktur jaringan telah dikonfigurasi dengan mengacu kepada persyaratan sekuriti pada kebijakan yang berlaku',
                'status_control'      => 'completed',
                'status_it_category'  => 'completed',
            ],

            /* ── Computer Operations ───────────────────────────────── */
            [
                'uic'                 => '',
                'it_category_id'      => $catOps->id,
                'it_control_id'       => 'C-IT-11',
                'control_description' => 'Manajemen perusahaan telah memiliki strategi backup dan recovery atas data dan program yang memadai',
                'status_control'      => 'not_started',
                'status_it_category'  => 'not_completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catOps->id,
                'it_control_id'       => 'C-IT-12',
                'control_description' => 'Dilakukan uji restorasi periodik terhadap backup informasi',
                'status_control'      => 'not_started',
                'status_it_category'  => 'not_completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catOps->id,
                'it_control_id'       => 'C-IT-13',
                'control_description' => 'Melakukan review log batch processing',
                'status_control'      => 'not_started',
                'status_it_category'  => 'not_completed',
            ],

            /* ── Program Development ────────────────────────────────── */
            [
                'uic'                 => '',
                'it_category_id'      => $catDev->id,
                'it_control_id'       => 'C-IT-15',
                'control_description' => 'Strategi testing disusun dan dipatuhi untuk setiap perubahan yang signifikan pada aplikasi dan infrastruktur, yang meliputi : pengujian unit, sistem, interface, integrasi dan UAT, sehingga sistem dapat diimplementasikan sesuai kebutuhan.',
                'status_control'      => 'not_started',
                'status_it_category'  => 'not_completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catDev->id,
                'it_control_id'       => 'C-IT-16',
                'control_description' => 'Konversi data antara sumber dan tujuan telah diuji untuk memastikan bahwa data adalah lengkap, akurat dan valid.',
                'status_control'      => 'not_started',
                'status_it_category'  => 'not_completed',
            ],
            [
                'uic'                 => '',
                'it_category_id'      => $catDev->id,
                'it_control_id'       => 'C-IT-17',
                'control_description' => 'Pemisahan tugas dan fungsi (segregation of duties) yg memadai antara personil yg bertanggung jawab utk memindahkan suatu program ke Lingkungan Produksi dari personil yg bertanggungjawab di Lingkungan Pengembangan.',
                'status_control'      => 'not_started',
                'status_it_category'  => 'not_completed',
            ],

            /* ── Computer Operations (continued) ───────────────────── */
            [
                'uic'                 => '',
                'it_category_id'      => $catOps->id,
                'it_control_id'       => 'C-IT-18',
                'control_description' => 'Melaksanakan Capacity Planning secara berkala dengan memprojeksikan kebutuhan kapasitas berdasarkan tren pertumbuhan transaksi data',
                'status_control'      => 'not_started',
                'status_it_category'  => 'not_completed',
            ],
        ];

        foreach ($controls as $row) {
            Control::updateOrCreate(
                [
                    'application_id' => $app->id,
                    'it_category_id' => $row['it_category_id'],
                    'it_control_id'  => $row['it_control_id'],
                    'year'           => $year,
                    'quarter'        => $quarter,
                ],
                [
                    'uic'                 => $row['uic'],
                    'control_description' => $row['control_description'],
                    'status_control'      => $row['status_control'],
                    'status_it_category'  => $row['status_it_category'],
                ]
            );
        }

        $this->command->info('✓ Seeded ' . count($controls) . ' control rows for SAP S/4HANA (2026 Q1).');
    }
}
