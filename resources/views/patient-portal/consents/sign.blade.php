@extends('patient-portal.layout')

@section('title', 'Execute Authorization')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden flex flex-col h-[85vh]">
        <!-- Header -->
        <div class="px-10 py-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center border border-slate-200 shadow-sm text-slate-400">
                    <i class="fas fa-file-signature text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-none Outfit">{{ $consent->template->title }}</h1>
                    <p class="text-xs text-slate-400 font-black uppercase tracking-widest mt-2">
                        Digital Verification Node: <span class="Outfit" style="color: {{ $clinicColor }}">{{ $consent->clinic->name }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('patient.consents') }}" class="w-10 h-10 rounded-full bg-white border border-slate-100 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-all shadow-sm">
                <i class="fas fa-times text-sm"></i>
            </a>
        </div>

        <!-- Document Content -->
        <div class="flex-1 overflow-y-auto p-12 no-scrollbar bg-white">
            <div class="max-w-none prose prose-slate prose-headings:font-black prose-headings:text-slate-900 prose-headings:Outfit prose-p:text-slate-600 prose-p:font-medium prose-p:leading-relaxed">
                {!! $consent->template->content !!}
            </div>
            
            <div class="mt-16 p-8 rounded-[2rem] border border-slate-100 relative overflow-hidden group transition-all duration-500 hover:border-slate-200" style="background-color: {{ $clinicColor }}08">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-16 -mt-16 blur-2xl opacity-10" style="background-color: {{ $clinicColor }}"></div>
                <div class="flex gap-5 relative z-10">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg text-white" style="background-color: {{ $clinicColor }}">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-relaxed Outfit">
                            "By executing this signature, I acknowledge thorough review and comprehension of the clinical terms outlined above. This digital timestamp serves as a legally binding authorization within our synchronized health network."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signing Area -->
        <div class="px-10 py-10 border-t border-slate-100 bg-slate-50/80 backdrop-blur-md">
            <form action="{{ route('patient.consents.sign', $consent) }}" method="POST" id="signature-form">
                @csrf
                <input type="hidden" name="signature_data" id="signature-input">
                
                <div class="flex flex-col md:flex-row items-end gap-10">
                    <div class="flex-1 space-y-4 w-full">
                        <div class="flex justify-between items-end px-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-marker text-slate-300"></i>
                                Authentication Interface
                            </label>
                            <button type="button" id="clear-signature" class="text-xs font-black text-rose-500 uppercase tracking-widest hover:text-rose-700 transition-colors">
                                <i class="fas fa-eraser mr-1"></i> Flush Pad
                            </button>
                        </div>
                        <div class="relative bg-white border-2 border-dashed border-slate-200 rounded-[2.5rem] overflow-hidden h-44 transition-all shadow-inner focus-within:border-slate-400 group">
                            <canvas id="signature-pad" class="absolute inset-0 w-full h-full cursor-crosshair"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10 group-focus-within:opacity-0 transition-opacity">
                                <p class="text-xs font-black uppercase tracking-[0.4em] text-slate-400">Initialize Signature Here</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full md:w-auto px-16 py-6 text-white rounded-[2rem] text-sm font-black uppercase tracking-widest shadow-2xl transform active:scale-95 transition-all shadow-slate-200"
                            style="background-color: {{ $clinicColor }}">
                        Sync Signature
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="flex items-center justify-center gap-8 opacity-40 grayscale pt-6">
        <div class="flex items-center gap-2 text-slate-900">
            <i class="fas fa-lock text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">ECC-Encrypted</span>
        </div>
        <div class="flex items-center gap-2 text-slate-900">
            <i class="fas fa-user-shield text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">RFC Compliant</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d');
        const form = document.getElementById('signature-form');
        const input = document.getElementById('signature-input');
        const clearBtn = document.getElementById('clear-signature');
        
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            ctx.scale(ratio, ratio);
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        let signing = false;
        ctx.strokeStyle = '#0f172a'; // Slate-900
        ctx.lineWidth = 3;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            return {
                x: (e.clientX || (e.touches ? e.touches[0].clientX : 0)) - rect.left,
                y: (e.clientY || (e.touches ? e.touches[0].clientY : 0)) - rect.top
            };
        }

        function start(e) {
            signing = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            if(e.type === 'touchstart') e.preventDefault();
        }

        function draw(e) {
            if (!signing) return;
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            if(e.type === 'touchmove') e.preventDefault();
        }

        function stop() {
            signing = false;
        }

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stop);
        canvas.addEventListener('mouseleave', stop);

        canvas.addEventListener('touchstart', start, {passive: false});
        canvas.addEventListener('touchmove', draw, {passive: false});
        canvas.addEventListener('touchend', stop);

        clearBtn.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        form.addEventListener('submit', (e) => {
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            if (canvas.toDataURL() === blank.toDataURL()) {
                alert('Buffer empty. Signature required for authorization.');
                e.preventDefault();
                return;
            }
            input.value = canvas.toDataURL();
        });
    });
</script>
@endpush
@endsection
