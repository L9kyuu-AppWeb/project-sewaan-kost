@extends('layouts.app')

@section('title', 'Pesan Makanan - ' . $kost->nama_kost)

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('food.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Menu Makanan
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">🛒 Pesan Makanan</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">{{ $kost->nama_kost }}</p>
    </div>

    @if (session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            <strong style="color: #721c24;">⚠️ Terdapat kesalahan:</strong>
            <ul style="margin: 10px 0 0 20px; color: #721c24;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
        @csrf
        
        <!-- Hidden inputs for cart data -->
        <div id="cartItems"></div>
        
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 20px;">
            <!-- Menu Items -->
            <div>
                <h3 style="color: white; margin-bottom: 15px; font-size: 18px;">📋 Pilih Menu</h3>
                
                @if ($makanans->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                        @foreach ($makanans as $m)
                            <div class="menu-item" style="background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                                 data-id="{{ $m->id_makanan }}" 
                                 data-nama="{{ $m->nama_makanan }}" 
                                 data-harga="{{ $m->harga }}" 
                                 data-stok="{{ $m->stok }}">
                                <div style="height: 120px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                    @if ($m->foto_makanan)
                                        <img src="{{ asset('storage/' . $m->foto_makanan) }}" alt="{{ $m->nama_makanan }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <span style="font-size: 40px; color: rgba(255,255,255,0.5);">🍽️</span>
                                    @endif
                                </div>
                                
                                <h4 style="font-size: 16px; color: #333; margin-bottom: 5px;">{{ $m->nama_makanan }}</h4>
                                <p style="font-size: 14px; color: #666; margin-bottom: 8px;">
                                    Stok: <strong style="color: #43e97b;">{{ $m->stok }}</strong> porsi
                                </p>
                                <p style="font-size: 18px; color: #970747; font-weight: 700; margin-bottom: 10px;">
                                    Rp {{ number_format($m->harga, 0, ',', '.') }}
                                </p>
                                
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <button type="button" class="btn-decrease" style="width: 35px; height: 35px; background: #f0f0f0; border: none; border-radius: 6px; font-size: 18px; font-weight: 600; color: #666; cursor: pointer;">
                                        -
                                    </button>
                                    <input type="number" class="quantity-input" value="0" min="0" max="{{ $m->stok }}" readonly 
                                           style="width: 60px; height: 35px; text-align: center; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-weight: 600;">
                                    <button type="button" class="btn-increase" style="width: 35px; height: 35px; background: #970747; border: none; border-radius: 6px; font-size: 18px; font-weight: 600; color: white; cursor: pointer;">
                                        +
                                    </button>
                                </div>
                                
                                <div style="margin-top: 10px;">
                                    <input type="text" class="catatan-item-input" placeholder="Catatan (opsional)" 
                                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="background: white; border-radius: 12px; padding: 60px 20px; text-align: center;">
                        <span style="font-size: 60px; display: block; margin-bottom: 20px;">😔</span>
                        <h3 style="color: #970747; margin-bottom: 10px;">Tidak Ada Menu Tersedia</h3>
                        <p style="color: #666;">Maaf, saat ini belum ada menu yang tersedia.</p>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div style="position: sticky; top: 90px;">
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h3 style="color: #333; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #970747; padding-bottom: 10px;">
                        📝 Ringkasan Pesanan
                    </h3>

                    <div id="orderSummary" style="margin-bottom: 15px; max-height: 300px; overflow-y: auto;">
                        <p style="color: #999; text-align: center; padding: 20px 0;">Belum ada menu dipilih</p>
                    </div>

                    <div style="border-top: 2px solid #eee; padding-top: 15px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 14px; color: #666;">Total Item:</span>
                            <strong id="totalItem" style="font-size: 16px; color: #333;">0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                            <span style="font-size: 14px; color: #666;">Total Harga:</span>
                            <strong id="totalHarga" style="font-size: 20px; color: #970747;">Rp 0</strong>
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="catatan_pesanan" style="display: block; color: #333; font-weight: 600; margin-bottom: 5px; font-size: 13px;">
                            Catatan Pesanan (Opsional)
                        </label>
                        <textarea name="catatan_pesanan" id="catatan_pesanan" rows="2" placeholder="Contoh: Antar ke kamar 101, Jangan terlalu pedas" 
                                  style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px; resize: vertical;"></textarea>
                    </div>

                    <button type="submit" id="btnSubmit" style="width: 100%; padding: 14px; background: #970747; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; opacity: 0.5; pointer-events: none;" disabled>
                        📦 Buat Pesanan
                    </button>

                    <a href="{{ route('orders.index') }}" style="display: block; text-align: center; margin-top: 10px; padding: 12px; color: #666; text-decoration: none; font-size: 13px; font-weight: 600;">
                        Lihat Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const menuItems = document.querySelectorAll('.menu-item');
    const orderSummary = document.getElementById('orderSummary');
    const totalItemEl = document.getElementById('totalItem');
    const totalHargaEl = document.getElementById('totalHarga');
    const btnSubmit = document.getElementById('btnSubmit');
    const cartItemsContainer = document.getElementById('cartItems');
    
    let cart = {};

    menuItems.forEach((item, index) => {
        const id = item.dataset.id;
        const nama = item.dataset.nama;
        const harga = parseInt(item.dataset.harga);
        const stok = parseInt(item.dataset.stok);
        const quantityInput = item.querySelector('.quantity-input');
        const btnIncrease = item.querySelector('.btn-increase');
        const btnDecrease = item.querySelector('.btn-decrease');
        const catatanInput = item.querySelector('.catatan-item-input');

        btnIncrease.addEventListener('click', () => {
            let qty = parseInt(quantityInput.value) || 0;
            if (qty < stok) {
                qty++;
                quantityInput.value = qty;
                updateCart(id, nama, harga, qty, catatanInput.value);
            }
        });

        btnDecrease.addEventListener('click', () => {
            let qty = parseInt(quantityInput.value) || 0;
            if (qty > 0) {
                qty--;
                quantityInput.value = qty;
                updateCart(id, nama, harga, qty, catatanInput.value);
            }
        });

        // Listen for catatan changes
        catatanInput.addEventListener('input', () => {
            if (cart[id]) {
                updateCart(id, nama, harga, parseInt(quantityInput.value) || 0, catatanInput.value);
            }
        });
    });

    function updateCart(id, nama, harga, qty, catatan = '') {
        if (qty > 0) {
            cart[id] = { nama, harga, qty, catatan };
        } else {
            delete cart[id];
        }
        renderSummary();
        updateHiddenInputs();
    }

    function renderSummary() {
        const items = Object.values(cart);
        let totalItem = 0;
        let totalHarga = 0;
        let html = '';

        if (items.length === 0) {
            html = '<p style="color: #999; text-align: center; padding: 20px 0;">Belum ada menu dipilih</p>';
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.5';
            btnSubmit.style.pointerEvents = 'none';
        } else {
            btnSubmit.disabled = false;
            btnSubmit.style.opacity = '1';
            btnSubmit.style.pointerEvents = 'auto';

            items.forEach(item => {
                totalItem += item.qty;
                totalHarga += item.harga * item.qty;
                html += `
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0;">
                        <div style="flex: 1;">
                            <p style="font-size: 13px; color: #333; margin: 0; font-weight: 600;">${item.nama}</p>
                            <p style="font-size: 12px; color: #999; margin: 3px 0 0;">${item.qty} x Rp ${item.harga.toLocaleString('id-ID')}</p>
                            ${item.catatan ? `<p style="font-size: 11px; color: #999; margin: 3px 0 0;">📝 ${item.catatan}</p>` : ''}
                        </div>
                        <strong style="font-size: 13px; color: #970747;">Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</strong>
                    </div>
                `;
            });
        }

        orderSummary.innerHTML = html;
        totalItemEl.textContent = totalItem;
        totalHargaEl.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
    }

    function updateHiddenInputs() {
        // Clear existing inputs
        cartItemsContainer.innerHTML = '';
        
        // Create hidden inputs for each cart item
        let index = 0;
        Object.entries(cart).forEach(([id, item]) => {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <input type="hidden" name="items[${index}][id_makanan]" value="${id}">
                <input type="hidden" name="items[${index}][jumlah]" value="${item.qty}">
                <input type="hidden" name="items[${index}][catatan_item]" value="${item.catatan}">
            `;
            cartItemsContainer.appendChild(wrapper);
            index++;
        });
    }

    // Form submission validation
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const items = Object.values(cart);
        if (items.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal 1 menu untuk dipesan.');
            return false;
        }
    });
</script>
@endsection
