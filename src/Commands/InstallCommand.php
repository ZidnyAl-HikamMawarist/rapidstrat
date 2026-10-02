<?php

namespace Rapidstrat\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rapidstrat:install {--all : Install all modules without interactive prompts}';

    /**
     * The console command description.
     */
    protected $description = 'Install modul RapidStrat (Auth, Multi-Role, Admin Layout, PDF Report, Stubs, & Docs)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->displayBanner();

        $installAll = $this->option('all');

        if (!$installAll) {
            $choice = $this->choice(
                'Pilih paket instalasi yang Anda inginkan:',
                [
                    0 => '🚀 Full Package (Auth Multi-Role + Tailwind Dashboard + Laporan PDF + CRUD Stubs + Panduan)',
                    1 => '🔐 Hanya Multi-Role Auth & Middleware (Login, Register, Role Admin/Petugas/User)',
                    2 => '🖥️  Hanya Admin Dashboard Layout (Tailwind Sidebar, Navbar, & KPI Cards)',
                    3 => '📄 Hanya Modul Cetak Laporan PDF (Filter Tanggal & DomPDF Template)',
                    4 => '📦 Hanya Blueprint CRUD Stubs & PANDUAN_UJIKOM.md',
                ],
                0
            );

            $selectedOption = (int) substr($choice, 0, 1);
        } else {
            $selectedOption = 0;
        }

        $this->info("\n⏳ Memulai proses instalasi modul...");

        switch ($selectedOption) {
            case 0:
                $this->installAuth();
                $this->installDashboard();
                $this->installReports();
                $this->installCrudStubs();
                $this->installDocumentation();
                $this->appendRoutes();
                break;
            case 1:
                $this->installAuth();
                $this->appendAuthRoutes();
                break;
            case 2:
                $this->installDashboard();
                $this->appendDashboardRoutes();
                break;
            case 3:
                $this->installReports();
                $this->appendReportRoutes();
                break;
            case 4:
                $this->installCrudStubs();
                $this->installDocumentation();
                break;
        }

        $this->displayCompletion($selectedOption);

        return Command::SUCCESS;
    }

    protected function displayBanner()
    {
        $this->line("<fg=cyan>
  ____             _     _ ____  _             _   
 |  _ \ __ _ _ __ (_) __| / ___|| |_ _ __ __ _| |_ 
 | |_) / _` | '_ \| |/ _` \___ \| __| '__/ _` | __|
 |  _ < (_| | |_) | | (_| |___) | |_| | | (_| | |_ 
 |_| \_\__,_| .__/|_|\__,_|____/ \__|_|  \__,_|\__|
            |_|   <fg=yellow>Rapid Scaffolding & Multi-Role Kit</>
</>");
        $this->line("<fg=gray>  Created by Zidny Al-Hikam Mawarist | Versi 1.0.0</>\n");
    }

    protected function installAuth()
    {
        $this->task('Menginstal Autentikasi & Multi-Role...', function () {
            // 1. Controller
            File::ensureDirectoryExists(app_path('Http/Controllers'));
            File::copy(
                __DIR__ . '/../stubs/auth/Controllers/AuthController.php.stub',
                app_path('Http/Controllers/AuthController.php')
            );

            // 2. Middleware
            File::ensureDirectoryExists(app_path('Http/Middleware'));
            File::copy(
                __DIR__ . '/../stubs/auth/Middleware/CheckRole.php.stub',
                app_path('Http/Middleware/CheckRole.php')
            );

            // 3. Views Auth
            File::ensureDirectoryExists(resource_path('views/auth'));
            File::copy(
                __DIR__ . '/../stubs/auth/views/login.blade.php',
                resource_path('views/auth/login.blade.php')
            );
            File::copy(
                __DIR__ . '/../stubs/auth/views/register.blade.php',
                resource_path('views/auth/register.blade.php')
            );

            // 4. Migration role
            $timestamp = date('Y_m_d_His');
            File::copy(
                __DIR__ . '/../stubs/auth/Migrations/add_role_to_users_table.php.stub',
                database_path("migrations/{$timestamp}_add_role_to_users_table.php")
            );

            // 5. Seeder
            File::ensureDirectoryExists(database_path('seeders'));
            File::copy(
                __DIR__ . '/../stubs/auth/Seeders/UserRoleSeeder.php.stub',
                database_path('seeders/UserRoleSeeder.php')
            );

            // 6. Update User model fillable
            $this->updateUserModel();
        });
    }

    protected function updateUserModel()
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

    protected function installDashboard()
    {
        $this->task('Menginstal Admin Layout & Tailwind Dashboard...', function () {
            // 1. Controller
            File::copy(
                __DIR__ . '/../stubs/dashboard/Controllers/DashboardController.php.stub',
                app_path('Http/Controllers/DashboardController.php')
            );

            // 2. Views Layout & Dashboard
            File::ensureDirectoryExists(resource_path('views/layouts'));
            File::ensureDirectoryExists(resource_path('views/admin'));
            File::ensureDirectoryExists(resource_path('views/user'));

            File::copy(
                __DIR__ . '/../stubs/dashboard/views/layouts/admin.blade.php',
                resource_path('views/layouts/admin.blade.php')
            );
            File::copy(
                __DIR__ . '/../stubs/dashboard/views/admin/dashboard.blade.php',
                resource_path('views/admin/dashboard.blade.php')
            );
            File::copy(
                __DIR__ . '/../stubs/dashboard/views/user/dashboard.blade.php',
                resource_path('views/user/dashboard.blade.php')
            );
        });
    }

    protected function installReports()
    {
        $this->task('Menginstal Engine Laporan PDF & Filter...', function () {
            File::copy(
                __DIR__ . '/../stubs/report/Controllers/ReportController.php.stub',
                app_path('Http/Controllers/ReportController.php')
            );

            File::ensureDirectoryExists(resource_path('views/reports'));
            File::copy(
                __DIR__ . '/../stubs/report/views/index.blade.php',
                resource_path('views/reports/index.blade.php')
            );
            File::copy(
                __DIR__ . '/../stubs/report/views/pdf_template.blade.php',
                resource_path('views/reports/pdf_template.blade.php')
            );
        });
    }

    protected function installCrudStubs()
    {
        $this->task('Menyiapkan Stubs CRUD Blueprint...', function () {
            File::ensureDirectoryExists(resource_path('views/master'));
            File::copy(
                __DIR__ . '/../stubs/crud/index.blade.php.stub',
                resource_path('views/master/index.blade.php')
            );
            File::copy(
                __DIR__ . '/../stubs/crud/form.blade.php.stub',
                resource_path('views/master/form.blade.php')
            );
            File::copy(
                __DIR__ . '/../stubs/crud/ContohController.php.stub',
                app_path('Http/Controllers/ContohController.php')
            );
        });
    }

    protected function installDocumentation()
    {
        $this->task('Membuat PANDUAN_UJIKOM.md di root project...', function () {
            File::copy(
                __DIR__ . '/../stubs/docs/PANDUAN_UJIKOM.md',
                base_path('PANDUAN_UJIKOM.md')
            );
        });
    }

    protected function appendRoutes()
    {
        $routesPath = base_path('routes/web.php');
        $routesStub = <<<'PHP'


// ==========================================
// ROUTES RAPIDSTRAT (Multi-Role & Dashboard)
// ==========================================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContohController;

// Rute Publik (Tamu / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Portal Pengguna (Role: User / Siswa / Pelanggan)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
});

// Panel Administrator & Petugas (Role: Admin & Petugas)
Route::middleware(['auth', 'role:admin,petugas'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
});

// Modul Cetak Laporan PDF
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/print', [ReportController::class, 'printView'])->name('reports.print');
});

// Contoh Resource Master Data
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    Route::resource('master', ContohController::class);
});
PHP;

        if (File::exists($routesPath)) {
            $content = File::get($routesPath);
            if (!str_contains($content, 'ROUTES RAPIDSTRAT')) {
                File::append($routesPath, $routesStub);
            }
        }
    }

    protected function appendAuthRoutes()
    {
        // Minimal auth routes
        $routesPath = base_path('routes/web.php');
        $stub = <<<'PHP'

use App\Http\Controllers\AuthController;
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
PHP;
        File::append($routesPath, $stub);
    }

    protected function appendDashboardRoutes()
    {
        $routesPath = base_path('routes/web.php');
        $stub = <<<'PHP'

use App\Http\Controllers\DashboardController;
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
});
PHP;
        File::append($routesPath, $stub);
    }

    protected function appendReportRoutes()
    {
        $routesPath = base_path('routes/web.php');
        $stub = <<<'PHP'

use App\Http\Controllers\ReportController;
Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/print', [ReportController::class, 'printView'])->name('reports.print');
});
PHP;
        File::append($routesPath, $stub);
    }

    protected function displayCompletion($selectedOption)
    {
        $this->newLine();
        $this->info("✨ INSTALASI RAPIDSTRAT BERHASIL SELESAI! ✨");
        $this->line("────────────────────────────────────────────────────────────");

        $this->line("<fg=yellow>LANGKAH BERIKUTNYA UNTUK MENJALANKAN:</>");
        $this->line(" 1. Daftarkan alias middleware role di <fg=cyan>bootstrap/app.php</>:");
        $this->line("    <fg=gray>->withMiddleware(function (Middleware \$middleware) {</>");
        $this->line("    <fg=green>    \$middleware->alias(['role' => \\App\\Http\\Middleware\\CheckRole::class]);</>");
        $this->line("    <fg=gray>})</>");
        $this->newLine();
        $this->line(" 2. Jalankan migrasi dan seeder akun demo:");
        $this->line("    <fg=cyan>php artisan migrate</>");
        $this->line("    <fg=cyan>php artisan db:seed --class=UserRoleSeeder</>");
        $this->newLine();
        $this->line(" 3. (Opsional untuk PDF) Install DomPDF:");
        $this->line("    <fg=cyan>composer require barryvdh/laravel-dompdf</>");
        $this->newLine();
        $this->line(" 4. Buka file panduan arsitektur & contekan ujian di:");
        $this->line("    <fg=magenta>./PANDUAN_UJIKOM.md</>");
        $this->line("────────────────────────────────────────────────────────────");
        $this->info("Akun Demo: admin@rapidstrat.test | petugas@rapidstrat.test | pass: password");
        $this->newLine();
    }
}
