<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hospital Management') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Navigation Header */
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 4.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: #0f172a;
        }

        .brand-title {
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.015em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-link {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
            padding: 0.5rem 0.15rem;
            position: relative;
            transition: color 0.15s ease;
        }

        .nav-link:hover {
            color: #4f46e5;
        }

        .nav-link.active {
            color: #4f46e5;
            font-weight: 700;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1.35rem;
            left: 0;
            right: 0;
            height: 2.5px;
            background-color: #4f46e5;
            border-radius: 9999px;
        }

        /* User Profile & Actions */
        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-details {
            text-align: right;
            line-height: 1.25;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1e293b;
            display: block;
        }

        .role-badge {
            font-size: 0.7rem;
            font-weight: 800;
            color: #4f46e5;
            letter-spacing: 0.025em;
            text-transform: uppercase;
        }

        .logout-btn {
            background-color: #ef4444;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .logout-btn:hover {
            background-color: #dc2626;
        }

        /* Main Content Container */
        .main-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem 3.5rem;
        }

        /* Alert notifications */
        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <header class="top-navbar">
        <div class="nav-container">
            
            <!-- Logo & Brand -->
            <a href="{{ route('dashboard') }}" class="brand-section">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
                <div class="brand-title">
                    Hospital<br><span style="font-weight: 500; font-size: 0.95rem; color: #475569;">Management</span>
                </div>
            </a>

            <!-- Central Navigation Links -->
            <nav class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    Expenses
                </a>
                <a href="{{ route('incomes.index') }}" class="nav-link {{ request()->routeIs('incomes.*') ? 'active' : '' }}">
                    Incomes
                </a>
                <a href="{{ route('reports.financial') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    Financial Statement
                </a>

                {{-- Administrative Access Only --}}
                @if(strtolower(auth()->user()->role ?? '') === 'admin')
                    <a href="{{ route('cost-centers.index') }}" class="nav-link {{ request()->routeIs('cost-centers.*') ? 'active' : '' }}">
                        Cost Centers
                    </a>
                    <a href="{{ route('branches.index') }}" class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                        Branches
                    </a>
                    <a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                        Staff
                    </a>
                @endif
            </nav>

            <!-- User Info & Logout Button -->
            <div class="user-section">
                @auth
                    <div class="user-details">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="role-badge">({{ strtoupper(auth()->user()->role ?? 'STAFF') }})</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            Log Out
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </header>

    <!-- Main View Page Body -->
    <main class="main-wrapper">
        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- Error Alert -->
        @if (session('error'))
            <div class="alert alert-danger">
                ⚠ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>