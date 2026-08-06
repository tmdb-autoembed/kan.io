/* ============================================
   ThemeHub App JavaScript
   GSAP Animations + AlpineJS + Interactions
   ============================================ */

// Initialize GSAP
gsap.registerPlugin(ScrollTrigger);

// Navbar scroll effect
window.addEventListener('scroll', () => {
  const navbar = document.getElementById('navbar');
  if (navbar) {
    navbar.classList.toggle('nav-scrolled', window.scrollY > 50);
  }
});

// Scroll reveal animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      
      // GSAP animation for revealed elements
      gsap.fromTo(entry.target, 
        { opacity: 0, y: 40 },
        { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }
      );
    }
  });
}, observerOptions);

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Magnetic button effect
document.querySelectorAll('.magnetic-btn').forEach(btn => {
  btn.addEventListener('mousemove', (e) => {
    const rect = btn.getBoundingClientRect();
    const x = e.clientX - rect.left - rect.width / 2;
    const y = e.clientY - rect.top - rect.height / 2;
    
    gsap.to(btn, {
      x: x * 0.2,
      y: y * 0.2,
      duration: 0.3,
      ease: 'power2.out'
    });
  });
  
  btn.addEventListener('mouseleave', () => {
    gsap.to(btn, {
      x: 0,
      y: 0,
      duration: 0.5,
      ease: 'elastic.out(1, 0.5)'
    });
  });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    const href = this.getAttribute('href');
    if (href === '#') return;
    
    e.preventDefault();
    const target = document.querySelector(href);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// Theme card hover effects
document.querySelectorAll('.theme-card').forEach(card => {
  const image = card.querySelector('.theme-image');
  const actions = card.querySelector('.quick-actions');
  
  card.addEventListener('mouseenter', () => {
    if (image) {
      gsap.to(image, { scale: 1.05, duration: 0.6, ease: 'power2.out' });
    }
  });
  
  card.addEventListener('mouseleave', () => {
    if (image) {
      gsap.to(image, { scale: 1, duration: 0.6, ease: 'power2.out' });
    }
  });
});

// Parallax effect for orbs
window.addEventListener('scroll', () => {
  const scrolled = window.pageYOffset;
  document.querySelectorAll('.orb').forEach((orb, index) => {
    const speed = 0.1 + (index * 0.05);
    orb.style.transform = `translateY(${scrolled * speed}px)`;
  });
});

// Form input animations
document.querySelectorAll('input, textarea, select').forEach(input => {
  input.addEventListener('focus', () => {
    gsap.to(input, { scale: 1.02, duration: 0.3, ease: 'power2.out' });
  });
  
  input.addEventListener('blur', () => {
    gsap.to(input, { scale: 1, duration: 0.3, ease: 'power2.out' });
  });
});

// Lazy loading for images
if ('IntersectionObserver' in window) {
  const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        if (img.dataset.src) {
          img.src = img.dataset.src;
          img.classList.add('loaded');
        }
        imageObserver.unobserve(img);
      }
    });
  });
  
  document.querySelectorAll('img[data-src]').forEach(img => {
    imageObserver.observe(img);
  });
}

// Search input animation
const searchInput = document.querySelector('.search-input');
if (searchInput) {
  searchInput.addEventListener('focus', () => {
    gsap.to(searchInput, { width: '280px', duration: 0.3, ease: 'power2.out' });
  });
  
  searchInput.addEventListener('blur', () => {
    gsap.to(searchInput, { width: '256px', duration: 0.3, ease: 'power2.out' });
  });
}

// Toast notification animation
function showToast(message, type = 'info') {
  const container = document.getElementById('toast-container');
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  container.appendChild(toast);
  
  gsap.fromTo(toast, 
    { opacity: 0, x: 100 },
    { opacity: 1, x: 0, duration: 0.3, ease: 'power2.out' }
  );
  
  setTimeout(() => {
    gsap.to(toast, {
      opacity: 0,
      x: 100,
      duration: 0.3,
      ease: 'power2.in',
      onComplete: () => toast.remove()
    });
  }, 3000);
}

// Number counter animation
function animateCounter(element, target, duration = 2) {
  gsap.to(element, {
    innerText: target,
    duration: duration,
    ease: 'power2.out',
    snap: { innerText: 1 },
    onUpdate: function() {
      element.innerText = Math.floor(this.targets()[0].innerText).toLocaleString();
    }
  });
}

// Initialize counters
document.querySelectorAll('.stat-value').forEach(counter => {
  const target = parseInt(counter.dataset.target) || 0;
  animateCounter(counter, target);
});

// Theme detail page tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const tab = this.dataset.tab;
    
    document.querySelectorAll('.tab-btn').forEach(b => {
      b.classList.remove('text-indigo-400', 'border-b-2', 'border-indigo-400');
      b.classList.add('text-gray-400');
    });
    
    this.classList.remove('text-gray-400');
    this.classList.add('text-indigo-400', 'border-b-2', 'border-indigo-400');
    
    document.querySelectorAll('.tab-content').forEach(content => {
      content.classList.add('hidden');
    });
    
    const targetContent = document.getElementById('tab-' + tab);
    if (targetContent) {
      targetContent.classList.remove('hidden');
      gsap.fromTo(targetContent, 
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' }
      );
    }
  });
});

// Loading skeleton animation
function showSkeleton() {
  document.querySelectorAll('.skeleton').forEach(skeleton => {
    skeleton.style.animation = 'skeleton-loading 1.5s infinite';
  });
}

function hideSkeleton() {
  document.querySelectorAll('.skeleton').forEach(skeleton => {
    skeleton.style.animation = 'none';
  });
}

// Image lazy loading with blur effect
function lazyLoadImage(img) {
  const src = img.dataset.src;
  if (!src) return;
  
  img.style.filter = 'blur(10px)';
  
  const tempImg = new Image();
  tempImg.onload = () => {
    img.src = src;
    img.style.filter = 'blur(0)';
    img.style.transition = 'filter 0.5s ease';
  };
  tempImg.src = src;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
  // Animate hero section
  gsap.fromTo('.hero-title', 
    { opacity: 0, y: 50 },
    { opacity: 1, y: 0, duration: 1, ease: 'power3.out', delay: 0.2 }
  );
  
  gsap.fromTo('.hero-subtitle',
    { opacity: 0, y: 30 },
    { opacity: 1, y: 0, duration: 1, ease: 'power3.out', delay: 0.4 }
  );
  
  // Animate stats on scroll
  gsap.utils.toArray('.stat-card').forEach((card, i) => {
    gsap.fromTo(card,
      { opacity: 0, y: 30 },
      {
        opacity: 1,
        y: 0,
        duration: 0.6,
        delay: i * 0.1,
        scrollTrigger: {
          trigger: card,
          start: 'top 80%',
          toggleActions: 'play none none reverse'
        }
      }
    );
  });
});

// Console branding
console.log('%c ThemeHub %c Premium Theme Marketplace ',
  'background: linear-gradient(135deg, #6366f1, #ec4899); color: white; padding: 10px 20px; font-size: 20px; font-weight: bold; border-radius: 8px 0 0 8px;',
  'background: #13131f; color: #9ca3af; padding: 10px 20px; font-size: 14px; border-radius: 0 8px 8px 0;'
);
