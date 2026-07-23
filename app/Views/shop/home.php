<section class="launch-hero">
    <div class="orb orb-one"></div>
    <div class="orb orb-two"></div>
    <div class="container position-relative">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="eyebrow">Premium multi-vendor commerce</span>
                <h1 class="display-3 fw-black mt-3">Launch a liquid-glass marketplace that feels ready for scale.</h1>
                <p class="lead text-secondary mt-3">A polished PHP + SQLite marketplace with vendor stores, secure checkout foundations, API endpoints, payments, and dashboards in one cohesive product experience.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="#marketplace" class="btn btn-gradient btn-lg">Explore products</a>
                    <a href="/register" class="btn btn-glass btn-lg">Become a vendor</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card hero-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div><small class="text-secondary">Today's GMV</small><h3>$128.4K</h3></div>
                        <span class="pulse-dot"></span>
                    </div>
                    <div class="mini-chart"><span style="height:42%"></span><span style="height:68%"></span><span style="height:52%"></span><span style="height:88%"></span><span style="height:74%"></span><span style="height:96%"></span></div>
                    <div class="bento-mini mt-4"><div><b>420+</b><small>vendors</small></div><div><b>18K</b><small>orders</small></div><div><b>4.9</b><small>rating</small></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="bento-grid">
        <article class="glass-card bento-wide"><span class="icon">🛍️</span><h2>Storefront, cart, checkout</h2><p>SEO-friendly browsing, product cards, secure forms, and payment-ready checkout flows.</p></article>
        <article class="glass-card"><span class="icon">🏬</span><h3>Vendor engine</h3><p>Vendor profile, dashboard, products, stock, images, earnings, and payouts structure.</p></article>
        <article class="glass-card"><span class="icon">🛡️</span><h3>Secure core</h3><p>PDO, CSRF, hashed passwords, session hardening, validated uploads, and role guards.</p></article>
        <article class="glass-card accent-card"><span class="icon">⚡</span><h3>SQLite first</h3><p>No MySQL dependency. Import the schema into a local SQLite file and run fast.</p></article>
        <article class="glass-card"><span class="icon">🔌</span><h3>Expandable APIs</h3><p>REST endpoints for products, categories, vendors, auth, orders, and payment status.</p></article>
    </div>
</section>

<section class="container pb-5" id="marketplace">
    <div class="section-head glass-card mb-4">
        <div><span class="eyebrow">Live catalogue</span><h2>Featured marketplace drops</h2></div>
        <form class="search-pill"><input name="q" placeholder="Search products"><select name="category"><option value="">All</option><?php foreach($categories as $c): ?><option value="<?=e($c['slug'])?>"><?=e($c['name'])?></option><?php endforeach ?></select><button>Search</button></form>
    </div>
    <div class="row g-4"><?php foreach($products as $p): ?><div class="col-12 col-sm-6 col-lg-3"><div class="product-card glass-card h-100"><div class="product-art"><img src="/uploads/<?=e($p['image']?:'placeholder.webp')?>" alt="<?=e($p['name'])?>"></div><div class="pt-3"><small class="text-secondary"><?=e($p['store_name'])?></small><h3 class="h5 mt-1"><a href="/product/<?=e($p['slug'])?>"><?=e($p['name'])?></a></h3><div class="d-flex justify-content-between align-items-center"><strong>$<?=e($p['sale_price'] ?: $p['price'])?></strong><span class="chip">In stock</span></div></div></div></div><?php endforeach ?></div>
</section>

<section class="container pb-5"><div class="final-cta glass-card text-center"><span class="eyebrow">Ready for launch</span><h2>Customize the marketplace, onboard vendors, and start selling.</h2><a href="/register" class="btn btn-gradient btn-lg mt-3">Create your account</a></div></section>
