@if(auth()->user()->hasRole('clinic_admin'))
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Clinic Landing Page</h6>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Customize your clinic's public landing page</p>
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('landing-page-manager') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-edit"></i> Customize Landing Page
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('clinic.landing', auth()->user()->clinic->slug) }}" target="_blank" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-external-link-alt"></i> View Public Page
                </a>
            </div>
        </div>
    </div>
</div>
@endif