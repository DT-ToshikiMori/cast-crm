<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class CastCrmController extends Controller
{
    /**
     * DBなしで「登録した顧客」をセッションに保存するキー
     */
    private string $sessionKey = 'crm_customers';

    /**
     * ベースのダミーデータ（固定）
     */
    private function baseDummyCustomers(): Collection
    {
        return collect([
            [
                'id' => 101,
                'name' => 'タクミ',
                'tag' => ['VIP', 'シャンパン', '同伴多い'],
                'last_visit' => '2026-01-03',
                'birthday' => '02-10',
                'memo' => [
                    ['date' => '2026-01-03', 'text' => '年始の挨拶。仕事忙しいって言ってた。'],
                    ['date' => '2025-12-10', 'text' => '山崎好き。次は同伴行けそう。'],
                ],
                'visits' => [
                    ['date' => '2026-01-03', 'type' => '来店', 'amount' => 60000, 'note' => '延長あり'],
                    ['date' => '2025-12-10', 'type' => '同伴', 'amount' => 45000, 'note' => '焼肉'],
                ],
            ],
            [
                'id' => 102,
                'name' => 'ユウジ',
                'tag' => ['最近来てない', '旅行好き'],
                'last_visit' => '2025-11-20',
                'birthday' => '01-15',
                'memo' => [
                    ['date' => '2025-11-20', 'text' => '沖縄行くって言ってた。写真見せてもらう。'],
                ],
                'visits' => [
                    ['date' => '2025-11-20', 'type' => '来店', 'amount' => 22000, 'note' => '短時間'],
                ],
            ],
            [
                'id' => 103,
                'name' => 'ケン',
                'tag' => ['指名強', 'LINE返信早い'],
                'last_visit' => '2026-01-06',
                'birthday' => null,
                'memo' => [
                    ['date' => '2026-01-06', 'text' => '仕事の愚痴聞いた。次回は週末。'],
                ],
                'visits' => [
                    ['date' => '2026-01-06', 'type' => '来店', 'amount' => 30000, 'note' => 'ボトルなし'],
                ],
            ],
        ]);
    }

    /**
     * セッション保存分（新規登録）を取得
     */
    private function sessionCustomers(): Collection
    {
        return collect(session($this->sessionKey, []));
    }

    /**
     * 画面用：ダミー + セッション顧客を統合し、派生値を付加
     */
    private function customersAll(): Collection
    {
        $customers = $this->sessionCustomers()
            ->concat($this->baseDummyCustomers()) // セッション（新規）→固定ダミーの順に並べる
            ->map(function ($c) {
                // last_visit が空だと死ぬので保険
                $last = !empty($c['last_visit']) ? Carbon::parse($c['last_visit']) : Carbon::now();

                $c['days_since_last_visit'] = Carbon::now()->startOfDay()->diffInDays($last->startOfDay());
                $c['tag'] = $c['tag'] ?? [];
                $c['memo'] = $c['memo'] ?? [];
                $c['visits'] = $c['visits'] ?? [];
                return $c;
            })
            ->values();

        return $customers;
    }

    public function home()
    {
        $customers = $this->customersAll();

        $birthdaySoon = $customers
            ->filter(fn ($c) => !empty($c['birthday']))
            ->take(3);

        $stale = $customers
            ->sortByDesc('days_since_last_visit')
            ->take(5);

        return view('crm.home', compact('birthdaySoon', 'stale'));
    }

    public function customers(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $filter = (string) $request->query('filter', 'all'); // all | vip | stale | birthday

        $customers = $this->customersAll();

        if ($q !== '') {
            $customers = $customers->filter(function ($c) use ($q) {
                $inTags = collect($c['tag'])->contains(fn ($t) => mb_stripos($t, $q) !== false);
                return mb_stripos($c['name'], $q) !== false || $inTags;
            });
        }

        if ($filter === 'vip') {
            $customers = $customers->filter(fn ($c) => in_array('VIP', $c['tag'], true));
        } elseif ($filter === 'stale') {
            $customers = $customers->filter(fn ($c) => $c['days_since_last_visit'] >= 45);
        } elseif ($filter === 'birthday') {
            $customers = $customers->filter(fn ($c) => !empty($c['birthday']));
        }

        $customers = $customers->sortBy('name')->values();

        return view('crm.customers.index', compact('customers', 'q', 'filter'));
    }

    public function customerShow(int $id)
    {
        $customer = $this->customersAll()->firstWhere('id', $id);
        abort_if(!$customer, 404);

        return view('crm.customers.show', compact('customer'));
    }

    /**
     * 新規登録フォーム
     */
    public function customerCreate()
    {
        return view('crm.customers.create');
    }

    /**
     * 新規登録（DBなし：セッション保存）
     */
    public function customerStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'birthday' => ['nullable', 'regex:/^\d{2}-\d{2}$/'], // 02-10形式
            'tags' => ['nullable', 'string', 'max:120'],         // カンマ区切り
            'memo' => ['nullable', 'string', 'max:500'],
        ]);

        $tags = collect(explode(',', (string) ($data['tags'] ?? '')))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values()
            ->all();

        // DBなし用の疑似ID（int）
        $newId = (int) (microtime(true) * 1000);

        $newCustomer = [
            'id' => $newId,
            'name' => $data['name'],
            'tag' => $tags,
            'last_visit' => now()->toDateString(),
            'birthday' => $data['birthday'] ?: null,
            'memo' => $data['memo']
                ? [['date' => now()->toDateString(), 'text' => $data['memo']]]
                : [],
            'visits' => [],
        ];

        $customers = $this->sessionCustomers();
        $customers->prepend($newCustomer);

        session([$this->sessionKey => $customers->all()]);

        return redirect()
            ->route('crm.customer.show', $newId)
            ->with('status', 'お客さんを登録したよ');
    }

    public function visitCreate()
    {
        return view('crm.visits.create');
    }

    public function reminders()
    {
        return view('crm.reminders');
    }

    public function settings()
    {
        return view('crm.settings');
    }

    public function memoQuick(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $selectedId = $request->query('customer_id');

        $customers = $this->customersAll();

        if ($q !== '') {
            $customers = $customers->filter(function ($c) use ($q) {
                $inTags = collect($c['tag'])->contains(fn ($t) => mb_stripos($t, $q) !== false);
                return mb_stripos($c['name'], $q) !== false || $inTags;
            })->values();
        }

        // 直近来店が新しい順に並べる（選びやすい）
        $customers = $customers->sortBy('days_since_last_visit')->values();

        return view('crm.memos.quick', compact('customers', 'q', 'selectedId'));
    }

    public function visitsUnassigned()
    {
        $unassignedVisits = collect([
            [
                'id' => 9001,
                'type' => '来店',
                'time' => '18:40',
                'memo' => '延長あり',
            ],
            [
                'id' => 9002,
                'type' => '同伴',
                'time' => '21:10',
                'memo' => null,
            ],
            [
                'id' => 9003,
                'type' => 'アフター',
                'time' => '23:50',
                'memo' => 'バーだけ',
            ],
        ]);

        return view('crm.visits.unassigned', compact('unassignedVisits'));
    }

    public function visitAssign(Request $request, int $visitId)
    {
        $q = trim((string) $request->query('q', ''));
        $selectedCustomerId = $request->query('customer_id');

        // 画面用ダミー：未整理の来店データ（IDで拾う）
        $unassignedVisits = collect([
            ['id' => 9001, 'type' => '来店',   'time' => '18:40', 'memo' => '延長あり'],
            ['id' => 9002, 'type' => '同伴',   'time' => '21:10', 'memo' => null],
            ['id' => 9003, 'type' => 'アフター', 'time' => '23:50', 'memo' => 'バーだけ'],
        ]);

        $visit = $unassignedVisits->firstWhere('id', $visitId);
        abort_if(!$visit, 404);

        // 既存の顧客（セッション+ダミー）を使う
        $customers = $this->customersAll();

        if ($q !== '') {
            $customers = $customers->filter(function ($c) use ($q) {
                $inTags = collect($c['tag'])->contains(fn ($t) => mb_stripos($t, $q) !== false);
                return mb_stripos($c['name'], $q) !== false || $inTags;
            })->values();
        }

        // 選びやすいよう、直近来店が新しい順（days小さい順）
        $customers = $customers->sortBy('days_since_last_visit')->values();

        return view('crm.visits.assign', compact('visit', 'customers', 'q', 'selectedCustomerId'));
    }
}