<section class="feature" style="{{ isset($featureSetting) && $featureSetting->background_image ? 'background-image: url(' . asset($featureSetting->background_image) . '); background-size: cover; background-position: center;' : '' }}">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-md-6 text-white">
                <h2 class="section-title feature-title">{{ optional($featureSetting)->title ?? 'Sección destacada' }}</h2>
                <p class="text-white feature-subtitle">
                    {{ optional($featureSetting)->subtitle ?? 'Agrega un mensaje desde la configuración de la sección destacada.' }}
                </p>
                <a href="#" class="btn btn-view-works" style="background-color: {{ optional($featureSetting)->button_color ?? '#691c32' }}; border-color: {{ optional($featureSetting)->button_color ?? '#FF7A00' }};">
                    {{ optional($featureSetting)->button_text ?? 'Ver' }}
                </a>
            </div>
        </div>
    </div>
</section>