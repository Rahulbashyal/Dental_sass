@props(['patient', 'notes' => []])

<div x-data="{ 
    selectedTooth: null,
    toothNotes: {{ json_encode($notes) }},
    selectTooth(id) {
        this.selectedTooth = id;
        $dispatch('tooth-selected', { id: id });
    },
    getToothStatus(id) {
        return this.toothNotes[id] ? 'has-notes' : 'healthy';
    }
}" class="relative bg-slate-50/50 rounded-[3rem] p-10 border border-slate-100 shadow-inner">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Upper Arch -->
        <div class="space-y-6">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center mb-4">Maxillary (Upper) Arch</h4>
            <div class="flex justify-center flex-wrap gap-2">
                @for($i = 1; $i <= 16; $i++)
                    <div @click="selectTooth({{ $i }})" 
                         :class="selectedTooth === {{ $i }} ? 'ring-4 ring-blue-500 bg-blue-500 text-white' : (toothNotes[{{ $i }}] ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-white text-slate-400 border-slate-100')"
                         class="w-10 h-14 rounded-xl border-2 cursor-pointer flex flex-col items-center justify-center transition-all hover:scale-110 shadow-sm relative overflow-hidden group">
                        <span class="text-[10px] font-black mb-1">#{{ $i }}</span>
                        <i class="fa-solid fa-tooth text-xs"></i>
                        <template x-if="toothNotes[{{ $i }}]">
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-400"></div>
                        </template>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Lower Arch -->
        <div class="space-y-6">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center mb-4">Mandibular (Lower) Arch</h4>
            <div class="flex justify-center flex-wrap gap-2">
                @for($i = 17; $i <= 32; $i++)
                    <div @click="selectTooth({{ $i }})" 
                         :class="selectedTooth === {{ $i }} ? 'ring-4 ring-blue-500 bg-blue-500 text-white' : (toothNotes[{{ $i }}] ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-white text-slate-400 border-slate-100')"
                         class="w-10 h-14 rounded-xl border-2 cursor-pointer flex flex-col items-center justify-center transition-all hover:scale-110 shadow-sm relative overflow-hidden group">
                        <span class="text-[10px] font-black mb-1">#{{ $i }}</span>
                        <i class="fa-solid fa-tooth text-xs"></i>
                        <template x-if="toothNotes[{{ $i }}]">
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-400"></div>
                        </template>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Selection Summary -->
    <div x-show="selectedTooth" x-transition class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black">
                    <span x-text="selectedTooth"></span>
                </div>
                <div>
                    <h5 class="text-sm font-black text-slate-900 leading-tight">Tooth Anatomy Selection</h5>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Clinical Data Mapping</p>
                </div>
            </div>
            <button @click="$dispatch('open-note-modal', { tooth: selectedTooth })" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all">
                Add Observation
            </button>
        </div>

        <div class="space-y-2">
            <template x-if="toothNotes[selectedTooth]">
                <div class="space-y-2">
                    <template x-for="note in toothNotes[selectedTooth]">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[9px] font-black text-blue-600 uppercase tracking-tighter" x-text="note.condition || 'General Observation'"></span>
                                <span class="text-[8px] text-slate-400 font-bold" x-text="note.date"></span>
                            </div>
                            <p class="text-[11px] text-slate-700 leading-relaxed" x-text="note.note"></p>
                        </div>
                    </template>
                </div>
            </template>
            <template x-if="!toothNotes[selectedTooth]">
                <div class="py-4 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase italic">No clinical findings recorded for this tooth.</p>
                </div>
            </template>
        </div>
    </div>
</div>
