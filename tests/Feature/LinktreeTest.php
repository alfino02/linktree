<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinktreeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_piknik_and_lombok_pages_render(): void
    {
        $piknikRes = $this->get('/');
        $piknikRes->assertStatus(200);
        $piknikRes->assertSee('Piknik Enthusiast', false);

        $lombokRes = $this->get('/lombok');
        $lombokRes->assertStatus(200);
        $lombokRes->assertSee('Lombok Enthusiast', false);

        $adminRes = $this->get('/admin');
        $adminRes->assertStatus(200);
        $adminRes->assertSee('Pilih Linktree yang Dikelola', false);
    }

    public function test_content_api_auth_and_multi_site_persistence(): void
    {
        $this->getJson('/api/content?site=piknik')
            ->assertStatus(200)
            ->assertJson(['site' => 'piknik', 'exists' => false]);

        $this->postJson('/api/content', ['action' => 'verify', 'password' => 'wrong'])
            ->assertStatus(401);

        $this->postJson('/api/content', ['action' => 'verify', 'password' => 'langsungakses'])
            ->assertStatus(200)
            ->assertJson(['ok' => true]);

        $lombokPayload = [
            'profile' => ['name' => 'Lombok Enthusiast Custom'],
            'destinations' => [['id' => 'd1', 'name' => 'Gili Trawangan', 'enabled' => true]],
            'settings' => ['adminPassword' => 'secret-should-be-stripped'],
        ];

        $this->postJson('/api/content', [
            'action' => 'save',
            'site' => 'lombok',
            'password' => 'langsungakses',
            'content' => $lombokPayload,
        ])->assertStatus(200)->assertJson(['ok' => true, 'site' => 'lombok']);

        $getLombok = $this->getJson('/api/content?site=lombok')
            ->assertStatus(200)
            ->assertJson([
                'site' => 'lombok',
                'exists' => true,
                'content' => [
                    'profile' => ['name' => 'Lombok Enthusiast Custom'],
                ],
            ]);

        $this->assertArrayNotHasKey('adminPassword', $getLombok->json('content.settings') ?? []);

        // Piknik remains independent
        $this->getJson('/api/content?site=piknik')
            ->assertStatus(200)
            ->assertJson(['site' => 'piknik', 'exists' => false]);
    }

    public function test_analytics_api_tracks_per_site_and_requires_password_for_summary(): void
    {
        $this->postJson('/api/analytics', [
            'action' => 'track',
            'site' => 'piknik',
            'vid' => 'visitor_abc12345',
            'device' => 'mobile',
            'source' => 'ig',
            'path' => '/',
        ])->assertStatus(200)->assertJson(['ok' => true, 'site' => 'piknik']);

        $this->postJson('/api/analytics', [
            'action' => 'track',
            'site' => 'lombok',
            'vid' => 'visitor_xyz98765',
            'device' => 'desktop',
            'source' => 'tt',
            'path' => '/lombok',
        ])->assertStatus(200)->assertJson(['ok' => true, 'site' => 'lombok']);

        $this->postJson('/api/analytics', ['action' => 'summary', 'site' => 'piknik'])
            ->assertStatus(401);

        $piknikSum = $this->postJson('/api/analytics', [
            'action' => 'summary',
            'site' => 'piknik',
            'password' => 'langsungakses',
        ])->assertStatus(200);

        $this->assertSame(1, $piknikSum->json('totals.todayVisitors'));
        $this->assertSame('Instagram', $piknikSum->json('sources.0.name'));

        $lombokSum = $this->postJson('/api/analytics', [
            'action' => 'summary',
            'site' => 'lombok',
            'password' => 'langsungakses',
        ])->assertStatus(200);

        $this->assertSame(1, $lombokSum->json('totals.todayVisitors'));
        $this->assertSame('TikTok', $lombokSum->json('sources.0.name'));
    }
}
