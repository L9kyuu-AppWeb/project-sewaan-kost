<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sewa An Kost')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);
            min-height: 100vh;
            padding-top: 70px;
        }

        /* Header / Navbar */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            height: 70px;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo span {
            font-size: 24px;
        }

        .logo h1 {
            color: #970747;
            font-size: 20px;
            font-weight: 700;
        }

        .logo p {
            color: #666;
            font-size: 12px;
            margin-top: 3px;
        }

        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-link {
            padding: 10px 16px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background: #fce4ec;
            color: #970747;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);
            color: white;
        }

        .nav-link.btn-primary {
            background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);
            color: white;
        }

        .nav-link.btn-primary:hover {
            opacity: 0.9;
            background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 10px;
        }

        .menu-toggle span {
            width: 25px;
            height: 3px;
            background: #970747;
            border-radius: 2px;
            transition: all 0.3s;
        }

        /* User Info in Header */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: #f8f9fa;
            border-radius: 25px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .user-role {
            font-size: 11px;
            color: #666;
            text-transform: capitalize;
            font-weight: 500;
        }

        /* Container */
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 100%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .container-full {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #222;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            color: #222;
            background: #fff;
            transition: border-color 0.3s;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #970747;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            display: inline-block;
            padding: 14px 24px;
            background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(151, 7, 71, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .text-center {
            text-align: center;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .link {
            color: #970747;
            text-decoration: none;
            font-weight: 600;
        }

        .link:hover {
            text-decoration: underline;
        }

        .divider {
            margin: 20px 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #ddd;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #666;
            font-size: 12px;
            position: relative;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .pagination li {
            list-style: none;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .pagination a {
            background: #f0f0f0;
            color: #333;
        }

        .pagination a:hover {
            background: #970747;
            color: white;
        }

        .pagination .active span {
            background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);
            color: white;
        }

        .pagination .disabled span {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
        }

        /* Flash Message Animation */
        .alert {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Demo Account Hover Effect */
        .demo-account {
            transition: all 0.2s ease;
        }

        .demo-account:hover {
            transform: translateX(5px);
        }

        .demo-account:hover .demo-account {
            border-color: #970747;
            box-shadow: 0 2px 8px rgba(151, 7, 71, 0.2);
        }

        /* Mobile Menu Animation */
        @media (max-width: 768px) {
            body {
                padding-top: 60px;
            }

            .header {
                height: 60px;
            }

            .menu-toggle {
                display: flex;
            }

            .nav-menu {
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                transform: translateY(-150%);
                transition: transform 0.3s ease;
            }

            .nav-menu.active {
                transform: translateY(0);
            }

            .nav-link {
                width: 100%;
                padding: 15px;
                text-align: center;
            }

            .user-info {
                display: none;
            }

            .container {
                margin: 20px;
                padding: 20px;
            }

            .container-full {
                margin: 20px;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header / Navbar -->
    <header class="header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="logo">
                <span>🏠</span>
                <h1>Sewa An Kost</h1>
            </a>

            <nav class="nav-menu" id="navMenu">
                @auth
                    @if (auth()->user()->role === 'pemilik')
                        <a href="{{ route('dashboard.pemilik') }}" class="nav-link {{ request()->routeIs('dashboard.pemilik') ? 'active' : '' }}">Dashboard</a>
                        
                        <!-- Dropdown Kelola -->
                        <div style="position: relative; display: inline-block;">
                            <a href="javascript:void(0)" class="nav-link {{ request()->routeIs('kost.*') || request()->routeIs('kamar.*') || request()->routeIs('makanan.*') || request()->routeIs('galon.*') ? 'active' : '' }}" 
                               onclick="toggleDropdown('kelolaDropdown')" 
                               style="cursor: pointer;">
                                Kelola ▾
                            </a>
                            <div id="kelolaDropdown" style="display: none; position: absolute; top: 100%; left: 0; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; min-width: 180px; z-index: 1000; padding: 8px 0;">
                                <a href="{{ route('kost.index') }}" class="nav-link {{ request()->routeIs('kost.*') ? 'active' : '' }}" 
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🏢 Kost
                                </a>
                                <a href="{{ route('kamar.index') }}" class="nav-link {{ request()->routeIs('kamar.*') ? 'active' : '' }}" 
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🛏️ Kamar
                                </a>
                                <a href="{{ route('makanan.index') }}" class="nav-link {{ request()->routeIs('makanan.*') ? 'active' : '' }}" 
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🍽️ Makanan
                                </a>
                                <a href="{{ route('galon.index') }}" class="nav-link {{ request()->routeIs('galon.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    💧 Galon (Katalog)
                                </a>
                                <a href="{{ route('laundry.index') }}" class="nav-link {{ request()->routeIs('laundry.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    👕 Laundry (Katalog)
                                </a>
                            </div>
                        </div>

                        <!-- Dropdown Pesanan -->
                        <div style="position: relative; display: inline-block;">
                            <a href="javascript:void(0)" class="nav-link {{ request()->routeIs('pesan.owner.*') || request()->routeIs('owner.orders.*') || request()->routeIs('owner.galon.*') || request()->routeIs('owner.laundry.*') ? 'active' : '' }}"
                               onclick="toggleDropdown('pesananDropdown')"
                               style="cursor: pointer;">
                                Pesanan ▾
                            </a>
                            <div id="pesananDropdown" style="display: none; position: absolute; top: 100%; left: 0; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; min-width: 180px; z-index: 1000; padding: 8px 0;">
                                <a href="{{ route('pesan.owner.index') }}" class="nav-link {{ request()->routeIs('pesan.owner.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🛏️ Kamar
                                </a>
                                <a href="{{ route('owner.orders.index') }}" class="nav-link {{ request()->routeIs('owner.orders.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🍽️ Makanan
                                </a>
                                <a href="{{ route('owner.galon.index') }}" class="nav-link {{ request()->routeIs('owner.galon.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    💧 Galon
                                </a>
                                <a href="{{ route('owner.laundry.index') }}" class="nav-link {{ request()->routeIs('owner.laundry.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    👕 Laundry
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('dashboard.penyewa') }}" class="nav-link {{ request()->routeIs('dashboard.penyewa') ? 'active' : '' }}">Dashboard</a>

                        <!-- Dropdown Cari -->
                        <div style="position: relative; display: inline-block;">
                            <a href="javascript:void(0)" class="nav-link {{ request()->routeIs('kost-public.*') ? 'active' : '' }}"
                               onclick="toggleDropdown('cariDropdown')"
                               style="cursor: pointer;">
                                Cari ▾
                            </a>
                            <div id="cariDropdown" style="display: none; position: absolute; top: 100%; left: 0; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; min-width: 180px; z-index: 1000; padding: 8px 0;">
                                <a href="{{ route('kost-public.index') }}" class="nav-link {{ request()->routeIs('kost-public.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🏢 Kost
                                </a>
                                <a href="{{ route('orders.create') }}" class="nav-link {{ request()->routeIs('orders.create') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🍽️ Makanan
                                </a>
                                <a href="{{ route('galon.orders.create') }}" class="nav-link {{ request()->routeIs('galon.orders.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    💧 Galon
                                </a>
                                <a href="{{ route('laundry.orders.create') }}" class="nav-link {{ request()->routeIs('laundry.orders.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    👕 Laundry
                                </a>
                            </div>
                        </div>

                        <!-- Dropdown Pemesanan -->
                        <div style="position: relative; display: inline-block;">
                            <a href="javascript:void(0)" class="nav-link {{ request()->routeIs('pesan.*') || request()->routeIs('orders.*') || request()->routeIs('galon.orders.*') || request()->routeIs('laundry.orders.*') ? 'active' : '' }}"
                               onclick="toggleDropdown('pemesananDropdown')"
                               style="cursor: pointer;">
                                Pemesanan ▾
                            </a>
                            <div id="pemesananDropdown" style="display: none; position: absolute; top: 100%; left: 0; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; min-width: 180px; z-index: 1000; padding: 8px 0;">
                                <a href="{{ route('pesan.index') }}" class="nav-link {{ request()->routeIs('pesan.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🛏️ Kamar
                                </a>
                                <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    🍽️ Makanan
                                </a>
                                <a href="{{ route('galon.orders.index') }}" class="nav-link {{ request()->routeIs('galon.orders.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    💧 Galon
                                </a>
                                <a href="{{ route('laundry.orders.index') }}" class="nav-link {{ request()->routeIs('laundry.orders.*') ? 'active' : '' }}"
                                   style="display: block; padding: 10px 16px; color: #333; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 6px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#fce4ec'; this.style.color='#970747'"
                                   onmouseout="this.style.background=''; this.style.color=''">
                                    👕 Laundry
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <div class="user-info">
                        <div class="user-avatar">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</div>
                        <div>
                            <div class="user-name">{{ auth()->user()->nama_lengkap }}</div>
                            <div class="user-role">{{ auth()->user()->role }}</div>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin logout?')">
                        @csrf
                        <button type="submit" class="nav-link btn-primary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
                    <a href="{{ route('register') }}" class="nav-link btn-primary">Register</a>
                @endauth
            </nav>

            <div class="menu-toggle" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    @yield('content')

    <script>
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('navMenu');
            const toggle = document.querySelector('.menu-toggle');
            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.classList.remove('active');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id$="Dropdown"]');
            dropdowns.forEach(function(dropdown) {
                if (!dropdown.contains(event.target) && !event.target.closest('[onclick*="toggleDropdown"]')) {
                    dropdown.style.display = 'none';
                }
            });
        });

        // Auto-dismiss flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>
