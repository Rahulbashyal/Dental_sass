<div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-4 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-medium opacity-90">आजको मिति</h3>
            <div class="text-2xl font-bold mt-1">{{ \App\Helpers\NepaliDateHelper::getCurrentNepaliDate()['formatted'] ?? '१ मंसिर २०८२' }}</div>
            <div class="text-sm opacity-90 mt-1">{{ date('l') }}</div>
        </div>
        <div class="text-right">
            <div class="text-sm opacity-90">English Date</div>
            <div class="text-lg font-semibold">{{ date('M d, Y') }}</div>
            <div class="text-sm opacity-90">{{ date('l') }}</div>
        </div>
    </div>
    
    <div class="mt-3 pt-3 border-t border-blue-400 border-opacity-30">
        <div class="flex justify-between text-sm">
            @php
                $nepaliMonth = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate()['month'] ?? 8;
                $nepaliYear = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate()['year'] ?? 2082;
                $nepaliMonthName = \App\Services\NepaliCalendarService::getMonthName($nepaliMonth) ?? 'मंसिर';
            @endphp
            <span>{{ $nepaliMonthName }} {{ $nepaliYear }}</span>
            <span id="live-time" class="font-mono"></span>
        </div>
    </div>
</div>

<script>
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: '2-digit', 
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('live-time').textContent = timeString;
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call

// Refresh Nepali date every hour
setInterval(function() {
    location.reload();
}, 3600000); // 1 hour in milliseconds
</script>
