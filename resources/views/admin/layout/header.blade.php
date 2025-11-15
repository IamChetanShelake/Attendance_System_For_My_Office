<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HR Admin Panel - Attendance Employee Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4eb4e6;
            --secondary-color: #4a494b;
            --dark-color: #000000;
            --light-bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --border-radius: 15px;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-collapsed-width);
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5a595b 100%);
            color: white;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .sidebar.expanded {
            width: var(--sidebar-width);
        }

        .sidebar.collapsed:hover {
            width: var(--sidebar-width);
        }

        .sidebar.collapsed:hover .nav-link {
            justify-content: flex-start;
            padding: 1rem 1.5rem;
            margin: 0 0.5rem;
        }

        .sidebar.collapsed:hover .nav-link i {
            margin-right: 12px;
            font-size: 1.3rem;
        }

        .sidebar.collapsed:hover .nav-text {
            opacity: 1;
            transform: translateX(0);
            position: static;
        }

        .sidebar.collapsed:hover .logo-text {
            opacity: 1;
            transform: translateY(0);
        }

        .sidebar.collapsed:hover .logo-container img {
            height: 40px;
            margin-bottom: 0.5rem;
        }

        .sidebar.collapsed:hover .toggle-text {
            opacity: 1;
        }

        .sidebar-header {
            padding: 2rem 1rem 1rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            transform: translateY(-10px);
        }

        .logo-container img {
            height: 40px;
            transition: all 0.3s ease;
            background: white;
            padding: 8px;
            border-radius: 12px;
            margin-bottom: 0.5rem;
        }

        .sidebar.collapsed .logo-container img {
            height: 35px;
            margin-bottom: 0;
        }

        .logo-text {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .sidebar-nav {
            padding: 2rem 0 1rem 0;
            height: calc(100vh - 140px);
            overflow-y: scroll; /* Keep scrolling enabled */
            overflow-x: hidden;

            /* Hide scrollbar - cross-browser */
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }

        .sidebar-nav::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-toggle-btn {
            width: 100%;
            background: rgba(78, 180, 230, 0.1);
            border: 2px solid rgba(78, 180, 230, 0.3);
            color: white;
            padding: 0.75rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(78, 180, 230, 0.2);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .sidebar-toggle-btn i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        .sidebar.expanded .sidebar-toggle-btn i {
            transform: rotate(180deg);
        }

        .toggle-text {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .toggle-text {
            opacity: 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            padding: 1rem 1.5rem;
            margin: 0 0.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 1rem 0.5rem;
            margin: 0 0.25rem;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(78, 180, 230, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            background-color: rgba(78, 180, 230, 0.15);
            color: white !important;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(78, 180, 230, 0.2);
        }

        .sidebar.collapsed .nav-link:hover {
            transform: none;
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a9bc7 100%);
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(78, 180, 230, 0.4);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .nav-link.active::before {
            display: none;
        }

        .nav-link i {
            font-size: 1.3rem;
            margin-right: 12px;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.4rem;
        }

        .nav-text {
            transition: all 0.3s ease;
            white-space: nowrap;
            opacity: 1;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            transform: translateX(-10px);
            position: absolute;
            left: -9999px;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .dashboard-container {
            padding: 0;
        }

        .section-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a9bc7 100%);
            color: white;
            border: none;
            padding: 1.5rem;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 2rem;
        }

        .btn-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a9bc7 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(78, 180, 230, 0.3);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 180, 230, 0.4);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .table thead th {
            background: var(--secondary-color);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(78, 180, 230, 0.05);
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 180, 230, 0.25);
        }

        .rule-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 5px solid var(--primary-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .rule-card:hover {
            transform: translateX(5px);
        }

        .rule-card h5 {
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .rule-card p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card .icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stats-card h3 {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-card p {
            color: #6c757d;
            font-weight: 500;
        }

        .calendar-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--card-shadow);
        }

        .calendar-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5a595b 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px 10px 0 0;
            margin: -2rem -2rem 2rem -2rem;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
                margin-top: 1rem;
            }

            .nav-link {
                margin: 0.25rem 0;
            }

            .card-body {
                padding: 1.5rem;
            }

            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <!-- Sidebar -->
        <div class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <img src="{{ asset('images/TechMET Logo (2).png') }}" alt="TechMET Logo">
                <span class="logo-text">HR Admin Panel</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.employees.index' ? 'active' : '' }}" href="{{ route('admin.employees.index') }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Employees</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with(Route::currentRouteName(), 'admin.employee-logins') ? 'active' : '' }}" href="{{ route('admin.employee-logins.index') }}">
                        <i class="fas fa-user-shield"></i>
                        <span class="nav-text">Employee Login</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.daily-attendance' ? 'active' : '' }}" href="{{ route('admin.daily-attendance') }}">
                        <i class="fas fa-clock"></i>
                        <span class="nav-text">Daily Attendance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.manage-attendance' ? 'active' : '' }}" href="{{ route('admin.manage-attendance') }}">
                        <i class="fas fa-calendar-check"></i>
                        <span class="nav-text">Attendance Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.manual-attendance' ? 'active' : '' }}" href="{{ route('admin.manual-attendance') }}">
                        <i class="fas fa-hand-paper"></i>
                        <span class="nav-text">Manual Attendance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with(Route::currentRouteName(), 'admin.accountability') ? 'active' : '' }}" href="{{ route('admin.accountability.index') }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span class="nav-text">Salary/Accountability</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with(Route::currentRouteName(), 'admin.documents') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span class="nav-text">Documents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with(Route::currentRouteName(), 'admin.office-rules') ? 'active' : '' }}" href="{{ route('admin.office-rules.index') }}">
                        <i class="fas fa-cogs"></i>
                        <span class="nav-text">Office Rules</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with(Route::currentRouteName(), 'admin.employee-leave') ? 'active' : '' }}" href="{{ route('admin.employee-leave.index') }}">
                        <i class="fas fa-calendar-times"></i>
                        <span class="nav-text">Leave Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with(Route::currentRouteName(), 'admin.academic-calendar') ? 'active' : '' }}" href="{{ route('admin.academic-calendar.index') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Academic Calendar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.report-management' ? 'active' : '' }}" href="{{ route('admin.report-management') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.employee-accessories' ? 'active' : '' }}" href="{{ route('admin.employee-accessories') }}">
                        <i class="fas fa-tools"></i>
                        <span class="nav-text">Accessories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.ceo-login' ? 'active' : '' }}" href="{{ route('admin.ceo-login') }}">
                        <i class="fas fa-user-tie"></i>
                        <span class="nav-text">CEO Login</span>
                    </a>
                </li>
                <li class="nav-item" style="padding-bottom: 40px;">
                    <a class="nav-link" href="#" onclick="logout()" >
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <i class="fas fa-chevron-right"></i>
                <span class="toggle-text">Expand</span>
            </button>
        </div>
    </div>
    </header>
