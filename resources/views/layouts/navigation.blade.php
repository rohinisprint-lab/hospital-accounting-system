<nav style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb; position: relative; z-index: 50;">
    <div style="max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; height: 4rem;">
            
            <!-- Left: Logo & Nav Links -->
            <div style="display: flex; align-items: center; gap: 2rem;">
                <a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <svg style="height: 2rem; width: 2rem; color: #4f46e5;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                    <span style="font-weight: 800; font-size: 1.125rem; color: #111827;">Hospital Management</span>
                </a>

                @php $role = Auth::user()->role ?? 'receptionist'; @endphp

                <div style="display: flex; align-items: center; gap: 1.25rem; font-size: 0.875rem; font-weight: 600;">
                    <a href="{{ route('dashboard') }}" style="text-decoration: none; color: {{ request()->routeIs('dashboard') ? '#4f46e5' : '#6b7280' }}; border-bottom: {{ request()->routeIs('dashboard') ? '2px solid #4f46e5' : '2px solid transparent' }}; padding-bottom: 0.25rem;">
                        Dashboard
                    </a>

                    <!-- Accountant or Admin -->
                    @if(in_array($role, ['admin', 'accountant']))
                        <a href="{{ route('expenses.index') }}" style="text-decoration: none; color: {{ request()->routeIs('expenses.*') ? '#4f46e5' : '#6b7280' }}; border-bottom: {{ request()->routeIs('expenses.*') ? '2px solid #4f46e5' : '2px solid transparent' }}; padding-bottom: 0.25rem;">
                            Expenses
                        </a>
                        <a href="{{ route('expense-heads.index') }}" style="text-decoration: none; color: {{ request()->routeIs('expense-heads.*') ? '#4f46e5' : '#6b7280' }}; border-bottom: {{ request()->routeIs('expense-heads.*') ? '2px solid #4f46e5' : '2px solid transparent' }}; padding-bottom: 0.25rem;">
                            Cost Centers
                        </a>
                    @endif

                    <!-- Receptionist or Admin -->
                    @if(in_array($role, ['admin', 'receptionist']))
                        <a href="{{ route('incomes.index') }}" style="text-decoration: none; color: {{ request()->routeIs('incomes.*') ? '#4f46e5' : '#6b7280' }}; border-bottom: {{ request()->routeIs('incomes.*') ? '2px solid #4f46e5' : '2px solid transparent' }}; padding-bottom: 0.25rem;">
                            Incomes
                        </a>
                    @endif

                    <!-- Admin Only -->
                    @if($role === 'admin')
                        <a href="{{ route('reports.financial') }}" style="text-decoration: none; color: {{ request()->routeIs('reports.*') ? '#4f46e5' : '#6b7280' }}; border-bottom: {{ request()->routeIs('reports.*') ? '2px solid #4f46e5' : '2px solid transparent' }}; padding-bottom: 0.25rem;">
                            Financial Statement
                        </a>
                        <a href="{{ route('branches.index') }}" style="text-decoration: none; color: {{ request()->routeIs('branches.*') ? '#4f46e5' : '#6b7280' }}; border-bottom: {{ request()->routeIs('branches.*') ? '2px solid #4f46e5' : '2px solid transparent' }}; padding-bottom: 0.25rem;">
                            Branches
                        </a>
                        <a href="{{ route('users.index') }}" style="text-decoration: none; color: {{ request()->routeIs('users.*') ? '#4f46e5' : '#6b7280' }}; border-bottom: {{ request()->routeIs('users.*') ? '2px solid #4f46e5' : '2px solid transparent' }}; padding-bottom: 0.25rem;">
                            Staff
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right: Staff User & Log Out Button -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right; line-height: 1.2;">
                    <div style="font-weight: 700; font-size: 0.8125rem; color: #111827;">
                        {{ Auth::user()->name ?? 'Staff User' }}
                    </div>
                    <span style="font-size: 0.6875rem; font-weight: 700; color: #4f46e5; text-transform: uppercase;">
                        ({{ Auth::user()->role ?? 'Admin' }})
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background-color: #ef4444; color: #ffffff; border: none; padding: 0.4rem 0.85rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>