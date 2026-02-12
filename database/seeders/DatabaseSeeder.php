<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Memo;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // ── 顧客 1: タクミ ──
        $takumi = Customer::create([
            'name' => 'タクミ',
            'birthday' => '02-10',
            'tag' => ['VIP', 'シャンパン', '同伴多い'],
        ]);

        Memo::create(['customer_id' => $takumi->id, 'date' => '2026-01-03', 'text' => '年始の挨拶。仕事忙しいって言ってた。']);
        Memo::create(['customer_id' => $takumi->id, 'date' => '2025-12-10', 'text' => '山崎好き。次は同伴行けそう。']);

        Visit::create(['customer_id' => $takumi->id, 'date' => '2026-01-03', 'type' => '来店', 'amount' => 60000, 'note' => '延長あり']);
        Visit::create(['customer_id' => $takumi->id, 'date' => '2025-12-10', 'type' => '同伴', 'amount' => 45000, 'note' => '焼肉']);

        // ── 顧客 2: ユウジ ──
        $yuuji = Customer::create([
            'name' => 'ユウジ',
            'birthday' => '01-15',
            'tag' => ['最近来てない', '旅行好き'],
        ]);

        Memo::create(['customer_id' => $yuuji->id, 'date' => '2025-11-20', 'text' => '沖縄行くって言ってた。写真見せてもらう。']);

        Visit::create(['customer_id' => $yuuji->id, 'date' => '2025-11-20', 'type' => '来店', 'amount' => 22000, 'note' => '短時間']);

        // ── 顧客 3: ケン ──
        $ken = Customer::create([
            'name' => 'ケン',
            'birthday' => null,
            'tag' => ['指名強', 'LINE返信早い'],
        ]);

        Memo::create(['customer_id' => $ken->id, 'date' => '2026-01-06', 'text' => '仕事の愚痴聞いた。次回は週末。']);

        Visit::create(['customer_id' => $ken->id, 'date' => '2026-01-06', 'type' => '来店', 'amount' => 30000, 'note' => 'ボトルなし']);

        // ── 未整理来店（customer_id = null）──
        Visit::create(['customer_id' => null, 'date' => now()->toDateString(), 'type' => '来店',   'time' => '18:40', 'note' => '延長あり']);
        Visit::create(['customer_id' => null, 'date' => now()->toDateString(), 'type' => '同伴',   'time' => '21:10', 'note' => null]);
        Visit::create(['customer_id' => null, 'date' => now()->toDateString(), 'type' => 'アフター', 'time' => '23:50', 'note' => 'バーだけ']);
    }
}
