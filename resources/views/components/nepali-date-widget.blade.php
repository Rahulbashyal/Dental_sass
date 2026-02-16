<div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-blue-900 rounded-3xl p-6 shadow-2xl border border-white/5 group">
    <!-- Animated Background Elements -->
    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all duration-700"></div>
    <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-all duration-700"></div>
    
    <div class="relative flex flex-col sm:flex-row items-center justify-between gap-6">
        <!-- Nepali Side -->
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-blue-400 shadow-inner group-hover:scale-110 transition-transform duration-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-300 uppercase tracking-[0.2em] mb-1">आजको नेपाली मिति</p>
                @php
                    $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
                @endphp
                <h3 class="text-2xl font-black text-white leading-tight">
                    {{ $nepaliDate['formatted'] ?? '११ मंसिर २०८२' }}
                </h3>
                <p class="text-blue-200/60 text-xs font-bold">{{ $nepaliDate['day_of_week'] ? \App\Services\NepaliCalendarService::getDayName($nepaliDate['day_of_week']) : 'बुधबार' }}</p>
            </div>
        </div>

        <!-- Vertical Divider (hidden on small screens) -->
        <div class="hidden sm:block w-px h-12 bg-white/10"></div>

        <!-- English/Global Side -->
        <div class="flex flex-col items-center sm:items-end">
            <div class="flex items-center space-x-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <p id="live-time" class="text-xl font-black text-white font-mono tracking-tighter"></p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ date('l') }}</p>
                <p class="text-sm font-bold text-slate-300">{{ date('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof updateTimeWidget !== 'function') {
        function updateTimeWidget() {
            const timeEl = document.getElementById('live-time');
            if (!timeEl) return;
            
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            timeEl.textContent = timeString;
        }

        setInterval(updateTimeWidget, 1000);
        updateTimeWidget();
    }
</script>
