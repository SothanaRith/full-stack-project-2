<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Menu - Cafe Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #b45309;
            --accent-hover: #92400e;
            --primary-dark: #2b1d17;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background-color: #f8fafc;
            color: #1c1917;
        }

        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e7e5e4;
        }

        .product-card {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 1.25rem 2.5rem -0.75rem rgba(43, 29, 23, 0.15) !important;
        }

        .product-hero {
            aspect-ratio: 1 / 1;
            background: linear-gradient(135deg, #44281d 0%, #292524 55%, #0c0a09 100%);
            position: relative;
            overflow: hidden;
        }

        .product-hero img {
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-hero img {
            transform: scale(1.08);
        }

        .product-hero .placeholder-icon {
            width: clamp(2.5rem, 6vw, 3.5rem);
            height: clamp(2.5rem, 6vw, 3.5rem);
        }

        .product-badge {
            font-size: 0.65rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .product-title {
            font-size: 1.05rem;
            letter-spacing: -0.01em;
            font-weight: 700;
        }

        .product-tagline {
            font-size: 0.8rem;
            color: var(--accent);
            font-weight: 600;
        }

        .product-desc {
            font-size: 0.825rem;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #78716c;
        }

        .product-price {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1c1917;
        }

        .btn-view-detail {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            background-color: #f5f5f4;
            color: #292524;
            border: 1px solid #e7e5e4;
            transition: all 0.2s ease;
        }

        .btn-view-detail:hover {
            background-color: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        .modal-product-img {
            max-height: 380px;
            width: 100%;
            object-fit: cover;
            border-radius: 1rem;
        }

        .brand-badge {
            background: #f4dfca;
            color: #3b271e;
        }
    </style>
</head>

<body>

    {{-- Top Navigation Bar --}}
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3 shadow-sm">
        <div class="container-fluid px-3 px-md-5">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark fs-5" href="{{ route('productView') }}">
                <span class="rounded-3 brand-badge p-2 d-inline-flex align-items-center justify-content-center">
                    ☕
                </span>
                <span>Cafe Shop</span>
            </a>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="btn btn-dark btn-sm rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-1 shadow-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Admin Dashboard</span>
                        </a>
                    @endif

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 dropdown-toggle d-flex align-items-center gap-2 bg-white" type="button" data-bs-toggle="dropdown">
                            <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                            <span class="badge {{ Auth::user()->role === 'admin' ? 'bg-warning text-dark' : 'bg-light text-secondary border' }} rounded-pill">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 mt-2">
                            @if (Auth::user()->role === 'admin')
                                <li><a class="dropdown-item py-2 small fw-semibold" href="{{ route('dashboard') }}">Admin Dashboard</a></li>
                                <li><a class="dropdown-item py-2 small fw-semibold" href="{{ route('products.create') }}">Add New Product</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li><a class="dropdown-item py-2 small" href="{{ route('profile.edit') }}">Profile Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 small text-danger fw-semibold">
                                        Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-dark btn-sm rounded-pill px-3">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Container --}}
    <main class="container-fluid px-3 px-md-5 py-4 py-md-5">

        {{-- Flash messages --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Header & Search --}}
        <div class="d-flex flex-column flex-md-row md:items-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
            <div>
                <h1 class="fw-bold text-dark mb-1 fs-3 fs-md-2">Our Menu</h1>
                <p class="text-muted mb-0">Handcrafted coffees, teas, and specialty bakery items freshly prepared for you.</p>
            </div>

            <form method="GET" action="{{ route('productView') }}" class="d-flex align-items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search menu..." class="form-control rounded-pill px-3 py-2 form-control-sm" style="min-width: 220px;">
                <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3 py-2">Search</button>
                @if(!empty($search))
                    <a href="{{ route('productView') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5">Clear</a>
                @endif
            </form>
        </div>

        {{-- Product Grid --}}
        @if($products->isEmpty())
            <div class="text-center py-5 my-5">
                <div class="display-6 mb-3">☕</div>
                <h4 class="fw-bold text-secondary mb-2">No items found</h4>
                <p class="text-muted">
                    @if(!empty($search))
                        No results match "{{ $search }}". Try searching another keyword.
                    @else
                        Our menu is currently being updated. Please check back shortly!
                    @endif
                </p>
                @if(!empty($search))
                    <a href="{{ route('productView') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 mt-2">View Full Menu</a>
                @endif
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
                @foreach ($products as $product)
                    @php
                        $imgSrc = $product->image_url ?? (!empty($product['images']) ? (\Illuminate\Support\Str::startsWith($product['images'], ['http://', 'https://', '//']) ? $product['images'] : asset('storage/' . $product['images'])) : null);
                    @endphp

                    <div class="col">
                        <div class="product-card card h-100 rounded-4 p-2.5 shadow-sm"
                            onclick="openProductDetailModal({{ json_encode([
                                'id' => $product->id,
                                'name' => $product->name,
                                'tagline' => $product->tagline,
                                'badge' => $product->badge,
                                'price' => $product->currency . number_format($product->price, 2),
                                'description' => $product->description,
                                'image' => $imgSrc
                            ]) }})"
                            role="button"
                            tabindex="0">

                            {{-- Image / Hero --}}
                            <div class="rounded-3 overflow-hidden position-relative product-hero">
                                @if (!empty($imgSrc))
                                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="d-block w-100 h-100">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 text-white-50">
                                        <svg class="placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3">
                                            <path d="M4 8h13a3 3 0 0 1 0 6h-1" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M4 8v7a4 4 0 0 0 4 4h4a4 4 0 0 0 4-4v-1" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M7 3c0 1-1 1-1 2s1 1 1 2M11 3c0 1-1 1-1 2s1 1 1 2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Badge --}}
                                @if (!empty($product->badge))
                                    <span class="product-badge badge bg-dark bg-opacity-75 position-absolute top-0 start-0 m-2 fw-semibold rounded-pill px-2.5 py-1">
                                        {{ $product->badge }}
                                    </span>
                                @endif
                            </div>

                            {{-- Card Details --}}
                            <div class="card-body px-1 pt-3 pb-1 d-flex flex-column">
                                <h2 class="product-title text-dark text-truncate mb-0">{{ $product->name }}</h2>
                                <p class="product-tagline mb-0 mt-1 text-truncate">{{ $product->tagline }}</p>
                                <p class="product-desc mt-2 mb-0">{{ $product->description }}</p>

                                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                    <span class="product-price">
                                        {{ $product->currency }}{{ number_format($product->price, 2) }}
                                    </span>

                                    <button type="button" class="btn btn-view-detail d-inline-flex align-items-center gap-1.5"
                                        onclick="event.stopPropagation(); openProductDetailModal({{ json_encode([
                                            'id' => $product->id,
                                            'name' => $product->name,
                                            'tagline' => $product->tagline,
                                            'badge' => $product->badge,
                                            'price' => $product->currency . number_format($product->price, 2),
                                            'description' => $product->description,
                                            'image' => $imgSrc
                                        ]) }})">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>View Details</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </main>

    {{-- Product Detail Modal (For normal users & visitors to view rich product details) --}}
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0 pt-3 px-4">
                    <span id="modalProductBadge" class="badge bg-dark rounded-pill px-3 py-1 text-uppercase" style="font-size: 0.7rem; display: none;"></span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6">
                            <div id="modalImageWrapper" class="rounded-3 overflow-hidden bg-light shadow-sm d-flex align-items-center justify-content-center" style="min-height: 260px; background: linear-gradient(135deg, #44281d 0%, #292524 55%, #0c0a09 100%);">
                                <img id="modalProductImage" src="" alt="Product" class="modal-product-img d-none">
                                <div id="modalPlaceholder" class="text-white-50 p-4 text-center">
                                    <span class="fs-1">☕</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-center">
                            <h2 id="modalProductName" class="h3 fw-bold text-dark mb-1"></h2>
                            <p id="modalProductTagline" class="text-warning-emphasis fw-semibold mb-3 fs-6" style="color: var(--accent) !important;"></p>
                            
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <span class="text-muted small d-block mb-1 font-monospace">DESCRIPTION</span>
                                <p id="modalProductDescription" class="text-secondary small mb-0 lh-base"></p>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                <div>
                                    <span class="text-muted small d-block">Price</span>
                                    <span id="modalProductPrice" class="fs-4 fw-bold text-dark"></span>
                                </div>
                                <button type="button" class="btn btn-dark rounded-pill px-4 py-2" data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openProductDetailModal(product) {
            document.getElementById('modalProductName').textContent = product.name || '';
            document.getElementById('modalProductTagline').textContent = product.tagline || '';
            document.getElementById('modalProductDescription').textContent = product.description || 'No description provided.';
            document.getElementById('modalProductPrice').textContent = product.price || '';

            const badgeEl = document.getElementById('modalProductBadge');
            if (product.badge) {
                badgeEl.textContent = product.badge;
                badgeEl.style.display = 'inline-block';
            } else {
                badgeEl.style.display = 'none';
            }

            const imgEl = document.getElementById('modalProductImage');
            const placeholderEl = document.getElementById('modalPlaceholder');

            if (product.image) {
                imgEl.src = product.image;
                imgEl.classList.remove('d-none');
                placeholderEl.classList.add('d-none');
            } else {
                imgEl.src = '';
                imgEl.classList.add('d-none');
                placeholderEl.classList.remove('d-none');
            }

            const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            modal.show();
        }
    </script>
</body>

</html>