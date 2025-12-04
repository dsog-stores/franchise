    <!-- Enhanced Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="bottom-nav-container">
            <!-- Home Button -->
            <a href="<?php echo SITE_URL; ?>/" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" onclick="setActiveNav(this)">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-label">Home</span>
            </a>
            
            <!-- Collections Button -->
            <a href="#collections" class="nav-item" onclick="setActiveNav(this)">
                <div class="nav-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <span class="nav-label">Collections</span>
            </a>
            
            <!-- Products Button -->
            <a href="#products" class="nav-item" onclick="setActiveNav(this)">
                <div class="nav-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <span class="nav-label">Products</span>
            </a>
            
            <!-- Gifts Button -->
            <a href="#gifts" class="nav-item" onclick="setActiveNav(this)">
                <div class="nav-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <span class="nav-label">Gifts</span>
            </a>
            
            <!-- Contact Button -->
            <a href="https://wa.me/<?php echo getWhatsAppNumber(); ?>" class="nav-item" onclick="setActiveNav(this)" target="_blank">
                <div class="nav-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <span class="nav-label">Contact</span>
            </a>
        </div>
    </nav>

    <!-- WhatsApp Chat Bot with Franchisee Name -->
    <div class="whatsapp-chat-bot" id="whatsappChatBot">
        <button class="chat-bot-toggle" id="chatBotToggle">
            <i class="fab fa-whatsapp"></i>
        </button>
        <div class="chat-bot-container" id="chatBotContainer">
            <div class="chat-bot-header">
                <div class="chat-bot-avatar">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h4 id="franchiseeChatName"><?php echo $currentFranchisee ? $currentFranchisee['name'] : 'DSOG Assistant'; ?></h4>
                    <div class="franchisee-name" id="franchiseeNameDisplay"><?php echo $currentFranchisee ? 'DSOG ' . $currentFranchisee['location'] : 'Your Fashion Expert'; ?></div>
                    <div class="chat-bot-status">Online</div>
                </div>
            </div>
            <div class="chat-bot-messages" id="chatMessages">
                <div class="chat-message bot-message">
                    Hi! I'm your DSOG fashion assistant. How can I help you today?
                </div>
            </div>
            <div class="chat-options" id="chatOptions">
                <button class="chat-option-btn" onclick="handleChatOption('products')">
                    <i class="fas fa-shopping-bag"></i> View Products
                </button>
                <button class="chat-option-btn" onclick="handleChatOption('order')">
                    <i class="fas fa-shopping-cart"></i> Place Order
                </button>
                <button class="chat-option-btn" onclick="handleChatOption('tracking')">
                    <i class="fas fa-truck"></i> Order Tracking
                </button>
                <button class="chat-option-btn" onclick="handleChatOption('contact')">
                    <i class="fas fa-phone"></i> Contact Franchisee
                </button>
            </div>
            <div class="chat-bot-input">
                <input type="text" class="chat-input" id="chatInput" placeholder="Type your message..." onkeypress="handleChatKeypress(event)">
                <button class="chat-send-btn" onclick="sendChatMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Main JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
    
    <!-- Additional JavaScript for specific pages -->
    <?php if (isset($additionalJS)): ?>
    <script><?php echo $additionalJS; ?></script>
    <?php endif; ?>
    
    <!-- Pass PHP data to JavaScript -->
    <script>
        // Global configuration
        const SITE_URL = '<?php echo SITE_URL; ?>';
        const CURRENT_FRANCHISEE = <?php echo $currentFranchisee ? json_encode($currentFranchisee) : 'null'; ?>;
        
        // Pass WhatsApp number to JavaScript
        const WHATSAPP_NUMBER = '<?php echo getWhatsAppNumber(); ?>';
        
        // Pass product data if available
        <?php if (isset($product)): ?>
        const CURRENT_PRODUCT = <?php echo json_encode($product); ?>;
        <?php endif; ?>
    </script>
    
</body>
</html>
