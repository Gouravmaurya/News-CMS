        </div>
        
        <footer class="admin-footer">
            <p>&copy; <?php echo date('Y'); ?> News CMS. All rights reserved.</p>
        </footer>
    </main>
    
    <script src="<?php echo BASE_URL; ?>/assets/js/admin.js"></script>
    <?php if (isset($extra_js)): ?>
        <script src="<?php echo $extra_js; ?>"></script>
    <?php endif; ?>
</body>
</html>
