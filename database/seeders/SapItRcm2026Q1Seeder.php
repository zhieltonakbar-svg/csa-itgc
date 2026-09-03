<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Control;
use App\Models\ControlEvidence;
use App\Models\ItCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Replaces every existing Control for SAP S/4HANA, Year 2026, Quarter Q1
 * with the 18 controls from "IT Risk & Control Matrix V1.0" (mentor-provided
 * PDF). All 18 are seeded as Completed, with reviewer/approver notes and a
 * Review Result already set, so the Berita Acara PDF is immediately
 * generatable for each one.
 *
 * Run with:
 *   php artisan db:seed --class=SapItRcm2026Q1Seeder
 */
class SapItRcm2026Q1Seeder extends Seeder
{
    public function run(): void
    {
        $application = Application::where('name', 'SAP S/4HANA')->first();

        if (!$application) {
            $this->command->error('Application "SAP S/4HANA" not found. Aborting.');
            return;
        }

        $year = 2026;
        $quarter = 'q1';
        $upti = 'ESS';

        DB::transaction(function () use ($application, $year, $quarter, $upti) {

            // 1. Wipe existing controls (and their evidence) for this
            //    Application + Period, across all categories, as requested.
            $oldControls = Control::where('application_id', $application->id)
                ->where('year', $year)
                ->where('quarter', $quarter)
                ->get();

            foreach ($oldControls as $old) {
                foreach ($old->evidences as $evidence) {
                    if ($evidence->file_path && Storage::disk('public')->exists($evidence->file_path)) {
                        Storage::disk('public')->delete($evidence->file_path);
                    }
                    $evidence->delete();
                }
                $old->delete();
            }

            $this->command->info("Deleted {$oldControls->count()} existing control(s) for SAP S/4HANA {$year} {$quarter}.");

            // 2. Data straight from the PDF (IT Risk & Control Matrix V1.0)
            $rows = [
                ['C-IT-01', 'Access to Programs & Data', 'Prosedur ditetapkan, diimplementasikan dan direview untuk proses permintaan, pembuatan, aktivasi, suspending (penghentian hak akses), dan penutupan user accounts.', 'Quarterly', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-02', 'Access to Programs & Data', 'Proses review user akses dilakukan secara periodik untuk memastikan pengendalian hak akses user.', 'Quarterly', true, 'Unit AO, BPO dan Unit Pengelola Operasi TI'],
                ['C-IT-03', 'Access to Programs & Data', 'Aktivitas yang berdampak pada keamanan IT dimonitor, dicatat, direview dan didokumentasikan oleh administrator IT untuk mengidentifikasi adanya pelanggaran keamanan.', 'Twice a year', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-04', 'Access to Programs & Data', 'Akses terhadap fasilitas fisik TI perusahaan diatur melalui mekanisme identifikasi dan menggunakan sistem otentikasi yang memadai', 'Yearly', false, 'Unit Pengelola Fasilitas Fisik Infrastruktur'],
                ['C-IT-05', 'Program Change', 'Dokumen permintaan program changes, dan pemeliharaan aplikasi telah distandarkan, tercatat dalam log, direview, disetujui, dan didokumentasikan ke dalam prosedur formal', 'Twice a year', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-06', 'Program Change', 'emergency changes diimplementasikan, didokumentasikan secara lengkap serta direview dengan persetujuan dari manajemen IT.', 'Twice a year', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-07', 'Program Change', 'Pemindahan aplikasi dari lingkungan pengembangan dan pengujian ke lingkungan produksi sesuai dengan prosedur yang berlaku dan direview.', 'Twice a year', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-08', 'Program Change', 'Pemisahan tugas dan fungsi (segregation of duties) yg memadai antara personil yg bertanggung jawab utk memindahkan suatu program ke Lingkungan Produksi dari personil yg bertanggungjawab di Lingkungan Pengembangan dan direview.', 'Twice a year', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-09', 'Program Change', 'Pengujian dilakukan untuk mengkonfirmasi bahwa software dan infrastruktur jaringan telah dikonfigurasi dengan mengacu kepada persyaratan sekuriti pada kebijakan yang berlaku dan direview', 'Twice a year', true, 'Unit Pengelola Operasi TI, Unit Pengelola Keamanan Informasi'],
                ['C-IT-10', 'Computer Operations', 'Mengimplementasikan insiden & Problem manajemen, dicatat, disolusikan, dan di-review serta dilaporkan ke manajemen perusahaan.', 'Twice a year', true, 'Unit Pengelola Layanan TI'],
                ['C-IT-11', 'Computer Operations', 'Backup dilakukan sesuai dengan strategi dan jadwal backup serta direview secara berkala.', 'Twice a year', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-12', 'Computer Operations', 'Uji restorasi dilakukan secara periodik terhadap backup informasi dan direview secara berkala.', 'Yearly', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-13', 'Computer Operations', 'Melakukan review log batch processing', 'Twice a year', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-14', 'Program Development', 'Terdapat metodologi pengembangan sistem SDLC (System Development Life Cycle Methodology).', 'Yearly', false, 'Unit Pengelola Kebijakan TI'],
                ['C-IT-15', 'Program Development', 'Strategi testing disusun dan dipatuhi untuk setiap perubahan yang signifikan pada aplikasi dan infrastruktur, yang meliputi: pengujian unit, sistem, interface, integrasi dan UAT, sehingga sistem dapat diimplementasikan sesuai kebutuhan.', 'Per Project', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-16', 'Program Development', 'Konversi data antara sumber dan tujuan telah diuji untuk memastikan bahwa data adalah lengkap, akurat dan valid.', 'Per Project', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-17', 'Program Development', 'Pemisahan Lingkungan Pengembangan dan Lingkungan Produksi', 'Per Project', true, 'Unit Pengelola Operasi TI'],
                ['C-IT-18', 'Computer Operations', 'Melaksanakan Capacity Planning secara berkala dengan memproyeksikan kebutuhan kapasitas berdasarkan tren pertumbuhan transaksi data', 'Yearly', false, 'Unit Pengelola Operasi TI'],
            ];

            $submittedAt = \Carbon\Carbon::create($year, 2, 5, 9, 0);
            $reviewedAt  = \Carbon\Carbon::create($year, 2, 12, 10, 0);
            $approvedAt  = \Carbon\Carbon::create($year, 2, 18, 14, 0);

            $created = 0;

            foreach ($rows as [$controlId, $categoryName, $description, $frequency, $keyControl, $uic]) {

                $category = ItCategory::where('name', $categoryName)->first();

                if (!$category) {
                    $this->command->warn("Category '{$categoryName}' not found — skipping {$controlId}.");
                    continue;
                }

                Control::create([
                    'application_id' => $application->id,
                    'it_category_id' => $category->id,
                    'it_control_id' => $controlId,
                    'control_description' => $description,
                    'keterangan_frekuensi' => $frequency,
                    'key_control' => $keyControl,
                    'upti' => $upti,
                    'uic' => $uic,
                    'year' => $year,
                    'quarter' => $quarter,
                    'status_control' => 'completed',
                    'submitted_at' => $submittedAt,
                    'reviewed_at' => $reviewedAt,
                    'approved_at' => $approvedAt,
                    'reviewer_notes' => 'Kontrol telah direview dan sesuai dengan kebijakan serta prosedur IT yang berlaku.',
                    'approver_notes' => 'Disetujui, kontrol telah dilaksanakan dengan baik sesuai IT Risk & Control Matrix V1.0.',
                    'review_result' => 'effective',
                ]);

                $created++;
            }

            $this->command->info("Created {$created} new control(s) from IT Risk & Control Matrix V1.0.");
        });
    }
}
