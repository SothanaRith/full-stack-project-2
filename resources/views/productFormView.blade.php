<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Cafe Shop</title>
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
            background-color: #f8fafc;
        }

        .form-card {
            max-width: 680px;
            margin: 0 auto;
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .btn-primary-custom {
            background-color: #18181b;
            border-color: #18181b;
            color: #fff;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .preview-container {
            max-height: 220px;
            border: 2px dashed #cbd5e1;
            border-radius: 0.75rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
        }

        .preview-container img {
            max-height: 220px;
            width: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body class="py-4 py-md-5">
    <div class="container">
        <div class="card form-card bg-white p-4 p-md-5">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h2 class="h4 fw-bold mb-1">Add New Product</h2>
                    <p class="text-muted small mb-0">Fill in the details to add a coffee or snack to the menu.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        &larr; Back to Dashboard
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-4">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/create-product" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-12">
                        <label for="name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Caramel Macchiato" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tagline & Badge --}}
                    <div class="col-md-7">
                        <label for="tagline" class="form-label fw-semibold">Tagline <span class="text-danger">*</span></label>
                        <input type="text" name="tagline" id="tagline" class="form-control @error('tagline') is-invalid @enderror"
                            placeholder="e.g. Rich espresso with vanilla & caramel" value="{{ old('tagline') }}" required>
                        @error('tagline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="badge" class="form-label fw-semibold">Badge <span class="text-muted small">(Optional)</span></label>
                        <input type="text" name="badge" id="badge" class="form-control @error('badge') is-invalid @enderror"
                            placeholder="e.g. Best Seller / New" value="{{ old('badge') }}">
                        @error('badge')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Price & Currency --}}
                    <div class="col-md-7">
                        <label for="price" class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror" placeholder="e.g. 4.50"
                            value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="currency" class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                        <input type="text" name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror"
                            placeholder="e.g. $" value="{{ old('currency', '$') }}" required>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Describe flavor notes, roast profile, or ingredients..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Image File Upload --}}
                    <div class="col-12">
                        <label for="images" class="form-label fw-semibold">Product Image (File)</label>
                        <input type="file" name="images" id="images" class="form-control @error('images') is-invalid @enderror"
                            accept="image/png, image/jpeg, image/jpg, image/webp, image/gif, image/svg+xml"
                            onchange="previewImage(event)">
                        <div class="form-text">Accepted formats: JPG, PNG, WEBP, GIF, SVG (Max 5MB).</div>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Preview Box --}}
                        <div id="previewWrapper" class="mt-3 d-none">
                            <label class="form-label small text-muted">Image Preview:</label>
                            <div class="preview-container">
                                <img id="imagePreview" src="#" alt="Selected image preview">
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="col-12 mt-4 pt-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-grow-1">
                            Save Product
                        </button>
                        <a href="/product-view" class="btn btn-light border px-4">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const previewWrapper = document.getElementById('previewWrapper');
            const previewImage = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewWrapper.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewWrapper.classList.add('d-none');
                previewImage.src = '#';
            }
        }
    </script>
</body>

</html>