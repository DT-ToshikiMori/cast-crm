<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Memo;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CastCrmController extends Controller
{
    public function home()
    {
        $customers = auth()->user()->customers()->with('visits')->get();

        $birthdaySoon = $customers
            ->filter(fn ($c) => !empty($c->birthday))
            ->take(3);

        $stale = $customers
            ->sortByDesc('days_since_last_visit')
            ->take(5);

        return view('crm.home', compact('birthdaySoon', 'stale'));
    }

    public function customers(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $filter = (string) $request->query('filter', 'all');

        $customers = auth()->user()->customers;

        if ($q !== '') {
            $customers = $customers->filter(function ($c) use ($q) {
                $inTags = collect($c->tag)->contains(fn ($t) => mb_stripos($t, $q) !== false);
                return mb_stripos($c->name, $q) !== false || $inTags;
            });
        }

        if ($filter === 'vip') {
            $customers = $customers->filter(fn ($c) => in_array('VIP', $c->tag ?? [], true));
        } elseif ($filter === 'stale') {
            $customers = $customers->filter(fn ($c) => $c->days_since_last_visit >= 45);
        } elseif ($filter === 'birthday') {
            $customers = $customers->filter(fn ($c) => !empty($c->birthday));
        }

        $customers = $customers->sortBy('name')->values();

        return view('crm.customers.index', compact('customers', 'q', 'filter'));
    }

    public function customerShow(int $id)
    {
        $customer = auth()->user()->customers()->findOrFail($id);

        return view('crm.customers.show', compact('customer'));
    }

    public function customerCreate()
    {
        return view('crm.customers.create');
    }

    public function customerStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'birthday' => ['nullable', 'regex:/^\d{2}-\d{2}$/'],
            'tags' => ['nullable', 'string', 'max:120'],
            'memo' => ['nullable', 'string', 'max:500'],
        ]);

        $tags = collect(explode(',', (string) ($data['tags'] ?? '')))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values()
            ->all();

        $customer = auth()->user()->customers()->create([
            'name' => $data['name'],
            'birthday' => $data['birthday'] ?: null,
            'tag' => $tags,
        ]);

        if (!empty($data['memo'])) {
            Memo::create([
                'customer_id' => $customer->id,
                'date' => now()->toDateString(),
                'text' => $data['memo'],
            ]);
        }

        return redirect()
            ->route('crm.customer.show', $customer->id)
            ->with('status', 'お客さんを登録したよ');
    }

    // ── 来店記録 ──

    public function visitCreate()
    {
        return view('crm.visits.create');
    }

    public function visitStore(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'in:来店,同伴,アフター'],
            'amount' => ['nullable', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        auth()->user()->visits()->create([
            'customer_id' => null,
            'type' => $data['type'],
            'date' => now()->toDateString(),
            'time' => now()->format('H:i'),
            'amount' => $data['amount'] ?? null,
            'note' => $data['note'] ?? null,
        ]);

        return redirect()
            ->route('crm.visits.unassigned')
            ->with('status', '来店を記録したよ（未整理に追加）');
    }

    // ── 未整理来店 ──

    public function visitsUnassigned()
    {
        $unassignedVisits = auth()->user()->visits()->whereNull('customer_id')->orderByDesc('created_at')->get();

        return view('crm.visits.unassigned', compact('unassignedVisits'));
    }

    public function visitAssign(Request $request, int $visitId)
    {
        $q = trim((string) $request->query('q', ''));
        $selectedCustomerId = $request->query('customer_id');

        $visit = auth()->user()->visits()->whereNull('customer_id')->findOrFail($visitId);

        $customers = auth()->user()->customers;

        if ($q !== '') {
            $customers = $customers->filter(function ($c) use ($q) {
                $inTags = collect($c->tag)->contains(fn ($t) => mb_stripos($t, $q) !== false);
                return mb_stripos($c->name, $q) !== false || $inTags;
            })->values();
        }

        $customers = $customers->sortBy('days_since_last_visit')->values();

        return view('crm.visits.assign', compact('visit', 'customers', 'q', 'selectedCustomerId'));
    }

    public function visitAssignStore(Request $request, int $visitId)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
        ]);

        $visit = auth()->user()->visits()->whereNull('customer_id')->findOrFail($visitId);

        // Ensure the customer belongs to this user
        auth()->user()->customers()->findOrFail($data['customer_id']);

        $visit->update(['customer_id' => $data['customer_id']]);

        return redirect()
            ->route('crm.visits.unassigned')
            ->with('status', '来店を紐づけたよ');
    }

    // ── クイックメモ ──

    public function memoQuick(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $selectedId = $request->query('customer_id');

        $customers = auth()->user()->customers;

        if ($q !== '') {
            $customers = $customers->filter(function ($c) use ($q) {
                $inTags = collect($c->tag)->contains(fn ($t) => mb_stripos($t, $q) !== false);
                return mb_stripos($c->name, $q) !== false || $inTags;
            })->values();
        }

        $customers = $customers->sortBy('days_since_last_visit')->values();

        return view('crm.memos.quick', compact('customers', 'q', 'selectedId'));
    }

    public function memoQuickStore(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'text' => ['required', 'string', 'max:500'],
        ]);

        // Ensure the customer belongs to this user
        auth()->user()->customers()->findOrFail($data['customer_id']);

        Memo::create([
            'customer_id' => $data['customer_id'],
            'date' => now()->toDateString(),
            'text' => $data['text'],
        ]);

        return redirect()
            ->route('crm.customer.show', $data['customer_id'])
            ->with('status', 'メモを保存したよ');
    }

    // ── その他 ──

    public function reminders()
    {
        return view('crm.reminders');
    }

    public function settings()
    {
        return view('crm.settings');
    }
}
