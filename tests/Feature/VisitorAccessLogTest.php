<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VisitorAccessLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class VisitorAccessLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('visitor-access.enabled', true);
    }

    public function test_it_logs_safe_request_and_response_metadata_after_the_response(): void
    {
        Route::get('/_test/visitor-log/{token}', fn () => response('created', 201))->name('test.visitor-log');
        $user = User::factory()->create();
        $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 Version/17.4 Mobile/15E148 Safari/604.1';

        $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])
            ->withHeaders([
                'User-Agent' => $userAgent,
                'Referer' => 'https://example.org/source?secret=hidden',
                'Authorization' => 'Bearer must-not-be-stored',
                'Cookie' => 'session=must-not-be-stored',
            ])
            ->get('/_test/visitor-log/must-not-be-stored?query_secret=must-not-be-stored')
            ->assertCreated();

        $log = VisitorAccessLog::sole();

        $this->assertSame('203.0.113.7', $log->ip_address);
        $this->assertSame('GET', $log->http_method);
        $this->assertSame('test.visitor-log', $log->route_name);
        $this->assertSame('/_test/visitor-log/[redacted]', $log->url_path);
        $this->assertSame(201, $log->response_status);
        $this->assertSame('example.org', $log->referrer_domain);
        $this->assertSame($userAgent, $log->user_agent);
        $this->assertSame('Safari', $log->browser_name);
        $this->assertSame('iOS', $log->os_name);
        $this->assertSame('mobile', $log->device_category);
        $this->assertSame($user->id, $log->user_id);
        $this->assertNotNull($log->duration_ms);
        $this->assertStringNotContainsString('must-not-be-stored', json_encode($log->getAttributes(), JSON_THROW_ON_ERROR));
    }

    public function test_it_excludes_health_checks_static_assets_and_configured_routes(): void
    {
        Route::get('/_test/excluded', fn () => 'ok')->name('test.excluded');
        config()->set('visitor-access.exclude.route_names', ['test.excluded']);

        $this->get('/up')->assertOk();
        $this->get('/build/app.js')->assertNotFound();
        $this->get('/_test/excluded')->assertOk();

        $this->assertDatabaseCount('visitor_access_logs', 0);
    }

    public function test_admin_page_requires_an_authenticated_admin(): void
    {
        $this->get(route('visitor-access-logs.index'))->assertRedirect(route('login'));

        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get(route('visitor-access-logs.index'))->assertRedirect(route('productView'));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('visitor-access-logs.index'))
            ->assertOk()
            ->assertSee('Visitor Access Logs');
    }

    public function test_unavailable_user_agent_details_are_nullable(): void
    {
        Route::get('/_test/no-agent', fn () => 'ok')->name('test.no-agent');

        $this->withHeader('User-Agent', '')->get('/_test/no-agent')->assertOk();

        $log = VisitorAccessLog::sole();
        $this->assertNull($log->user_agent);
        $this->assertNull($log->browser_name);
        $this->assertNull($log->browser_version);
        $this->assertNull($log->os_name);
        $this->assertNull($log->os_version);
        $this->assertNull($log->device_category);
    }

    public function test_forwarded_ip_is_ignored_unless_the_connecting_proxy_is_trusted(): void
    {
        Route::get('/_test/proxy', fn () => 'ok')->name('test.proxy');

        $this->withServerVariables([
            'REMOTE_ADDR' => '10.0.0.10',
            'HTTP_X_FORWARDED_FOR' => '198.51.100.20',
        ])->get('/_test/proxy')->assertOk();

        $this->assertDatabaseHas('visitor_access_logs', ['ip_address' => '10.0.0.10']);

        config()->set('trusted-proxies.proxies', ['10.0.0.10']);

        $this->withServerVariables([
            'REMOTE_ADDR' => '10.0.0.10',
            'HTTP_X_FORWARDED_FOR' => '198.51.100.20',
        ])->get('/_test/proxy')->assertOk();

        $this->assertDatabaseHas('visitor_access_logs', ['ip_address' => '198.51.100.20']);
    }

    public function test_a_trusted_dynamic_platform_proxy_honors_the_forwarded_https_scheme(): void
    {
        Route::get('/_test/asset-url', fn () => asset('build/app.css'))->name('test.asset-url');
        config()->set('trusted-proxies.proxies', ['REMOTE_ADDR']);

        $this->withServerVariables([
            'REMOTE_ADDR' => '10.0.0.25',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ])->get('/_test/asset-url')
            ->assertOk()
            ->assertSeeText('https://localhost/build/app.css');
    }

    public function test_cleanup_command_honors_retention(): void
    {
        VisitorAccessLog::create($this->minimalLog(['accessed_at' => now()->subDays(91)]));
        VisitorAccessLog::create($this->minimalLog(['accessed_at' => now()->subDays(30)]));

        $this->artisan('visitor-access:prune')->assertSuccessful();

        $this->assertDatabaseCount('visitor_access_logs', 1);
    }

    /** @return array<string, mixed> */
    private function minimalLog(array $overrides = []): array
    {
        return array_merge([
            'accessed_at' => now(),
            'ip_address' => '127.0.0.1',
            'http_method' => 'GET',
            'url_path' => '/',
            'response_status' => 200,
        ], $overrides);
    }
}
