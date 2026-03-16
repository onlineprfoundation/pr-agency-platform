<?php

namespace App\Http\Controllers;

use App\Services\InstallService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class InstallController extends Controller
{
    public function __construct(
        protected InstallService $installService
    ) {}

    public function welcome(): View
    {
        $check = $this->installService->checkRequirements();

        return view('install.welcome', $check);
    }

    public function database(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $validated = Validator::make($request->all(), [
                'db_connection' => 'required|in:sqlite,mysql,pgsql',
                'db_host' => 'required_if:db_connection,mysql,pgsql',
                'db_port' => 'nullable',
                'db_database' => 'required',
                'db_username' => 'nullable',
                'db_password' => 'nullable',
            ])->validate();

            session(['install.db' => $validated]);
            return redirect()->route('install.administrator');
        }

        return view('install.database');
    }

    public function testDatabase(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'db_connection' => 'required|in:sqlite,mysql,pgsql',
            'db_host' => 'required_if:db_connection,mysql,pgsql',
            'db_port' => 'nullable',
            'db_database' => 'required',
            'db_username' => 'nullable',
            'db_password' => 'nullable',
        ])->validate();

        $config = [
            'connection' => $validated['db_connection'],
            'database' => $validated['db_database'],
        ];

        if ($validated['db_connection'] !== 'sqlite') {
            $config['host'] = $validated['db_host'] ?? '127.0.0.1';
            $config['port'] = $validated['db_port'] ?? ($validated['db_connection'] === 'mysql' ? 3306 : 5432);
            $config['username'] = $validated['db_username'] ?? 'root';
            $config['password'] = $validated['db_password'] ?? '';
        } else {
            $config['database'] = $validated['db_database'] ?: database_path('database.sqlite');
        }

        $result = $this->installService->testDatabaseConnection($config);

        if ($result['passed']) {
            $this->installService->saveDatabaseConfigToEnv($config);
        }

        return response()->json($result);
    }

    public function administrator(Request $request): View|RedirectResponse
    {
        $dbConfig = session('install.db');
        if (! $dbConfig) {
            $dbConfig = $this->installService->getDatabaseConfigFromEnv();
            if ($dbConfig) {
                session(['install.db' => $dbConfig]);
            } else {
                return redirect()->route('install.database');
            }
        }

        return view('install.administrator');
    }

    public function run(Request $request)
    {
        $dbConfig = session('install.db') ?? $this->installService->getDatabaseConfigFromEnv() ?? [];
        $input = array_merge(
            $request->only(['app_name', 'app_url', 'admin_name', 'admin_email', 'admin_password', 'admin_password_confirmation']),
            $dbConfig
        );
        $validated = Validator::make($input, [
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_connection' => 'required|in:sqlite,mysql,pgsql',
            'db_host' => 'required_if:db_connection,mysql,pgsql',
            'db_port' => 'nullable',
            'db_database' => 'required',
            'db_username' => 'nullable',
            'db_password' => 'nullable',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'admin_password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ])->validate();

        $data = array_merge($validated, $dbConfig);
        if ($validated['db_connection'] === 'sqlite') {
            $data['db_database'] = $validated['db_database'] ?: database_path('database.sqlite');
        } else {
            $data['db_host'] = $validated['db_host'] ?? '127.0.0.1';
            $data['db_port'] = $validated['db_port'] ?? ($validated['db_connection'] === 'mysql' ? '3306' : '5432');
            $data['db_username'] = $validated['db_username'] ?? 'root';
            $data['db_password'] = $validated['db_password'] ?? '';
        }

        $result = $this->installService->install($data);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 422);
    }

    public function complete(): View
    {
        return view('install.complete');
    }
}
