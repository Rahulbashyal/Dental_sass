@props([
    'name',
    'label'    => null,
    'value'    => null,
    'required' => false,
    'minDate'  => null,
    'maxDate'  => null,
    'error'    => null,
    'help'     => null,
    'disabled' => false,
])

@php
    $uid          = 'ndp_' . substr(md5($name . uniqid()), 0, 8);
    $errorMessage = $error ?? $errors->first($name);

    // Pre-fill: convert stored AD → BS for display
    $initBsYear  = null;
    $initBsMonth = null;
    $initBsDay   = null;
    $initDisplay = '';
    $initAdValue = old($name, $value);

    if ($initAdValue) {
        try {
            $bs = \App\Helpers\NepaliDateHelper::convertADtoBS($initAdValue);
            if ($bs) {
                $initBsYear  = (int) $bs['year'];
                $initBsMonth = (int) $bs['month'];
                $initBsDay   = (int) $bs['day'];
                $initDisplay = \App\Services\NepaliCalendarService::formatNepaliDate(
                    $bs['year'], $bs['month'], $bs['day']
                );
            }
        } catch (\Exception $e) {}
    }

    // Today in BS for default view + "Today" button
    $todayBs    = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
    $currentBsY = (int) ($todayBs['year']  ?? 2082);
    $currentBsM = (int) ($todayBs['month'] ?? 8);
    $currentBsD = (int) ($todayBs['day']   ?? 1);

    // BS calendar data 2040–2090
    $calData = [
        2040=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2041=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2042=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2043=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2044=>[30,32,31,32,31,30,30,30,29,30,29,31],
        2045=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2046=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2047=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2048=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2049=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2050=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2051=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2052=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2053=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2054=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2055=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2056=>[30,32,31,32,31,30,30,30,29,30,29,31],
        2057=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2058=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2059=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2060=>[30,32,31,32,31,30,30,30,29,30,29,31],
        2061=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2062=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2063=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2064=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2065=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2066=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2067=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2068=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2069=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2070=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2071=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2072=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2073=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2074=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2075=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2076=>[30,32,31,32,31,30,30,30,29,30,29,31],
        2077=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2078=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2079=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2080=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2081=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2082=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2083=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2084=>[31,32,31,32,31,30,30,30,29,30,29,31],
        2085=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2086=>[31,31,32,32,31,30,30,29,30,29,30,30],
        2087=>[31,32,31,32,31,30,30,30,29,29,30,31],
        2088=>[30,32,31,32,31,30,30,30,29,30,29,31],
        2089=>[31,31,32,31,31,31,30,29,30,29,30,30],
        2090=>[31,31,32,32,31,30,30,29,30,29,30,30],
    ];
@endphp

<style>
  .ndp-popup { position:fixed; z-index:99999; background:#fff; border-radius:1rem; box-shadow:0 20px 60px rgba(0,0,0,0.15); border:1px solid #e2e8f0; width:310px; display:none; }
  .ndp-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:2px; padding:6px 10px 10px; }
  .ndp-day { height:34px; width:34px; margin:auto; border:none; background:none; border-radius:10px; font-size:13px; font-weight:500; color:#334155; cursor:pointer; display:flex; align-items:center; justify-content:center; }
  .ndp-day:hover { background:#eff6ff; color:#2563eb; }
  .ndp-day.today { border:1.5px solid #93c5fd; color:#2563eb; font-weight:700; }
  .ndp-day.selected { background:#2563eb; color:#fff; font-weight:700; box-shadow:0 4px 12px rgba(37,99,235,0.3); }
  .ndp-day.blank { pointer-events:none; }
  .ndp-header { display:flex; align-items:center; justify-content:space-between; padding:10px 14px 8px; background:#f8fafc; border-bottom:1px solid #e2e8f0; border-radius:1rem 1rem 0 0; }
  .ndp-nav { width:32px; height:32px; border:none; background:none; border-radius:8px; font-size:18px; cursor:pointer; color:#64748b; display:flex; align-items:center; justify-content:center; }
  .ndp-nav:hover { background:#e2e8f0; }
  .ndp-select { border:none; background:transparent; font-size:13px; font-weight:700; color:#0f172a; cursor:pointer; outline:none; }
  .ndp-dow { display:grid; grid-template-columns:repeat(7,1fr); padding:6px 10px 0; }
  .ndp-dow span { text-align:center; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; }
  .ndp-today-btn { width:100%; border:none; background:none; padding:8px; font-size:12px; font-weight:700; color:#2563eb; cursor:pointer; border-top:1px solid #f1f5f9; border-radius:0 0 1rem 1rem; }
  .ndp-today-btn:hover { background:#eff6ff; }
  .ndp-trigger { width:100%; padding:14px 20px; background:#f8fafc; border:none; border-radius:1rem; font-size:14px; font-weight:500; color:#334155; cursor:pointer; text-align:left; display:flex; align-items:center; justify-content:space-between; transition:box-shadow .15s; }
  .ndp-trigger:focus { outline:none; box-shadow:0 0 0 2px #3b82f6; }
  .ndp-trigger:hover { background:#f1f5f9; }
</style>

<div style="position:relative;" id="{{ $uid }}_wrap">

    @if($label)
        <label for="{{ $uid }}_btn" class="block font-bold text-slate-700 tracking-tight mb-1">
            {{ $label }}@if($required)<span class="text-blue-500 ml-0.5">*</span>@endif
        </label>
    @endif

    {{-- Visible trigger button --}}
    <button type="button" id="{{ $uid }}_btn" class="ndp-trigger {{ $errorMessage ? 'ring-2 ring-red-400' : '' }}"
            onclick="ndpToggle('{{ $uid }}')" {{ $disabled ? 'disabled' : '' }}>
        <span id="{{ $uid }}_lbl">{{ $initDisplay ?: 'मिति छान्नुहोस् (Select Date)' }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </button>

    {{-- Hidden input carries actual AD date to server --}}
    <input type="hidden" id="{{ $uid }}_val" name="{{ $name }}"
           value="{{ $initAdValue }}" {{ $required ? 'required' : '' }}>

    @if($help && !$errorMessage)
        <p class="mt-1 text-xs text-slate-400">{{ $help }}</p>
    @endif
    @if($errorMessage)
        <p class="mt-1 text-sm text-red-600 font-semibold">{{ $errorMessage }}</p>
    @endif
</div>

{{-- Popup renders at body level (position:fixed) to escape all overflow --}}
<div id="{{ $uid }}_popup" class="ndp-popup">
    <div class="ndp-header">
        <button type="button" class="ndp-nav" onclick="ndpPrev('{{ $uid }}')">&#8249;</button>
        <div style="display:flex;gap:6px;align-items:center;">
            <select id="{{ $uid }}_m" class="ndp-select" onchange="ndpRender('{{ $uid }}')"></select>
            <select id="{{ $uid }}_y" class="ndp-select" onchange="ndpRender('{{ $uid }}')"></select>
        </div>
        <button type="button" class="ndp-nav" onclick="ndpNext('{{ $uid }}')">&#8250;</button>
    </div>
    <div class="ndp-dow">
        <span>आइत</span><span>सोम</span><span>मंगल</span><span>बुध</span><span>बिहि</span><span>शुक्र</span><span>शनि</span>
    </div>
    <div id="{{ $uid }}_grid" class="ndp-grid"></div>
    <button type="button" class="ndp-today-btn" onclick="ndpGoToday('{{ $uid }}')">आजको मिति — Today</button>
</div>

<script>
(function () {
    // ── Nepali calendar month-day data ─────────────────────────────────────
    var CAL = {!! json_encode($calData) !!};

    var MONTHS = ['बैशाख','जेठ','आषाढ','श्रावण','भाद्र','आश्विन',
                  'कार्तिक','मंसिर','पौष','माघ','फाल्गुन','चैत्र'];

    // ── BS ↔ AD conversion ─────────────────────────────────────────────────
    // Reference: 1 Baisakh 2057 = 10 April 2000 (UTC)
    var REF_EPOCH = Date.UTC(2000, 3, 10);
    var REF_Y = 2057, REF_M = 1, REF_D = 1;

    function bsToAd(y, m, d) {
        var days = 0;
        for (var yr = REF_Y; yr < y; yr++) {
            var yd = CAL[yr]; if (!yd) break;
            for (var i = 0; i < 12; i++) days += yd[i];
        }
        var yd = CAL[y] || [];
        for (var mo = 1; mo < m; mo++) days += (yd[mo - 1] || 30);
        days += (d - REF_D);
        var ts = REF_EPOCH + days * 864e5;
        var dt = new Date(ts);
        return dt.getUTCFullYear() + '-' +
               String(dt.getUTCMonth() + 1).padStart(2, '0') + '-' +
               String(dt.getUTCDate()).padStart(2, '0');
    }

    // Day-of-week for BS 1st of given month
    function dowOf1st(y, m) {
        var ad = bsToAd(y, m, 1);
        var p = ad.split('-');
        return new Date(Date.UTC(+p[0], +p[1] - 1, +p[2])).getUTCDay();
    }

    // ── Nepali numeral helper ───────────────────────────────────────────────
    var NN = {'0':'०','1':'१','2':'२','3':'३','4':'४','5':'५','6':'६','7':'७','8':'८','9':'९'};
    function toNep(n) { return String(n).split('').map(function(c){return NN[c]||c;}).join(''); }

    // ── State store ────────────────────────────────────────────────────────
    var S = window.__NDP_STATE__ = window.__NDP_STATE__ || {};

    function init(uid, iBsY, iBsM, iBsD, todayY, todayM, todayD) {
        S[uid] = {
            vY: iBsY || todayY,
            vM: iBsM || todayM,
            sY: iBsY, sM: iBsM, sD: iBsD,
            tY: todayY, tM: todayM, tD: todayD
        };
        // Populate month select
        var ms = document.getElementById(uid + '_m');
        MONTHS.forEach(function(n, i) { ms.add(new Option(n, i + 1)); });
        // Populate year select 2040–2090
        var ys = document.getElementById(uid + '_y');
        for (var yr = 2040; yr <= 2090; yr++) ys.add(new Option(yr, yr));
        ndpRender(uid);
    }

    window.ndpRender = function (uid) {
        var s = S[uid]; if (!s) return;
        var ms = document.getElementById(uid + '_m');
        var ys = document.getElementById(uid + '_y');
        if (ms.value) s.vM = +ms.value;
        if (ys.value) s.vY = +ys.value;
        ms.value = s.vM; ys.value = s.vY;

        var dims  = (CAL[s.vY] || [])[s.vM - 1] || 30;
        var start = dowOf1st(s.vY, s.vM);
        var grid  = document.getElementById(uid + '_grid');
        grid.innerHTML = '';

        // Blank offset cells
        for (var i = 0; i < start; i++) {
            var b = document.createElement('div'); b.className = 'ndp-day blank'; grid.appendChild(b);
        }

        for (var d = 1; d <= dims; d++) {
            (function (day) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = day;
                var cls = 'ndp-day';
                if (s.sY === s.vY && s.sM === s.vM && s.sD === day) cls += ' selected';
                else if (s.tY === s.vY && s.tM === s.vM && s.tD === day) cls += ' today';
                btn.className = cls;
                btn.onclick = function () { ndpSelect(uid, s.vY, s.vM, day); };
                grid.appendChild(btn);
            })(d);
        }
    };

    window.ndpSelect = function (uid, y, m, d) {
        var s = S[uid]; if (!s) return;
        s.sY = y; s.sM = m; s.sD = d;
        var ad = bsToAd(y, m, d);
        document.getElementById(uid + '_val').value = ad;
        document.getElementById(uid + '_lbl').textContent = toNep(d) + ' ' + MONTHS[m - 1] + ' ' + toNep(y);
        ndpClose(uid);
    };

    window.ndpGoToday = function (uid) {
        var s = S[uid]; if (!s) return;
        s.vY = s.tY; s.vM = s.tM;
        ndpRender(uid);
        ndpSelect(uid, s.tY, s.tM, s.tD);
    };

    window.ndpPrev = function (uid) {
        var s = S[uid]; if (!s) return;
        s.vM--; if (s.vM < 1) { s.vM = 12; s.vY--; }
        ndpRender(uid);
    };

    window.ndpNext = function (uid) {
        var s = S[uid]; if (!s) return;
        s.vM++; if (s.vM > 12) { s.vM = 1; s.vY++; }
        ndpRender(uid);
    };

    window.ndpToggle = function (uid) {
        var popup = document.getElementById(uid + '_popup');
        if (!popup) return;
        var open = popup.style.display === 'block';
        // Close all others
        document.querySelectorAll('.ndp-popup').forEach(function (p) {
            if (p !== popup) p.style.display = 'none';
        });
        if (open) { popup.style.display = 'none'; return; }
        // Position popup using fixed coords of trigger button
        var btn  = document.getElementById(uid + '_btn');
        var rect = btn.getBoundingClientRect();
        popup.style.display = 'block';
        var popH = popup.offsetHeight;
        var spaceBelow = window.innerHeight - rect.bottom;
        if (spaceBelow < popH && rect.top > popH) {
            popup.style.top  = (rect.top - popH - 4) + 'px';
        } else {
            popup.style.top  = (rect.bottom + 4) + 'px';
        }
        popup.style.left = Math.min(rect.left, window.innerWidth - 314) + 'px';
        ndpRender(uid);
    };

    window.ndpClose = function (uid) {
        var p = document.getElementById(uid + '_popup');
        if (p) p.style.display = 'none';
    };

    // Close on outside click
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.ndp-popup').forEach(function (popup) {
            var uid = popup.id.replace('_popup', '');
            var wrap = document.getElementById(uid + '_wrap');
            if (wrap && !wrap.contains(e.target) && !popup.contains(e.target)) {
                popup.style.display = 'none';
            }
        });
    });

    // Append all popups to body so overflow:hidden never clips them
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ndp-popup').forEach(function (p) {
            document.body.appendChild(p);
        });
    });

    // Boot this instance
    init(
        '{{ $uid }}',
        {!! $initBsYear  ?? 'null' !!},
        {!! $initBsMonth ?? 'null' !!},
        {!! $initBsDay   ?? 'null' !!},
        {{ $currentBsY }},
        {{ $currentBsM }},
        {{ $currentBsD }}
    );
})();
</script>
