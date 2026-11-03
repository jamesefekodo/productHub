<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - ProductHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --text: #374151;
            --text-light: #6b7280;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--light);
            color: var(--text);
        }

        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, var(--dark) 0%, #374151 100%);
            color: #fff;
            padding: 10px 0;
            font-size: 13px;
        }

        .top-bar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .top-bar-left, .top-bar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .top-bar a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
        }

        /* Navigation */
        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            padding: 15px 0;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 35px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 10px;
            background: transparent;
            border: none;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: var(--dark);
            border-radius: 3px;
        }

        /* Customer Dashboard Layout */
        .customer-layout {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        /* Sidebar Menu */
        .customer-sidebar {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .sidebar-user {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .sidebar-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 32px;
            font-weight: 700;
            margin: 0 auto 15px;
        }

        .sidebar-user h3 {
            font-size: 18px;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .sidebar-user p {
            font-size: 14px;
            color: var(--text-light);
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu > li {
            margin-bottom: 5px;
        }

        .sidebar-menu a,
        .sidebar-menu-parent {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 15px;
            color: var(--text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            cursor: pointer;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
        }

        .sidebar-menu a:hover,
        .sidebar-menu-parent:hover {
            background: var(--light);
            color: var(--primary);
        }

        .sidebar-menu a.active {
            background: rgba(37,99,235,0.1);
            color: var(--primary);
        }

        .sidebar-menu-parent.active {
            background: rgba(37,99,235,0.05);
            color: var(--primary);
        }

        .sidebar-menu-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu-icon {
            font-size: 20px;
            width: 24px;
        }

        .sidebar-menu-arrow {
            font-size: 14px;
            transition: transform 0.3s;
        }

        .sidebar-menu-arrow.expanded {
            transform: rotate(90deg);
        }

        /* Submenu */
        .sidebar-submenu {
            list-style: none;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding-left: 36px;
            margin-left: 12px;
            border-left: 2px solid var(--border);
            position: relative;
        }

        .sidebar-submenu.expanded {
            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }

        .sidebar-submenu li {
            margin-bottom: 3px;
        }

        .sidebar-submenu a {
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            display: block;
            justify-content: flex-start;
        }

        .sidebar-submenu a.active {
            background: rgba(37,99,235,0.1);
            color: var(--primary);
        }

        .sidebar-submenu a:hover {
            background: var(--light);
            color: var(--primary);
        }

        /* Main Content */
        .customer-content {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            min-height: 600px;
        }

        .content-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border);
        }

        .content-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .content-subtitle {
            color: var(--text-light);
            font-size: 14px;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            color: #fff;
            padding: 25px;
            border-radius: 12px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.green {
            background: linear-gradient(135deg, var(--secondary) 0%, #059669 100%);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        }

        .stat-card.red {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        }

        .stat-card-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card-label {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--light);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
        }

        tbody tr:hover {
            background: var(--light);
        }

        /* Badge */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: rgba(16,185,129,0.1);
            color: var(--secondary);
        }

        .badge-warning {
            background: rgba(245,158,11,0.1);
            color: var(--warning);
        }

        .badge-info {
            background: rgba(59,130,246,0.1);
            color: var(--info);
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }

        .btn-outline:hover {
            background: var(--light);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Order Card */
        .order-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .order-id {
            font-weight: 600;
            color: var(--dark);
            font-size: 16px;
        }

        .order-date {
            color: var(--text-light);
            font-size: 14px;
        }

        .order-items {
            margin-bottom: 15px;
        }

        .order-item {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
        }

        .order-item-image {
            width: 60px;
            height: 60px;
            background: var(--light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .order-item-details {
            flex: 1;
        }

        .order-item-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .order-item-meta {
            font-size: 14px;
            color: var(--text-light);
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid var(--border);
        }

        .order-total {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 998;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .mobile-sidebar-toggle {
            display: none;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .customer-layout {
                grid-template-columns: 1fr;
            }

            .customer-sidebar {
                position: fixed;
                left: -300px;
                top: 0;
                width: 280px;
                height: 100vh;
                z-index: 999;
                transition: left 0.3s;
                overflow-y: auto;
            }

            .customer-sidebar.active {
                left: 0;
            }

            .mobile-sidebar-toggle {
                display: flex;
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 60px;
                height: 60px;
                background: var(--primary);
                color: #fff;
                border-radius: 50%;
                border: none;
                font-size: 24px;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 997;
                align-items: center;
                justify-content: center;
            }

            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .customer-layout {
                margin: 20px auto;
            }

            .customer-content {
                padding: 20px;
            }

            .content-title {
                font-size: 24px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .order-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <span>📧 support@company.com</span>
                <span>📞 +234 800 000 0000</span>
            </div>
            <div class="top-bar-right">
                <a href="#">Help Center</a>
                <a href="#">Track Order</a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <header>
        <nav>
            <div class="nav-container">
                <a href="#" class="logo">
                    <span>🛍️</span>
                    <span>ProductHub</span>
                </a>
                <ul class="nav-links">
                    <li><a href="#products">Products</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="#solutions">Solutions</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <div class="nav-actions">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="user-avatar">JD</div>
                        <span style="font-weight: 600;">John Doe</span>
                    </div>
                    <button class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>

    <!-- Customer Dashboard Layout -->
    <div class="customer-layout">
        <!-- Sidebar -->
        <aside class="customer-sidebar" id="customerSidebar">
            <div class="sidebar-user">
                <div class="sidebar-avatar">JD</div>
                <h3>John Doe</h3>
                <p>john.doe@email.com</p>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="#" class="active" onclick="showSection(event, 'dashboard')">
                        <div class="sidebar-menu-left">
                            <span class="sidebar-menu-icon">📊</span>
                            <span>Dashboard</span>
                        </div>
                    </a>
                </li>

                <!-- Orders Menu with Submenu -->
                <li>
                    <button class="sidebar-menu-parent" onclick="toggleSubmenu(event, 'ordersSubmenu')">
                        <div class="sidebar-menu-left">
                            <span class="sidebar-menu-icon">📦</span>
                            <span>Orders</span>
                        </div>
                        <span class="sidebar-menu-arrow">▶</span>
                    </button>
                    <ul class="sidebar-submenu" id="ordersSubmenu">
                        <li><a href="#" onclick="showSection(event, 'all-orders')">All Orders</a></li>
                        <li><a href="#" onclick="showSection(event, 'pending-orders')">Pending Orders</a></li>
                        <li><a href="#" onclick="showSection(event, 'completed-orders')">Completed Orders</a></li>
                        <li><a href="#" onclick="showSection(event, 'cancelled-orders')">Cancelled Orders</a></li>
                    </ul>
                </li>

                <!-- Quotes Menu with Submenu -->
                <li>
                    <button class="sidebar-menu-parent" onclick="toggleSubmenu(event, 'quotesSubmenu')">
                        <div class="sidebar-menu-left">
                            <span class="sidebar-menu-icon">📄</span>
                            <span>Quote Requests</span>
                        </div>
                        <span class="sidebar-menu-arrow">▶</span>
                    </button>
                    <ul class="sidebar-submenu" id="quotesSubmenu">
                        <li><a href="#" onclick="showSection(event, 'all-quotes')">All Quotes</a></li>
                        <li><a href="#" onclick="showSection(event, 'pending-quotes')">Pending Quotes</a></li>
                        <li><a href="#" onclick="showSection(event, 'approved-quotes')">Approved Quotes</a></li>
                        <li><a href="#" onclick="showSection(event, 'new-quote')">Request New Quote</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" onclick="showSection(event, 'wishlist')">
                        <div class="sidebar-menu-left">
                            <span class="sidebar-menu-icon">❤️</span>
                            <span>Wishlist</span>
                        </div>
                    </a>
                </li>

                <!-- Account Settings with Submenu -->
                <li>
                    <button class="sidebar-menu-parent" onclick="toggleSubmenu(event, 'settingsSubmenu')">
                        <div class="sidebar-menu-left">
                            <span class="sidebar-menu-icon">⚙️</span>
                            <span>Account Settings</span>
                        </div>
                        <span class="sidebar-menu-arrow">▶</span>
                    </button>
                    <ul class="sidebar-submenu" id="settingsSubmenu">
                        <li><a href="#" onclick="showSection(event, 'profile')">Profile Information</a></li>
                        <li><a href="#" onclick="showSection(event, 'addresses')">Addresses</a></li>
                        <li><a href="#" onclick="showSection(event, 'payment-methods')">Payment Methods</a></li>
                        <li><a href="#" onclick="showSection(event, 'notifications')">Notifications</a></li>
                        <li><a href="#" onclick="showSection(event, 'security')">Security</a></li>
                    </ul>
                </li>

                <!-- Support Menu with Submenu -->
                <li>
                    <button class="sidebar-menu-parent" onclick="toggleSubmenu(event, 'supportSubmenu')">
                        <div class="sidebar-menu-left">
                            <span class="sidebar-menu-icon">💬</span>
                            <span>Support</span>
                        </div>
                        <span class="sidebar-menu-arrow">▶</span>
                    </button>
                    <ul class="sidebar-submenu" id="supportSubmenu">
                        <li><a href="#" onclick="showSection(event, 'help-center')">Help Center</a></li>
                        <li><a href="#" onclick="showSection(event, 'contact-support')">Contact Support</a></li>
                        <li><a href="#" onclick="showSection(event, 'faq')">FAQ</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" style="color: var(--danger);">
                        <div class="sidebar-menu-left">
                            <span class="sidebar-menu-icon">🚪</span>
                            <span>Logout</span>
                        </div>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="customer-content">
            <!-- Dashboard Section -->
            <div id="dashboardSection">
                <div class="content-header">
                    <h1 class="content-title">Welcome back, John! 👋</h1>
                    <p class="content-subtitle">Here's an overview of your account activity</p>
                </div>

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-card-icon">📦</div>
                        <div class="stat-card-value">12</div>
                        <div class="stat-card-label">Total Orders</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-card-icon">✓</div>
                        <div class="stat-card-value">8</div>
                        <div class="stat-card-label">Completed</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-card-icon">⏳</div>
                        <div class="stat-card-value">3</div>
                        <div class="stat-card-label">Pending Quotes</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-card-icon">❤️</div>
                        <div class="stat-card-value">15</div>
                        <div class="stat-card-label">Wishlist Items</div>
                    </div>
                </div>

                <h2 style="margin-bottom: 20px; font-size: 20px; color: var(--dark);">Recent Orders</h2>

                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Order #2026-001</div>
                            <div class="order-date">Placed on Jan 10, 2026</div>
                        </div>
                        <span class="badge badge-success">Delivered</span>
                    </div>
                    <div class="order-items">
                        <div class="order-item">
                            <div class="order-item-image">🖥️</div>
                            <div class="order-item-details">
                                <div class="order-item-name">Professional Monitor 27"</div>
                                <div class="order-item-meta">Quantity: 1 | $599.00</div>
                            </div>
                        </div>
                    </div>
                    <div class="order-footer">
                        <div class="order-total">Total: $599.00</div>
                        <div>
                            <button class="btn btn-outline btn-sm">View Details</button>
                            <button class="btn btn-primary btn-sm">Buy Again</button>
                        </div>
                    </div>
                </div>

                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Order #2026-002</div>
                            <div class="order-date">Placed on Jan 12, 2026</div>
                        </div>
                        <span class="badge badge-warning">Processing</span>
                    </div>
                    <div class="order-items">
                        <div class="order-item">
                            <div class="order-item-image">🔧</div>
                            <div class="order-item-details">
                                <div class="order-item-name">Industrial Drill Set</div>
                                <div class="order-item-meta">Quantity: 2 | $598.00</div>
                            </div>
                        </div>
                    </div>
                    <div class="order-footer">
                        <div class="order-total">Total: $598.00</div>
                        <div>
                            <button class="btn btn-outline btn-sm">Track Order</button>
                            <button class="btn btn-outline btn-sm">View Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Orders Section -->
            <div id="all-ordersSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">All Orders</h1>
                    <p class="content-subtitle">View and manage all your orders</p>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Products</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#2026-001</td>
                                <td>Jan 10, 2026</td>
                                <td>Professional Monitor 27"</td>
                                <td>$599.00</td>
                                <td><span class="badge badge-success">Delivered</span></td>
                                <td><button class="btn btn-outline btn-sm">View</button></td>
                            </tr>
                            <tr>
                                <td>#2026-002</td>
                                <td>Jan 12, 2026</td>
                                <td>Industrial Drill Set (x2)</td>
                                <td>$598.00</td>
                                <td><span class="badge badge-warning">Processing</span></td>
                                <td><button class="btn btn-outline btn-sm">Track</button></td>
                            </tr>
                            <tr>
                                <td>#2025-078</td>
                                <td>Dec 28, 2025</td>
                                <td>Wireless Charging Station</td>
                                <td>$89.00</td>
                                <td><span class="badge badge-success">Delivered</span></td>
                                <td><button class="btn btn-outline btn-sm">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Orders Section -->
            <div id="pending-ordersSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Pending Orders</h1>
                    <p class="content-subtitle">Orders currently being processed</p>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Products</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#2026-002</td>
                                <td>Jan 12, 2026</td>
                                <td>Industrial Drill Set (x2)</td>
                                <td>$598.00</td>
                                <td><span class="badge badge-warning">Processing</span></td>
                                <td><button class="btn btn-outline btn-sm">Track</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Completed Orders Section -->
            <div id="completed-ordersSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Completed Orders</h1>
                    <p class="content-subtitle">Successfully delivered orders</p>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Products</th>
                                <th>Total</th>
                                <th>Delivered On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#2026-001</td>
                                <td>Jan 10, 2026</td>
                                <td>Professional Monitor 27"</td>
                                <td>$599.00</td>
                                <td>Jan 15, 2026</td>
                                <td><button class="btn btn-primary btn-sm">Buy Again</button></td>
                            </tr>
                            <tr>
                                <td>#2025-078</td>
                                <td>Dec 28, 2025</td>
                                <td>Wireless Charging Station</td>
                                <td>$89.00</td>
                                <td>Jan 3, 2026</td>
                                <td><button class="btn btn-primary btn-sm">Buy Again</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cancelled Orders Section -->
            <div id="cancelled-ordersSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Cancelled Orders</h1>
                    <p class="content-subtitle">Orders that were cancelled</p>
                </div>

                <p style="text-align: center; color: var(--text-light); padding: 40px 0;">
                    You have no cancelled orders
                </p>
            </div>

            <!-- All Quotes Section -->
            <div id="all-quotesSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">All Quote Requests</h1>
                    <p class="content-subtitle">Track all your product quote requests</p>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#QT-2026-001</td>
                                <td>CNC Router Machine</td>
                                <td>1</td>
                                <td>Jan 14, 2026</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                                <td><button class="btn btn-outline btn-sm">View</button></td>
                            </tr>
                            <tr>
                                <td>#QT-2026-002</td>
                                <td>Industrial Equipment Set</td>
                                <td>5</td>
                                <td>Jan 12, 2026</td>
                                <td><span class="badge badge-success">Approved</span></td>
                                <td><button class="btn btn-primary btn-sm">View Quote</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Quotes Section -->
            <div id="pending-quotesSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Pending Quotes</h1>
                    <p class="content-subtitle">Quotes awaiting approval</p>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#QT-2026-001</td>
                                <td>CNC Router Machine</td>
                                <td>1</td>
                                <td>Jan 14, 2026</td>
                                <td><button class="btn btn-outline btn-sm">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Approved Quotes Section -->
            <div id="approved-quotesSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Approved Quotes</h1>
                    <p class="content-subtitle">Quotes that have been approved</p>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Quoted Price</th>
                                <th>Valid Until</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#QT-2026-002</td>
                                <td>Industrial Equipment Set</td>
                                <td>5</td>
                                <td>$12,450.00</td>
                                <td>Feb 12, 2026</td>
                                <td><button class="btn btn-primary btn-sm">Accept Quote</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Quote Section -->
            <div id="new-quoteSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Request New Quote</h1>
                    <p class="content-subtitle">Submit a new quote request for products</p>
                </div>

                <form>
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" placeholder="Enter product name">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" placeholder="Enter quantity">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select class="form-control">
                                <option>Select category</option>
                                <option>Industrial Equipment</option>
                                <option>Electronics</option>
                                <option>Office Supplies</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Details</label>
                        <textarea class="form-control" rows="4" placeholder="Provide any additional specifications or requirements"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Quote Request</button>
                </form>
            </div>

            <!-- Wishlist Section -->
            <div id="wishlistSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">My Wishlist</h1>
                    <p class="content-subtitle">Products you've saved for later</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                    <div class="order-card">
                        <div class="order-item-image" style="width: 100%; height: 200px; font-size: 64px; margin-bottom: 15px;">💡</div>
                        <div class="order-item-name">Smart LED Lighting System</div>
                        <div class="order-item-meta" style="margin: 10px 0;">$129.00</div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-primary btn-sm" style="flex: 1;">Add to Cart</button>
                            <button class="btn btn-outline btn-sm">Remove</button>
                        </div>
                    </div>
                    <div class="order-card">
                        <div class="order-item-image" style="width: 100%; height: 200px; font-size: 64px; margin-bottom: 15px;">📱</div>
                        <div class="order-item-name">Wireless Charging Dock</div>
                        <div class="order-item-meta" style="margin: 10px 0;">$89.00</div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-primary btn-sm" style="flex: 1;">Add to Cart</button>
                            <button class="btn btn-outline btn-sm">Remove</button>
                        </div>
                    </div>
                    <div class="order-card">
                        <div class="order-item-image" style="width: 100%; height: 200px; font-size: 64px; margin-bottom: 15px;">⚙️</div>
                        <div class="order-item-name">Industrial Safety Kit</div>
                        <div class="order-item-meta" style="margin: 10px 0;">$449.00</div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-primary btn-sm" style="flex: 1;">Add to Cart</button>
                            <button class="btn btn-outline btn-sm">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Section -->
            <div id="profileSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Profile Information</h1>
                    <p class="content-subtitle">Update your personal information</p>
                </div>

                <form>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="John">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="Doe">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="john.doe@email.com">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" value="+234 800 000 0000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company (Optional)</label>
                            <input type="text" class="form-control" placeholder="Your company name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" rows="4" placeholder="Tell us about yourself"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-outline">Cancel</button>
                </form>
            </div>

            <!-- Addresses Section -->
            <div id="addressesSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Saved Addresses</h1>
                    <p class="content-subtitle">Manage your shipping and billing addresses</p>
                </div>

                <button class="btn btn-primary" style="margin-bottom: 20px;">+ Add New Address</button>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    <div class="order-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <h3 style="font-size: 16px; color: var(--dark);">Home</h3>
                            <span class="badge badge-success">Default</span>
                        </div>
                        <p style="color: var(--text); margin-bottom: 5px;">John Doe</p>
                        <p style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                            123 Main Street<br>
                            Victoria Island<br>
                            Lagos, Nigeria<br>
                            +234 800 000 0001
                        </p>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="btn btn-outline btn-sm">Edit</button>
                            <button class="btn btn-outline btn-sm">Delete</button>
                        </div>
                    </div>

                    <div class="order-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <h3 style="font-size: 16px; color: var(--dark);">Office</h3>
                        </div>
                        <p style="color: var(--text); margin-bottom: 5px;">John Doe</p>
                        <p style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                            456 Business Avenue<br>
                            Lekki Phase 1<br>
                            Lagos, Nigeria<br>
                            +234 800 000 0002
                        </p>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="btn btn-outline btn-sm">Edit</button>
                            <button class="btn btn-outline btn-sm">Delete</button>
                            <button class="btn btn-outline btn-sm">Set as Default</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Section -->
            <div id="payment-methodsSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Payment Methods</h1>
                    <p class="content-subtitle">Manage your saved payment methods</p>
                </div>

                <button class="btn btn-primary" style="margin-bottom: 20px;">+ Add New Card</button>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    <div class="order-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div style="font-size: 32px;">💳</div>
                            <span class="badge badge-success">Default</span>
                        </div>
                        <p style="color: var(--text); font-weight: 600; margin-bottom: 10px;">Visa ending in 4242</p>
                        <p style="color: var(--text-light); font-size: 14px;">Expires 12/2027</p>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="btn btn-outline btn-sm">Edit</button>
                            <button class="btn btn-outline btn-sm">Remove</button>
                        </div>
                    </div>

                    <div class="order-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div style="font-size: 32px;">💳</div>
                        </div>
                        <p style="color: var(--text); font-weight: 600; margin-bottom: 10px;">Mastercard ending in 8888</p>
                        <p style="color: var(--text-light); font-size: 14px;">Expires 06/2028</p>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="btn btn-outline btn-sm">Edit</button>
                            <button class="btn btn-outline btn-sm">Remove</button>
                            <button class="btn btn-outline btn-sm">Set as Default</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div id="notificationsSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Notification Preferences</h1>
                    <p class="content-subtitle">Manage how you receive notifications</p>
                </div>

                <div style="background: var(--light); padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 20px;">Email Notifications</h3>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border-radius: 8px; margin-bottom: 10px;">
                        <div>
                            <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">Order Updates</div>
                            <div style="font-size: 14px; color: var(--text-light);">Notifications about your order status</div>
                        </div>
                        <label style="cursor: pointer;">
                            <input type="checkbox" checked style="width: 20px; height: 20px;">
                        </label>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border-radius: 8px; margin-bottom: 10px;">
                        <div>
                            <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">Promotional Emails</div>
                            <div style="font-size: 14px; color: var(--text-light);">Deals, offers, and new products</div>
                        </div>
                        <label style="cursor: pointer;">
                            <input type="checkbox" checked style="width: 20px; height: 20px;">
                        </label>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border-radius: 8px;">
                        <div>
                            <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">Quote Updates</div>
                            <div style="font-size: 14px; color: var(--text-light);">Updates on your quote requests</div>
                        </div>
                        <label style="cursor: pointer;">
                            <input type="checkbox" checked style="width: 20px; height: 20px;">
                        </label>
                    </div>
                </div>

                <div style="background: var(--light); padding: 20px; border-radius: 12px;">
                    <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 20px;">SMS Notifications</h3>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border-radius: 8px; margin-bottom: 10px;">
                        <div>
                            <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">Delivery Alerts</div>
                            <div style="font-size: 14px; color: var(--text-light);">Get SMS when your order is delivered</div>
                        </div>
                        <label style="cursor: pointer;">
                            <input type="checkbox" checked style="width: 20px; height: 20px;">
                        </label>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border-radius: 8px;">
                        <div>
                            <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">Security Alerts</div>
                            <div style="font-size: 14px; color: var(--text-light);">Important account security notifications</div>
                        </div>
                        <label style="cursor: pointer;">
                            <input type="checkbox" checked style="width: 20px; height: 20px;">
                        </label>
                    </div>
                </div>

                <button class="btn btn-primary" style="margin-top: 20px;">Save Preferences</button>
            </div>

            <!-- Security Section -->
            <div id="securitySection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Security Settings</h1>
                    <p class="content-subtitle">Manage your password and security preferences</p>
                </div>

                <div style="background: var(--light); padding: 20px; border-radius: 12px; margin-bottom: 30px;">
                    <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">Change Password</h3>
                    <p style="font-size: 14px; color: var(--text-light); margin-bottom: 20px;">
                        It's a good idea to use a strong password that you don't use elsewhere
                    </p>

                    <form>
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" placeholder="Enter current password">
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" placeholder="Confirm new password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>

                <div style="background: var(--light); padding: 20px; border-radius: 12px;">
                    <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">Two-Factor Authentication</h3>
                    <p style="font-size: 14px; color: var(--text-light); margin-bottom: 20px;">
                        Add an extra layer of security to your account
                    </p>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border-radius: 8px; margin-bottom: 15px;">
                        <div>
                            <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">SMS Authentication</div>
                            <div style="font-size: 14px; color: var(--text-light);">Receive codes via SMS</div>
                        </div>
                        <button class="btn btn-outline btn-sm">Enable</button>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border-radius: 8px;">
                        <div>
                            <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">Authenticator App</div>
                            <div style="font-size: 14px; color: var(--text-light);">Use an authenticator app</div>
                        </div>
                        <button class="btn btn-outline btn-sm">Enable</button>
                    </div>
                </div>
            </div>

            <!-- Help Center Section -->
            <div id="help-centerSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Help Center</h1>
                    <p class="content-subtitle">Find answers to common questions</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    <div class="order-card">
                        <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
                        <h3 style="font-size: 18px; color: var(--dark); margin-bottom: 10px;">Orders & Shipping</h3>
                        <p style="color: var(--text-light); font-size: 14px; margin-bottom: 15px;">
                            Learn about ordering, tracking, and delivery
                        </p>
                        <button class="btn btn-outline btn-sm">View Articles</button>
                    </div>

                    <div class="order-card">
                        <div style="font-size: 48px; margin-bottom: 15px;">💳</div>
                        <h3 style="font-size: 18px; color: var(--dark); margin-bottom: 10px;">Payments & Billing</h3>
                        <p style="color: var(--text-light); font-size: 14px; margin-bottom: 15px;">
                            Payment methods, invoices, and refunds
                        </p>
                        <button class="btn btn-outline btn-sm">View Articles</button>
                    </div>

                    <div class="order-card">
                        <div style="font-size: 48px; margin-bottom: 15px;">🔄</div>
                        <h3 style="font-size: 18px; color: var(--dark); margin-bottom: 10px;">Returns & Exchanges</h3>
                        <p style="color: var(--text-light); font-size: 14px; margin-bottom: 15px;">
                            Return policies and how to exchange items
                        </p>
                        <button class="btn btn-outline btn-sm">View Articles</button>
                    </div>

                    <div class="order-card">
                        <div style="font-size: 48px; margin-bottom: 15px;">👤</div>
                        <h3 style="font-size: 18px; color: var(--dark); margin-bottom: 10px;">Account Settings</h3>
                        <p style="color: var(--text-light); font-size: 14px; margin-bottom: 15px;">
                            Manage your profile and preferences
                        </p>
                        <button class="btn btn-outline btn-sm">View Articles</button>
                    </div>
                </div>
            </div>

            <!-- Contact Support Section -->
            <div id="contact-supportSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Contact Support</h1>
                    <p class="content-subtitle">Get in touch with our support team</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="order-card">
                        <div style="font-size: 40px; margin-bottom: 15px;">📧</div>
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">Email Support</h3>
                        <p style="color: var(--text-light); font-size: 14px;">support@company.com</p>
                        <p style="color: var(--text-light); font-size: 13px; margin-top: 10px;">Response within 24 hours</p>
                    </div>

                    <div class="order-card">
                        <div style="font-size: 40px; margin-bottom: 15px;">📞</div>
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">Phone Support</h3>
                        <p style="color: var(--text-light); font-size: 14px;">+234 800 000 0000</p>
                        <p style="color: var(--text-light); font-size: 13px; margin-top: 10px;">Mon-Fri, 9AM-6PM WAT</p>
                    </div>

                    <div class="order-card">
                        <div style="font-size: 40px; margin-bottom: 15px;">💬</div>
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">Live Chat</h3>
                        <p style="color: var(--text-light); font-size: 14px; margin-bottom: 15px;">Chat with us now</p>
                        <button class="btn btn-primary btn-sm">Start Chat</button>
                    </div>
                </div>

                <div style="background: var(--light); padding: 25px; border-radius: 12px;">
                    <h3 style="font-size: 18px; color: var(--dark); margin-bottom: 20px;">Send us a message</h3>

                    <form>
                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" placeholder="What can we help you with?">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="5" placeholder="Describe your issue or question"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- FAQ Section -->
            <div id="faqSection" style="display: none;">
                <div class="content-header">
                    <h1 class="content-title">Frequently Asked Questions</h1>
                    <p class="content-subtitle">Quick answers to common questions</p>
                </div>

                <div style="max-width: 800px;">
                    <div class="order-card">
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">How do I track my order?</h3>
                        <p style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                            You can track your order by clicking on "Track Order" in the top menu or by visiting the "My Orders" section in your account. Each order has a tracking number that updates in real-time.
                        </p>
                    </div>

                    <div class="order-card">
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">What is your return policy?</h3>
                        <p style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                            We accept returns within 30 days of delivery for most items. Products must be unused and in original packaging. Some items like customized products may not be eligible for return.
                        </p>
                    </div>

                    <div class="order-card">
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">How long does shipping take?</h3>
                        <p style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                            Standard shipping typically takes 3-5 business days within Nigeria. Express shipping is available for 1-2 business day delivery. International orders may take 7-14 business days.
                        </p>
                    </div>

                    <div class="order-card">
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">How do I request a quote?</h3>
                        <p style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                            Navigate to "Quote Requests" in your account menu and click "Request New Quote". Fill in the product details and quantity, and our team will respond within 24-48 hours with a customized quote.
                        </p>
                    </div>

                    <div class="order-card">
                        <h3 style="font-size: 16px; color: var(--dark); margin-bottom: 10px;">Can I change my order after placing it?</h3>
                        <p style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                            Orders can be modified within 2 hours of placement. After this time, the order enters processing and cannot be changed. Please contact support immediately if you need to make changes.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Toggle Button -->
    <button class="mobile-sidebar-toggle" onclick="toggleSidebar()">☰</button>

    <script>
        // Section Navigation
        function showSection(event, sectionName) {
            event.preventDefault();

            // Hide all sections
            const sections = document.querySelectorAll('[id$="Section"]');
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Show selected section
            const targetSection = document.getElementById(sectionName + 'Section');
            if (targetSection) {
                targetSection.style.display = 'block';
            }

            // Update active menu item - remove active from all
            document.querySelectorAll('.sidebar-menu a, .sidebar-submenu a').forEach(link => {
                link.classList.remove('active');
            });

            // Add active to clicked item
            if (event.currentTarget.tagName === 'A') {
                event.currentTarget.classList.add('active');

                // Also mark parent menu as active if clicking submenu item
                const parentButton = event.currentTarget.closest('li').previousElementSibling;
                if (parentButton && parentButton.classList.contains('sidebar-menu-parent')) {
                    parentButton.classList.add('active');
                }
            }

            // Close mobile sidebar after selection
            if (window.innerWidth <= 992) {
                toggleSidebar();
            }

            // Scroll to top of content
            document.querySelector('.customer-content').scrollIntoView({ behavior: 'smooth' });
        }

        // Submenu Toggle
        function toggleSubmenu(event, submenuId) {
            event.preventDefault();

            const submenu = document.getElementById(submenuId);
            const arrow = event.currentTarget.querySelector('.sidebar-menu-arrow');
            const parentButton = event.currentTarget;

            // Toggle submenu
            submenu.classList.toggle('expanded');
            arrow.classList.toggle('expanded');

            // Toggle parent active state
            if (submenu.classList.contains('expanded')) {
                parentButton.classList.add('active');
            } else {
                // Only remove active if no child is active
                const hasActiveChild = submenu.querySelector('a.active');
                if (!hasActiveChild) {
                    parentButton.classList.remove('active');
                }
            }
        }

        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('customerSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Close sidebar when clicking outside
        document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

        // Initialize: Expand menu if it has active child on page load
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.sidebar-submenu a.active');
            if (activeSubmenuItem) {
                const parentSubmenu = activeSubmenuItem.closest('.sidebar-submenu');
                const parentButton = parentSubmenu.previousElementSibling;
                const arrow = parentButton.querySelector('.sidebar-menu-arrow');

                parentSubmenu.classList.add('expanded');
                arrow.classList.add('expanded');
                parentButton.classList.add('active');
            }
        });
    </script>
</body>
</html>
