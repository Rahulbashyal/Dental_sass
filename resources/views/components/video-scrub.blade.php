<div x-data="{
    scrollProgress: 0,
    init() {
        const video = this.$refs.scrubVideo;
        const container = this.$refs.scrubContainer;
        
        ScrollTrigger.create({
            trigger: container,
            start: 'top top',
            end: 'bottom bottom',
            scrub: true,
            onUpdate: (self) => {
                this.scrollProgress = self.progress;
                if (video.duration) {
                    video.currentTime = video.duration * self.progress;
                }
            }
        });
    }
}" 
x-ref="scrubContainer"
class="relative w-full h-[300vh] bg-slate-950">
    
    <div class="sticky top-0 left-0 w-full h-screen flex items-center justify-center overflow-hidden">
        <video x-ref="scrubVideo"
               src="{{ $videoUrl }}"
               preload="auto"
               muted
               playsinline
               class="absolute inset-0 w-full h-full object-cover opacity-60">
        </video>
        
        <!-- Content Overlays based on scroll progress -->
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <template x-if="true">
                <div class="space-y-4">
                    <h2 class="text-5xl md:text-7xl font-black tracking-tighter" 
                        :style="{ opacity: scrollProgress < 0.3 ? 1 : 0.2 }">
                        {{ $title }}
                    </h2>
                    <p class="text-xl text-blue-400 font-bold uppercase tracking-[0.3em]"
                       :style="{ opacity: scrollProgress < 0.3 ? 1 : 0 }">
                        Phase I: The Foundation
                    </p>
                </div>
            </template>
        </div>
        
        <!-- Deep Clinical Data Overlay -->
        <div class="absolute bottom-12 left-12 glass-card p-6 max-w-xs border-l-4 border-blue-500">
            <p class="text-[10px] font-black uppercase text-slate-500 mb-2">Clinical Precision Meter</p>
            <div class="h-1 w-full bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 transition-all duration-300" :style="{ width: (scrollProgress * 100) + '%' }"></div>
            </div>
            <p class="mt-2 text-xs font-medium text-slate-400">
                Current State: <span class="text-white" x-text="scrollProgress < 0.5 ? 'Structural Analysis' : 'Integration Verified'"></span>
            </p>
        </div>
    </div>
</div>
