<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>Admin Dashboard</span>
                    <span class="text-xs px-2.5 py-1 font-semibold rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                        Admin Portal
                    </span>
                </h2>
                <p class="text-sm text-stone-500 mt-1">Manage cafe products, menu items, prices, and catalog status.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('productView') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-stone-300 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 transition shadow-sm">
                    <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>View Customer Menu</span>
                </a>
                <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#3b271e] hover:bg-[#4d3328] text-white rounded-xl text-sm font-semibold transition shadow-md shadow-[#3b271e]/15">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add New Product</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-stone-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Metric Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-6 rounded-2xl border border-stone-200/80 shadow-sm flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center p-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Products</p>
                        <h3 class="text-2xl font-bold text-stone-900 mt-0.5">{{ $stats['total_products'] ?? count($products) }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-stone-200/80 shadow-sm flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center p-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Average Menu Price</p>
                        <h3 class="text-2xl font-bold text-stone-900 mt-0.5">${{ number_format($stats['avg_price'] ?? 0, 2) }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-stone-200/80 shadow-sm flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center p-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Featured Badges</p>
                        <h3 class="text-2xl font-bold text-stone-900 mt-0.5">{{ $stats['badges_count'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            {{-- Products Table Card --}}
            <div class="bg-white rounded-2xl border border-stone-200/80 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-stone-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">Product Management</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Create, update details, upload images, or delete cafe menu items.</p>
                    </div>

                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <svg class="w-4 h-4 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search products..." class="pl-9 pr-3 py-2 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 w-full sm:w-64">
                        </div>
                        @if(!empty($search))
                            <a href="{{ route('dashboard') }}" class="text-xs text-stone-500 hover:text-stone-800 underline px-1">Clear</a>
                        @endif
                    </form>
                </div>

                @if($products->isEmpty())
                    <div class="text-center py-16 px-4">
                        <div class="w-16 h-16 bg-amber-50 text-amber-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-stone-800 mb-1">No products found</h4>
                        <p class="text-sm text-stone-500 max-w-sm mx-auto mb-6">
                            @if(!empty($search))
                                No results matching "{{ $search }}". Try searching for another item.
                            @else
                                You haven't added any products to your cafe menu yet.
                            @endif
                        </p>
                        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#3b271e] hover:bg-[#4d3328] text-white rounded-xl text-sm font-semibold transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Your First Product</span>
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-stone-50/75 border-b border-stone-200 text-[11px] font-bold uppercase tracking-wider text-stone-500">
                                    <th class="py-3.5 px-6">Product</th>
                                    <th class="py-3.5 px-6">Badge / Tag</th>
                                    <th class="py-3.5 px-6">Price</th>
                                    <th class="py-3.5 px-6">Description</th>
                                    <th class="py-3.5 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-sm">
                                @foreach ($products as $product)
                                    <tr class="hover:bg-amber-50/30 transition">
                                        {{-- Product info & thumbnail --}}
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3.5">
                                                <div class="w-12 h-12 rounded-xl bg-stone-100 border border-stone-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                                    @php
                                                        $imgSrc = $product->image_url ?? (!empty($product['images']) ? (\Illuminate\Support\Str::startsWith($product['images'], ['http://', 'https://', '//']) ? $product['images'] : asset('storage/' . $product['images'])) : null);
                                                    @endphp
                                                    @if (!empty($imgSrc))
                                                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-xl">☕</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-stone-900 leading-tight">{{ $product->name }}</h4>
                                                    <p class="text-xs text-amber-800 font-medium mt-0.5">{{ $product->tagline }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Badge --}}
                                        <td class="py-4 px-6">
                                            @if(!empty($product->badge))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-800 text-white">
                                                    {{ $product->badge }}
                                                </span>
                                            @else
                                                <span class="text-xs text-stone-400 italic">None</span>
                                            @endif
                                        </td>

                                        {{-- Price --}}
                                        <td class="py-4 px-6 font-semibold text-stone-900 whitespace-nowrap">
                                            {{ $product->currency }}{{ number_format($product->price, 2) }}
                                        </td>

                                        {{-- Description --}}
                                        <td class="py-4 px-6 text-xs text-stone-500 max-w-xs truncate">
                                            {{ $product->description }}
                                        </td>

                                        {{-- Actions --}}
                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Edit Button --}}
                                                <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-stone-200 bg-white text-xs font-semibold text-stone-700 hover:bg-stone-50 hover:text-amber-800 transition shadow-sm" title="Edit Product">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    <span>Edit</span>
                                                </a>

                                                {{-- Delete Button with Form --}}
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($product->name) }}\'? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-xs font-semibold text-red-700 hover:bg-red-100 transition shadow-sm" title="Delete Product">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span>Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
