-- ThemeHub Sample Data
-- ============================================

-- Admin User
INSERT INTO users (name, email, password, role, status, email_verified_at) VALUES
('Admin User', 'admin@themehub.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LeZUbk5l5vX5q5Q6i', 'admin', 'active', datetime('now'));

-- Sample Vendor
INSERT INTO users (name, email, password, role, status, email_verified_at) VALUES
('John Developer', 'vendor@themehub.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LeZUbk5l5vX5q5Q6i', 'vendor', 'active', datetime('now'));

-- Sample Customer
INSERT INTO users (name, email, password, role, status, email_verified_at) VALUES
('Jane Customer', 'customer@themehub.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LeZUbk5l5vX5q5Q6i', 'customer', 'active', datetime('now'));

-- Permissions
INSERT INTO permissions (role, permission) VALUES
('admin', '*'),
('vendor', 'themes.create'),
('vendor', 'themes.edit'),
('vendor', 'themes.delete'),
('vendor', 'orders.view'),
('vendor', 'earnings.view'),
('customer', 'themes.view'),
('customer', 'orders.create'),
('customer', 'orders.view'),
('customer', 'wishlist.create'),
('customer', 'wishlist.delete');

-- Categories
INSERT INTO categories (name, slug, description, status) VALUES
('WordPress', 'wordpress', 'WordPress themes and templates', 'active'),
('HTML5', 'html5', 'HTML5 website templates', 'active'),
('eCommerce', 'ecommerce', 'eCommerce templates', 'active'),
('Portfolio', 'portfolio', 'Portfolio and resume themes', 'active'),
('Blog', 'blog', 'Blog and magazine themes', 'active'),
('Corporate', 'corporate', 'Corporate and business themes', 'active'),
('Landing Page', 'landing-page', 'Landing page templates', 'active'),
('Admin Dashboard', 'admin-dashboard', 'Admin and dashboard templates', 'active');

-- Sample Themes
INSERT INTO themes (name, slug, description, price, sale_price, thumbnail, status, featured, trending, category_id, developer_id, compatible_browsers, compatible_php, views, sales, rating, reviews_count) VALUES
('Aurora Pro', 'aurora-pro', 'A modern WordPress theme with stunning animations and premium design.', 79.00, 59.00, 'themes/aurora-pro.jpg', 'published', 1, 1, 1, 2, 'Chrome, Firefox, Safari, Edge', '8.0+', 15420, 892, 4.8, 156),
('Nexus HTML5', 'nexus-html5', 'Professional HTML5 template for modern businesses.', 49.00, NULL, 'themes/nexus-html5.jpg', 'published', 1, 0, 2, 2, 'Chrome, Firefox, Safari, Edge', 'N/A', 8930, 445, 4.6, 89),
('Shopify Plus', 'shopify-plus', 'Complete eCommerce solution with advanced features.', 129.00, 99.00, 'themes/shopify-plus.jpg', 'published', 1, 1, 3, 2, 'Chrome, Firefox, Safari, Edge', 'N/A', 22100, 1567, 4.9, 312),
('Portfolio X', 'portfolio-x', 'Minimalist portfolio theme for creatives.', 39.00, 29.00, 'themes/portfolio-x.jpg', 'published', 0, 1, 4, 2, 'Chrome, Firefox, Safari, Edge', 'N/A', 6540, 234, 4.5, 45),
('Blogger Pro', 'blogger-pro', 'Feature-rich blog theme with SEO optimization.', 59.00, NULL, 'themes/blogger-pro.jpg', 'published', 0, 0, 5, 2, 'Chrome, Firefox, Safari, Edge', 'N/A', 11200, 678, 4.7, 123),
('CorpAdmin', 'corpadmin', 'Corporate admin dashboard with advanced analytics.', 89.00, 69.00, 'themes/corpadmin.jpg', 'published', 1, 1, 8, 2, 'Chrome, Firefox, Safari, Edge', '8.1+', 18700, 1234, 4.8, 267),
('LandingKit', 'landingkit', 'High-converting landing page templates.', 69.00, 49.00, 'themes/landingkit.jpg', 'published', 0, 1, 7, 2, 'Chrome, Firefox, Safari, Edge', 'N/A', 9800, 567, 4.6, 98),
('BizCorp', 'bizcorp', 'Corporate business theme with multiple layouts.', 79.00, NULL, 'themes/bizcorp.jpg', 'published', 0, 0, 6, 2, 'Chrome, Firefox, Safari, Edge', 'N/A', 7650, 345, 4.4, 67);

-- Coupons
INSERT INTO coupons (code, type, value, min_amount, expires_at, status) VALUES
('WELCOME10', 'percent', 10, 50, datetime('now', '+30 days'), 'active'),
('SAVE20', 'percent', 20, 100, datetime('now', '+15 days'), 'active'),
('FLAT50', 'fixed', 50, 200, datetime('now', '+7 days'), 'active');

-- Settings
INSERT INTO settings (key, value, type) VALUES
('site_name', 'ThemeHub', 'string'),
('site_description', 'Premium Theme Marketplace - Discover the best themes and templates', 'string'),
('site_url', 'http://localhost:8000', 'string'),
('admin_email', 'admin@themehub.com', 'string'),
('currency', 'USD', 'string'),
('currency_symbol', '$', 'string'),
('timezone', 'UTC', 'string'),
('seo_title', 'ThemeHub - Premium Theme Marketplace', 'string'),
('seo_description', 'Discover premium themes and templates for your next project', 'string'),
('seo_keywords', 'themes, templates, wordpress, html, marketplace', 'string'),
('mail_from_email', 'noreply@themehub.com', 'string'),
('mail_from_name', 'ThemeHub', 'string'),
('stripe_public_key', '', 'string'),
('stripe_secret_key', '', 'string'),
('paypal_client_id', '', 'string'),
('paypal_secret', '', 'string'),
('maintenance_mode', '0', 'bool'),
('registration_enabled', '1', 'bool'),
('vendor_registration', '1', 'bool'),
('review_moderation', '1', 'bool'),
('commission_rate', '10', 'float'),
('free_downloads_enabled', '0', 'bool');

-- Sample Blog Post
INSERT INTO posts (title, slug, excerpt, content, author_id, status, published_at) VALUES
('Welcome to ThemeHub', 'welcome-to-themehub', 'Discover the best premium themes and templates for your projects.', '<p>Welcome to ThemeHub, the premier marketplace for premium themes and templates...</p>', 1, 'published', datetime('now'));

-- Sample Page
INSERT INTO pages (title, slug, content, status) VALUES
('About Us', 'about', '<p>ThemeHub is a premium theme marketplace...</p>', 'published'),
('Contact', 'contact', '<p>Get in touch with us...</p>', 'published'),
('Privacy Policy', 'privacy-policy', '<p>Your privacy is important to us...</p>', 'published'),
('Terms of Service', 'terms', '<p>Terms and conditions...</p>', 'published');

-- Sample Menu
INSERT INTO menus (name, location, items) VALUES
('Main Navigation', 'header', '[]'),
('Footer Links', 'footer', '[]');

-- Sample Newsletter
INSERT INTO newsletters (email, name, status) VALUES
('newsletter@example.com', 'Newsletter Subscriber', 'subscribed');
