# CyberPulse — 3D Personal Portfolio & CMS

A high-performance, futuristic 3D portfolio website and administrative content management system built with modern **PHP 8.2+**, **MySQL / MariaDB**, **Three.js**, **GSAP**, and clean vanilla **HTML5/CSS3/JavaScript**.

Designed with cinematic dark aesthetic: deep charcoal backgrounds (`#07080d`), electric cyan (`#00f0ff`), neon violet (`#7928ca`), and glassmorphism.

---

## 🌐 Preview Live

- **Live Demo Preview**: [Preview Live](https://nobita329.github.io/php-wab/)
- **Local Interactive Preview**: Run `php -S 127.0.0.1:8000` and open [http://localhost:8000](http://localhost:8000)
- **Admin Control Deck**: [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)
  - **Email**: `admin@cyberpulse.dev`
  - **Password**: `Admin@Portfolio2026!`

---

## 🌟 Key Features

### 1. Interactive 3D Hero Section
- **Three.js 3D WebGL Canvas**: Features a responsive cybernetic sculpture (Torus Knot, Icosahedron, Cyber Sphere, or Morphing Array) with dynamic vertex glow and outer wireframe shell.
- **Ambient Stardust Particles**: Floating celestial particles responding dynamically to mouse cursor trajectory and velocity.
- **Dynamic Lighting Engine**: Synchronized dual point lights (Cyan & Purple) orbiting the 3D scene.
- **Mouse & Scroll Parallax**: Smooth camera tilt and depth scaling tied to scroll position and cursor movements.
- **Accessible WebGL Fallback**: Automatically falls back to an interactive HTML5 2D Canvas constellation if WebGL is disabled or unavailable.
- **Reduced-Motion Support**: Respects the user's `prefers-reduced-motion` operating system preference.
- **Mobile Performance Optimizer**: Clamps `devicePixelRatio` to 1.0 and scales particle counts on mobile devices to preserve battery and 60 FPS.

### 2. Comprehensive Public Pages
- **Home (`/` or `index.php`)**: Full 3D Hero, editable career metrics, featured case studies, skill meters, experience & education timeline, capabilities breakdown, and interactive contact section.
- **Projects Catalog (`projects.php`)**: Filterable project grid, search bar, technology tags, pagination, and demo/source links.
- **Project Case Study (`project.php?slug=...`)**: Full gallery modal/slider, technical architecture breakdown, metadata sidebar, and live view counter.
- **Contact Form (`contact.php`)**: Real-time AJAX submission, cryptographic CSRF token validation, honeypot bot trap, PDO database storage, and optional email notifications.
- **Legal Compliance**: Complete Privacy Policy (`privacy.php`) and Terms of Service (`terms.php`).
- **User Portal**: Member registration (`register.php`), authentication (`login.php`), and profile management (`profile.php`).

### 3. Executive Admin Command Deck (`/admin/`)
Complete 21-section sidebar dashboard with real PDO database operations:
1. **Dashboard**: Live database counts (Projects, Users, Inquiries, Unread), recent messages, recent projects, and administrative audit trail.
2. **Website Branding**: Site name, navbar logo, favicon upload, SEO description, coordinates, and social handles with live preview.
3. **Hero Settings**: Headline, role subtitle, introduction, primary & secondary CTA labels and links, and alignment.
4. **3D Animation Settings**: Toggle 3D, geometry type selector, rotation speeds, camera parallax sensitivity, particle count slider, and **live 3D preview viewport**.
5. **About Section**: Biographical narrative, career metrics counters, and CV/Resume upload.
6. **Skills Management**: Full CRUD for technical skills, category assignments, and proficiency percentages.
7. **Projects Management**: Full CRUD with thumbnail uploads, multi-image gallery uploads, tags, URLs, featured/published toggles.
8. **Experience Management**: Full CRUD for career trajectory, companies, roles, and achievements.
9. **Education Management**: Full CRUD for university degrees, certifications, and honors.
10. **Services Management**: Full CRUD for service offerings, icons, deliverables checklists, and active toggles.
11. **Contact Messages**: Inbox filter (All, Unread, Replied), message viewer, mark read/replied, and mailto reply.
12. **User Management**: Role-based access control (`admin` vs `user`), password reset, and account suspension.
13. **Navigation Management**: Header and footer link ordering, window target (`_self` vs `_blank`), and active states.
14. **SEO & Meta**: Meta keywords, OpenGraph share card upload, robots.txt editor, and **one-click XML Sitemap generator**.
15. **Media Library**: Upload images/documents, copy direct URL to clipboard, and safe file deletion.
16. **Theme Customization Studio**: Primary/accent color pickers, background shade, glass card radius, font families, and reset to defaults.
17. **Security Guard**: Failed login rate limiting thresholds, lockout durations, session timeout, and registration toggles.
18. **Database & Backup**: Real table telemetry, total row counters, **one-click SQL backup export**, cache clear, and audit log purge.
19. **General Settings**: Server timezone, date display format, and public maintenance mode lock.
20. **Admin Profile**: Update personal name, email, and password.
21. **Sign Out**: Secure session destruction.

---

## 🛠️ Technology Stack
- **Backend**: PHP 8.2+ (PDO, prepared statements, sessions, error handling)
- **Database**: MySQL 8.0+ / MariaDB 10.4+ (and SQLite support for local zero-dependency development)
- **Frontend**: HTML5, Modern CSS3 (Glassmorphism, CSS Custom Properties), JavaScript (ES6+)
- **3D Graphics**: Three.js (r128)
- **Animations**: GSAP 3
- **Web Server**: Apache / LiteSpeed / Nginx (cPanel compatible)
- **Zero Node.js / Docker Dependency**: Runs natively on standard LAMP hosting without permanently running processes.

---

## 🔒 Security Architecture
- 100% PDO prepared statements — zero SQL injection vulnerabilities.
- Cryptographic CSRF tokens on all POST operations.
- Password hashing using `password_hash($pwd, PASSWORD_DEFAULT)` (bcrypt/argon2).
- Session fixation protection with `session_regenerate_id(true)`.
- Rate limiting on login attempts to mitigate brute-force attacks.
- Contact form honeypot spam traps.
- Strict upload validation: MIME inspection with `finfo`, size limit check, extension whitelist, and randomized hash filenames.
- Upload directory `.htaccess` blocking execution of PHP/CGI scripts.
- Root `.htaccess` blocking direct web access to `.env`, `storage/`, `config/`, and `database/`.

