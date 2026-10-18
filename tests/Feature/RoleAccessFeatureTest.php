<?php

namespace Tests\Feature;

use App\Models\Criterion;
use App\Models\IbuHamil;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class RoleAccessFeatureTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $bidan;
    protected User $ahliGizi;
    protected IbuHamil $ibuHamil;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['username' => 'test_admin'],
            ['name' => 'Admin Test', 'email' => 'admin_test@puskesmas.com', 'role' => 'admin', 'password' => bcrypt('password')]
        );

        $this->bidan = User::firstOrCreate(
            ['username' => 'test_bidan'],
            ['name' => 'Bidan Test', 'email' => 'bidan_test@puskesmas.com', 'role' => 'bidan', 'password' => bcrypt('password')]
        );

        $this->ahliGizi = User::firstOrCreate(
            ['username' => 'test_ahligizi'],
            ['name' => 'Ahli Gizi Test', 'email' => 'ahligizi_test@puskesmas.com', 'role' => 'ahli_gizi', 'password' => bcrypt('password')]
        );

        $this->ibuHamil = IbuHamil::firstOrCreate(
            ['nama' => 'Ibu Hamil Test Role'],
            [
                'tanggal_lahir' => '1995-01-01',
                'hpht' => '2025-01-01',
                'hpl' => '2025-10-08',
                'usia_kehamilan_minggu' => 12,
                'status_kehamilan' => 'Trimester I',
                'status_anemia' => 'Normal (≥ 11)',
                'imt' => 22.0,
                'lila' => 24.0,
            ]
        );
    }

    public function test_ahli_gizi_can_access_and_manage_criteria(): void
    {
        $response = $this->actingAs($this->ahliGizi)->get('/kriteria');
        $response->assertStatus(200);
        $response->assertSee('Tambah Kriteria');

        Livewire::actingAs($this->ahliGizi)
            ->test(\App\Livewire\Criteria\Index::class)
            ->set('criterionCode', 'KT')
            ->set('criterionName', 'Kriteria Uji Gizi')
            ->set('criterionType', 'benefit')
            ->call('saveCriterion')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('criteria', ['code' => 'KT', 'name' => 'Kriteria Uji Gizi']);
    }

    public function test_bidan_cannot_manage_criteria(): void
    {
        $response = $this->actingAs($this->bidan)->get('/kriteria');
        $response->assertStatus(200);
        $response->assertDontSee('Tambah Kriteria');

        // Attempting to call saveCriterion should result in 403
        Livewire::actingAs($this->bidan)
            ->test(\App\Livewire\Criteria\Index::class)
            ->set('criterionCode', 'KB')
            ->set('criterionName', 'Kriteria Uji Bidan')
            ->set('criterionType', 'benefit')
            ->call('saveCriterion')
            ->assertStatus(403);
    }

    public function test_both_roles_can_access_and_calculate_ahp(): void
    {
        $resGizi = $this->actingAs($this->ahliGizi)->get('/perhitungan-bobot');
        $resGizi->assertStatus(200);
        $resGizi->assertSee('Perhitungan Bobot');

        $resBidan = $this->actingAs($this->bidan)->get('/perhitungan-bobot');
        $resBidan->assertStatus(200);
        $resBidan->assertSee('Perhitungan Bobot');

        Livewire::actingAs($this->ahliGizi)
            ->test(\App\Livewire\Ahp\Comparison::class)
            ->call('calculate')
            ->assertHasNoErrors();

        Livewire::actingAs($this->bidan)
            ->test(\App\Livewire\Ahp\Comparison::class)
            ->call('calculate')
            ->assertHasNoErrors();
    }

    public function test_both_roles_can_access_and_calculate_topsis(): void
    {
        $resGizi = $this->actingAs($this->ahliGizi)->get('/perhitungan-topsis');
        $resGizi->assertStatus(200);
        $resGizi->assertSee('Perhitungan TOPSIS');

        $resBidan = $this->actingAs($this->bidan)->get('/perhitungan-topsis');
        $resBidan->assertStatus(200);
        $resBidan->assertSee('Perhitungan TOPSIS');

        Livewire::actingAs($this->ahliGizi)
            ->test(\App\Livewire\Topsis\Calculation::class)
            ->call('runCalculation')
            ->assertHasNoErrors();

        Livewire::actingAs($this->bidan)
            ->test(\App\Livewire\Topsis\Calculation::class)
            ->call('runCalculation')
            ->assertHasNoErrors();
    }

    public function test_ahli_gizi_cannot_add_or_modify_ibu_hamil(): void
    {
        // Can view list & detail
        $resList = $this->actingAs($this->ahliGizi)->get('/ibu-hamil');
        $resList->assertStatus(200);
        $resList->assertDontSee('Tambah Ibu Hamil');
        $resList->assertDontSee('Impor Excel');

        $resDetail = $this->actingAs($this->ahliGizi)->get('/ibu-hamil/' . $this->ibuHamil->id);
        $resDetail->assertStatus(200);

        // Cannot visit create or edit routes
        $resCreate = $this->actingAs($this->ahliGizi)->get('/ibu-hamil/create');
        $resCreate->assertStatus(403);

        $resEdit = $this->actingAs($this->ahliGizi)->get('/ibu-hamil/' . $this->ibuHamil->id . '/edit');
        $resEdit->assertStatus(403);

        // Livewire save should abort 403
        Livewire::actingAs($this->ahliGizi)
            ->test(\App\Livewire\IbuHamil\Create::class)
            ->assertStatus(403);
    }

    public function test_bidan_can_add_and_modify_ibu_hamil(): void
    {
        $resList = $this->actingAs($this->bidan)->get('/ibu-hamil');
        $resList->assertStatus(200);
        $resList->assertSee('Tambah Ibu Hamil');
        $resList->assertSee('Impor Excel');

        $resCreate = $this->actingAs($this->bidan)->get('/ibu-hamil/create');
        $resCreate->assertStatus(200);

        $resEdit = $this->actingAs($this->bidan)->get('/ibu-hamil/' . $this->ibuHamil->id . '/edit');
        $resEdit->assertStatus(200);

        Livewire::actingAs($this->bidan)
            ->test(\App\Livewire\IbuHamil\Create::class)
            ->set('nama', 'Ibu Hamil Tambahan Bidan')
            ->set('tanggal_lahir', '1996-05-15')
            ->set('hpht', '2025-01-01')
            ->set('hpl', '2025-10-08')
            ->set('usia_kehamilan_minggu', 10)
            ->set('status_kehamilan', 'Trimester I')
            ->set('status_anemia', 'Normal (≥ 11)')
            ->set('imt', 21.5)
            ->set('lila', 24.0)
            ->call('save')
            ->assertRedirect(route('ibu-hamil.index'));

        $this->assertDatabaseHas('ibu_hamil', ['nama' => 'Ibu Hamil Tambahan Bidan']);
    }
}
