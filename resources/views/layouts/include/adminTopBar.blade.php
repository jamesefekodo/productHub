<div class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <div class="search-box">
            <span><i class="fas fa-magnifying-glass"></i></span>
            <input type="text" placeholder="Search products, orders, customers...">
        </div>
    </div>

    <div class="topbar-right">
        <div class="topbar-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">5</span>
        </div>
        <div class="topbar-icon"><i class="fas fa-comment-dots"></i></div>

        <div class="user-menu" onclick="toggleUserDropdown(event)">
            <div class="user-avatar">AD</div>
            <div class="user-info">
                <div class="user-name">Admin User</div>
                <div class="user-role">Administrator</div>
            </div>
            <span style="font-size: 12px;"><i class="fas fa-angle-down"></i></span>

            <!-- User Dropdown Menu -->
            <div class="user-dropdown" id="userDropdown">
                <a href="#" class="dropdown-item">
                    <span>👤</span>
                    <span>My Profile</span>
                </a>
                <a href="#" class="dropdown-item">
                    <span>⚙️</span>
                    <span>Account Settings</span>
                </a>
                <a href="#" class="dropdown-item">
                    <span>🔔</span>
                    <span>Notifications</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <span>❓</span>
                    <span>Help & Support</span>
                </a>
                <a href="{{ route('logoutRequest') }}" class="dropdown-item" style="color: var(--danger);">
                    <span>🚪</span>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>
