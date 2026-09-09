<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #b45309;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        .page-title {
            letter-spacing: -0.02em;
        }

        .product-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 1.25rem 2.5rem -0.75rem rgba(28, 25, 23, 0.35) !important;
        }

        .product-hero {
            aspect-ratio: 1 / 1;
            background: linear-gradient(135deg, #78350f 0%, #292524 55%, #0c0a09 100%);
        }

        .product-hero .carousel-inner,
        .product-hero .carousel-item {
            height: 100%;
        }

        .product-hero .carousel-indicators {
            position: absolute;
            bottom: 0;
            left: 50%;
            right: auto;
            transform: translateX(-50%);
            margin: 0 0 0.5rem 0;
            gap: 4px;
        }

        .product-hero .carousel-indicators [data-bs-target] {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #fff;
            opacity: 0.5;
            margin: 0;
            padding: 0;
            border: 0;
            text-indent: -9999px;
            transition: opacity 0.2s ease;
        }

        .product-hero .carousel-indicators [data-bs-target].active {
            opacity: 1;
        }

        .product-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0) 35%);
            pointer-events: none;
        }

        .product-hero img {
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-hero img {
            transform: scale(1.08);
        }

        .product-hero .placeholder-icon {
            width: clamp(2rem, 6vw, 4rem);
            height: clamp(2rem, 6vw, 4rem);
        }

        .product-badge {
            font-size: clamp(0.5rem, 1vw, 0.65rem);
            letter-spacing: 0.03em;
            text-transform: uppercase;
            backdrop-filter: blur(4px);
        }

        .brand-mark {
            width: clamp(1rem, 2.5vw, 1.75rem);
            height: clamp(1rem, 2.5vw, 1.75rem);
            transition: transform 0.25s ease;
        }

        .product-card:hover .brand-mark {
            transform: scale(1.1);
        }

        .brand-mark svg {
            width: 55%;
            height: 55%;
        }

        .product-title {
            font-size: clamp(0.7rem, 1.6vw, 1rem);
            letter-spacing: -0.01em;
        }

        .product-tagline {
            font-size: clamp(0.6rem, 1.2vw, 0.8rem);
            color: var(--accent);
        }

        .product-desc {
            font-size: 0.8rem;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: clamp(0.6rem, 1.3vw, 0.8rem);
            border-color: #e7e5e4 !important;
        }

        .add-to-cart-btn {
            width: clamp(1.5rem, 3vw, 2.25rem);
            height: clamp(1.5rem, 3vw, 2.25rem);
            flex-shrink: 0;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .add-to-cart-btn:hover {
            transform: scale(1.12);
            background-color: var(--accent) !important;
        }

        .add-to-cart-btn:active {
            transform: scale(0.95);
        }

        .add-to-cart-btn svg {
            width: 45%;
            height: 45%;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container-fluid p-3 p-md-5">
        <h1 class="page-title fw-bold text-dark mb-1 fs-3 fs-md-2">Our Menu</h1>
        <p class="text-muted mb-4">Freshly brewed favorites, made to order.</p>

        <button type="button" class="btn btn-dark rounded-pill px-3 py-2 mb-4"
            onclick="window.location.href='/product-form-view'">
            Add New Product
        </button>
        <div class="row row-cols-4 g-2 g-md-4">
            @foreach ($products as $product)
                <div class="col">
                    <div class="product-card card h-100 border-0 shadow-sm rounded-4 p-2">

                        {{-- Image / hero area --}}
                        <div id="hero-carousel-{{ $loop->index }}"
                            class="carousel slide carousel-fade rounded-3 overflow-hidden position-relative product-hero"
                            data-bs-ride="carousel" data-bs-interval="2500" aria-label="{{ $product['name'] }} photos">

                            @php
                                $imgSrc = $product->image_url ?? (!empty($product['images']) ? (\Illuminate\Support\Str::startsWith($product['images'], ['http://', 'https://', '//']) ? $product['images'] : asset('storage/' . $product['images'])) : null);
                            @endphp
                            @if (!empty($imgSrc))
                                <img src="{{ $imgSrc }}" alt="{{ $product['name'] }}" class="d-block w-100 h-100">
                            @else
                                {{-- Placeholder artwork until a real product photo is added --}}
                                <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                    <svg class="placeholder-icon text-white-50" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.2">
                                        <path d="M4 8h13a3 3 0 0 1 0 6h-1" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4 8v7a4 4 0 0 0 4 4h4a4 4 0 0 0 4-4v-1" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M7 3c0 1-1 1-1 2s1 1 1 2M11 3c0 1-1 1-1 2s1 1 1 2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Badge --}}
                            @if (!empty($product['badge']))
                                <span
                                    class="product-badge badge bg-dark bg-opacity-75 position-absolute top-0 start-0 m-2 fw-medium d-none d-sm-inline-block">
                                    {{ $product['badge'] }}
                                </span>
                            @endif

                            {{-- Action buttons (Edit & Delete) --}}
                            <div class="position-absolute top-0 end-0 m-2 d-flex gap-1 z-3">
                                <a href="/edit-product/{{ $product['id'] }}"
                                    class="btn btn-light bg-white rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center border-0 text-dark"
                                    style="width: 28px; height: 28px;" title="Edit Product">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form action="/delete-product/{{ $product['id'] }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center border-0"
                                        style="width: 28px; height: 28px;" title="Delete Product">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="card-body px-1 pt-3 pb-1 d-flex flex-column">
                            <h6 class="product-title fw-semibold text-dark text-truncate mb-0">{{$product['name']}}</h6>
                            <p class="product-tagline fw-medium mb-0 mt-1 text-truncate">{{ $product['tagline'] }}</p>
                            <p class="product-desc text-muted mt-2 mb-0 d-none d-sm-block">{{ $product['description'] }}</p>

                            <div class="d-flex align-items-center justify-content-between mt-2 mt-md-3 gap-1">
                                <span
                                    class="product-price badge bg-light text-dark border fw-semibold rounded-pill px-2 py-2 text-truncate">
                                    {{ $product['currency'] }}{{ number_format($product['price'], 2) }}
                                </span>

                                <button type="button"
                                    class="add-to-cart-btn btn btn-dark rounded-circle d-flex align-items-center justify-content-center p-0"
                                    aria-label="Add {{ $product['name'] }} to cart">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="9" cy="21" r="1" />
                                        <circle cx="20" cy="21" r="1" />
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>