
    <footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggleBtn = document.getElementById('sidebarToggle');
            const toggleIcon = toggleBtn.querySelector('i');
            const toggleText = toggleBtn.querySelector('.toggle-text');

            // Toggle between collapsed and expanded states
            if (sidebar.classList.contains('collapsed')) {
                // Expand sidebar
                sidebar.classList.remove('collapsed');
                sidebar.classList.add('expanded');
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                if (toggleText) toggleText.textContent = 'Collapse';
                if (mainContent) {
                    mainContent.style.marginLeft = '280px';
                }
            } else {
                // Collapse sidebar
                sidebar.classList.remove('expanded');
                sidebar.classList.add('collapsed');
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                if (toggleText) toggleText.textContent = 'Expand';
                if (mainContent) {
                    mainContent.style.marginLeft = '70px';
                }
            }
        }

        // Logout functionality
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                alert('Logging out...');
                // In a real application, this would redirect to login page
                // window.location.href = '/login';
            }
        }

        // Initialize sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggleBtn = document.getElementById('sidebarToggle');
            const toggleIcon = toggleBtn.querySelector('i');
            const toggleText = toggleBtn.querySelector('.toggle-text');

            // Set initial collapsed state
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                if (toggleText) toggleText.textContent = 'Expand';
                if (mainContent) {
                    mainContent.style.marginLeft = '70px';
                }
            }

            // Add event listener to sidebar toggle button
            toggleBtn.addEventListener('click', toggleSidebar);

            // Add hover event listeners for content adjustment
            sidebar.addEventListener('mouseenter', function() {
                if (sidebar.classList.contains('collapsed') && mainContent) {
                    mainContent.style.marginLeft = '280px';
                }
            });

            sidebar.addEventListener('mouseleave', function() {
                if (sidebar.classList.contains('collapsed') && mainContent) {
                    mainContent.style.marginLeft = '70px';
                }
            });
        });
    </script>
    </footer>
    
</body>
</html>
<?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/layout/footer.blade.php ENDPATH**/ ?>