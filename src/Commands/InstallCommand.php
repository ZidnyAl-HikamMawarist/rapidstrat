<?php

namespace Rapidstrat\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * ============================================================================
 * COMMAND ARTISAN INSTALLER RAPIDSTRAT
 * ============================================================================
 * Command ini bertugas mengotomatisasi penyalinan file blueprint/stubs
 * (Auth, Middleware, Dashboard, Views, Migrations, Seeders, dan Routes)
 * langsung ke dalam struktur direktori aplikasi Laravel pengguna.
 * ============================================================================
 */
class InstallCommand extends Command
{
    /**
     * Nama dan signature perintah console.
     * Dapat dijalankan interaktif (`rapidstrat:install`) atau otomatis (`--all`).
     */
    protected $signature = 'rapidstrat:install {--all : Install seluruh paket modul tanpa konfirmasi interaktif}';

    /**
     * Deskripsi kegunaan command pada daftar `php artisan list`.
     */
    protected $description = 'Scaffolding instan arsitektur UjiKom: Multi-Role Auth, Tailwind Dashboard, Laporan PDF, CRUD Stubs & Panduan';

    /**
     * Titik masuk utama eksekusi command console.
     */
    public function handle()
    {
        $this->displayBanner();

        $selectedOption = $this->promptInstallationChoice();

        $this->info("\n⏳ Memulai proses instalasi modul...");

        // Eksekusi pemasangan modul berdasarkan opsi pilihan pengguna
        match ($selectedOption) {
            0 => $this->installFullPackage(),
            1 => $this->installAuthOnly(),
            2 => $this->installDashboardOnly(),
            3 => $this->installReportsOnly(),
            4 => $this->installCrudOnly(),
            default => $this->installFullPackage(),
        };

        $this->displayCompletion($selectedOption);

        return Command::SUCCESS;
    }

    /**
     * Menampilkan logo banner ASCII art RapidStrat.
     */
    protected function displayBanner(): void
    {
        $this->line("<fg=cyan>
  ____             _     _ ____  _             _   
 |  _ \ __ _ _ __ (_) __| / ___|| |_ _ __ __ _| |_ 
 | |_) / _` | '_ \| |/ _` \___ \| __| '__/ _` | __|
 |  _ < (_| | |_) | | (_| |___) | |_| | | (_| | |_ 
 |_| \_\__,_| .__/|_|\__,_|____/ \__|_|  \__,_|\__|
            |_|   <fg=yellow>Rapid Scaffolding & Multi-Role Kit</>
</>");
        $this->line("<fg=gray>  Dibuat oleh Zidny Al-Hikam Mawarist | Versi 1.1.0</>\n");
    }

    /**
     * Meminta pilihan paket kepada pengguna jika tidak menggunakan flag `--all`.
     */
    protected function promptInstallationChoice(): int
    {
        if ($this->option('all')) {
            return 0;
        }

        $choice = $this->choice(
            'Pilih paket instalasi yang ingin Anda pasang:',
            [
                0 => '🚀 Full Package (Auth Multi-Role + Tailwind Dashboard + Laporan PDF + CRUD Stubs + Panduan)',
                1 => '🔐 Hanya Multi-Role Auth & Middleware (Login, Register, Role Admin/Petugas/User)',
                2 => '🖥️  Hanya Admin Dashboard Layout (Tailwind Sidebar, Navbar, & KPI Cards)',
                3 => '📄 Hanya Modul Cetak Laporan PDF (Filter Tanggal & DomPDF Template)',
                4 => '📦 Hanya Blueprint CRUD Stubs & PANDUAN_UJIKOM.md',
            ],
            0
        );

        return (int) substr($choice, 0, 1);
    }

    /**
     * Helper pembungkus task console untuk visualisasi progres baris per baris.
     */
    protected function runTask(string $description, callable $callback): void
    {
        if (isset($this->components)) {
            $this->components->task($description, $callback);
        } else {
            $this->info("⏳ {$description}");
            $callback();
            $this->info("✓ Selesai.");
        }
    }

    // =========================================================================
    // PAKET INSTALASI SPESIFIK
    // =========================================================================

    /**
     * Memasang seluruh modul lengkap RapidStrat sekaligus.
     */
    protected function installFullPackage(): void
    {
        $this->installAuth();
        $this->installDashboard();
        $this->installReports();
        $this->installCrudStubs();
        $this->installDocumentation();
        $this->appendRouteFromStub('routes_all.stub');
    }

    protected function installAuthOnly(): void
    {
        $this->installAuth();
        $this->appendRouteFromStub('routes_auth.stub');
    }

    protected function installDashboardOnly(): void
    {
        $this->installDashboard();
        $this->appendRouteFromStub('routes_dashboard.stub');
    }

    protected function installReportsOnly(): void
    {
        $this->installReports();
        $this->appendRouteFromStub('routes_report.stub');
    }

    protected function installCrudOnly(): void
    {
        $this->installCrudStubs();
        $this->installDocumentation();
    }

    // =========================================================================
    // IMPLEMENTASI PEMASANGAN TIAP KOMPONEN
    // =========================================================================

    /**
     * Memasang Controller Auth, Middleware CheckRole, View Login/Register, Migrasi & Seeder Role.
     */
    protected function installAuth(): void
    {
        $this->runTask('Menginstal Autentikasi & Multi-Role...', function () {
            // 1. Controller & Middleware
            File::ensureDirectoryExists(app_path('Http/Controllers'));
            File::copy(__DIR__ . '/../stubs/auth/Controllers/AuthController.php.stub', app_path('Http/Controllers/AuthController.php'));

            File::ensureDirectoryExists(app_path('Http/Middleware'));
            File::copy(__DIR__ . '/../stubs/auth/Middleware/CheckRole.php.stub', app_path('Http/Middleware/CheckRole.php'));

            // 2. Views Auth (Login & Register)
            File::ensureDirectoryExists(resource_path('views/auth'));
            File::copy(__DIR__ . '/../stubs/auth/views/login.blade.php', resource_path('views/auth/login.blade.php'));
            File::copy(__DIR__ . '/../stubs/auth/views/register.blade.php', resource_path('views/auth/register.blade.php'));

            // 3. Migrasi Role Pengguna
            $timestamp = date('Y_m_d_His');
            File::copy(__DIR__ . '/../stubs/auth/Migrations/add_role_to_users_table.php.stub', database_path("migrations/{$timestamp}_add_role_to_users_table.php"));

            // 4. Seeder Demo Akun Multi-Role
            File::ensureDirectoryExists(database_path('seeders'));
            File::copy(__DIR__ . '/../stubs/auth/Seeders/UserRoleSeeder.php.stub', database_path('seeders/UserRoleSeeder.php'));

            // 5. Tambahkan kolom 'role' ke properti $fillable di User Model
            $this->updateUserModel();

            // 6. Daftarkan alias middleware secara otomatis ke bootstrap/app.php
            $this->registerMiddlewareInBootstrap();
        });
    }

    /**
     * Memperbarui file Model User agar mengizinkan field role diisi secara massal.
     */
    protected function updateUserModel(): void
    {
        $userModelPath = app_path('Models/User.php');
        if (File::exists($userModelPath)) {
            $content = File::get($userModelPath);
            if (!str_contains($content, "'role'")) {
                $content = str_replace(
                    "'password',",
                    "'password',\n        'role',",
                    $content
                );
                File::put($userModelPath, $content);
            }
        }
    }

    /**
     * Mendaftarkan alias middleware 'role' secara otomatis ke dalam konfigurasi bootstrap/app.php (Laravel 11+).
     */
    protected function registerMiddlewareInBootstrap(): void
    {
        $bootstrapApp = base_path('bootstrap/app.php');
        if (File::exists($bootstrapApp)) {
            $content = File::get($bootstrapApp);
            if (!str_contains($content, "'role'")) {
                $pattern = '/->withMiddleware\s*\(\s*function\s*\(\s*Middleware\s*\$middleware\s*\)(?:\s*:\s*\w+)?\s*\{/';
                if (preg_match($pattern, $content, $matches)) {
                    $replacement = $matches[0] . "\n        \$middleware->alias([\n            'role' => \\App\\Http\\Middleware\\CheckRole::class,\n        ]);";
                    $content = str_replace($matches[0], $replacement, $content);
                    File::put($bootstrapApp, $content);
                }
            }
        }
    }

    /**
     * Memasang Controller Dashboard dan template layout Tailwind admin/user.
     */
    protected function installDashboard(): void
    {
        $this->runTask('Menginstal Admin Layout & Tailwind Dashboard...', function () {
            File::copy(__DIR__ . '/../stubs/dashboard/Controllers/DashboardController.php.stub', app_path('Http/Controllers/DashboardController.php'));

            File::ensureDirectoryExists(resource_path('views/layouts'));
            File::ensureDirectoryExists(resource_path('views/admin'));
            File::ensureDirectoryExists(resource_path('views/user'));

            File::copy(__DIR__ . '/../stubs/dashboard/views/layouts/admin.blade.php', resource_path('views/layouts/admin.blade.php'));
            File::copy(__DIR__ . '/../stubs/dashboard/views/admin/dashboard.blade.php', resource_path('views/admin/dashboard.blade.php'));
            File::copy(__DIR__ . '/../stubs/dashboard/views/user/dashboard.blade.php', resource_path('views/user/dashboard.blade.php'));
        });
    }

    /**
     * Memasang modul cetak laporan PDF dengan filter tanggal.
     */
    protected function installReports(): void
    {
        $this->runTask('Menginstal Engine Laporan PDF & Filter...', function () {
            File::copy(__DIR__ . '/../stubs/report/Controllers/ReportController.php.stub', app_path('Http/Controllers/ReportController.php'));

            File::ensureDirectoryExists(resource_path('views/reports'));
            File::copy(__DIR__ . '/../stubs/report/views/index.blade.php', resource_path('views/reports/index.blade.php'));
            File::copy(__DIR__ . '/../stubs/report/views/pdf_template.blade.php', resource_path('views/reports/pdf_template.blade.php'));
        });
    }

    /**
     * Memasang blueprint controller CRUD dan view form/tabel master data.
     */
    protected function installCrudStubs(): void
    {
        $this->runTask('Menyiapkan Stubs CRUD Blueprint...', function () {
            File::ensureDirectoryExists(resource_path('views/master'));
            File::copy(__DIR__ . '/../stubs/crud/index.blade.php.stub', resource_path('views/master/index.blade.php'));
            File::copy(__DIR__ . '/../stubs/crud/form.blade.php.stub', resource_path('views/master/form.blade.php'));
            File::copy(__DIR__ . '/../stubs/crud/ContohController.php.stub', app_path('Http/Controllers/ContohController.php'));
        });
    }

    /**
     * Menyalin dokumen panduan arsitektur dan jawaban penguji ke root project.
     */
    protected function installDocumentation(): void
    {
        $this->runTask('Membuat PANDUAN_UJIKOM.md di root project...', function () {
            File::copy(__DIR__ . '/../stubs/docs/PANDUAN_UJIKOM.md', base_path('PANDUAN_UJIKOM.md'));
        });
    }

    /**
     * Membaca file stub rute dan menambahkannya ke routes/web.php secara bersih.
     */
    protected function appendRouteFromStub(string $stubFileName): void
    {
        $routesPath = base_path('routes/web.php');
        $stubPath = __DIR__ . "/../stubs/routes/{$stubFileName}";

        if (File::exists($routesPath) && File::exists($stubPath)) {
            $existingRoutes = File::get($routesPath);
            $newRoutes = File::get($stubPath);

            // Hindari duplikasi rute jika sudah pernah di-install sebelumnya
            if (!str_contains($existingRoutes, 'RAPIDSTRAT')) {
                File::append($routesPath, "\n" . $newRoutes);
            }
        }
    }

    /**
     * Menampilkan panduan ringkas langkah berikutnya setelah proses instalasi selesai.
     */
    protected function displayCompletion(int $selectedOption): void
    {
        $this->newLine();
        $this->info("✨ INSTALASI RAPIDSTRAT SELESAI DENGAN SUKSES! ✨");
        $this->line("────────────────────────────────────────────────────────────");

        $this->line("<fg=yellow>LANGKAH LANJUTAN UNTUK MENJALANKAN SISTEM:</>");
        $this->line(" 1. <fg=green>✓ Alias Middleware 'role' sudah otomatis didaftarkan</> di <fg=cyan>bootstrap/app.php</>");
        $this->newLine();
        $this->line(" 2. Jalankan migrasi tabel role & seeder akun demo:");
        $this->line("    <fg=cyan>php artisan migrate</>");
        $this->line("    <fg=cyan>php artisan db:seed --class=UserRoleSeeder</>");
        $this->newLine();
        $this->line(" 3. (Opsional untuk fitur Cetak PDF) Pasang package DomPDF:");
        $this->line("    <fg=cyan>composer require barryvdh/laravel-dompdf</>");
        $this->newLine();
        $this->line(" 4. Buka panduan arsitektur & contekan ujian di:");
        $this->line("    <fg=magenta>./PANDUAN_UJIKOM.md</>");
        $this->line("────────────────────────────────────────────────────────────");
        $this->info("Akun Demo: admin@rapidstrat.test | petugas@rapidstrat.test | pass: password");
        $this->newLine();
    }
}
