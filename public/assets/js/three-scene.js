/**
 * ========================================================
 * CYBERPULSE — THREE.JS 3D HERO SCENE & WEBGL FALLBACK
 * Futuristic Cybernetic Sculpture, Dynamic Lighting,
 * Mouse Parallax, Scroll Reactions & Accessible Fallback
 * ========================================================
 */

(function () {
  'use strict';

  const container = document.getElementById('three-hero-container');
  if (!container) return;

  const loader = document.getElementById('three-loader');

  // Read Configuration from HTML data attributes
  const isEnabled = container.dataset.enabled !== '0';
  const geomType = container.dataset.geometry || 'torus_knot';
  const speedX = parseFloat(container.dataset.speedX || '0.003');
  const speedY = parseFloat(container.dataset.speedY || '0.005');
  const sensitivity = parseFloat(container.dataset.sensitivity || '0.05');
  const particleCount = parseInt(container.dataset.particles || '850', 10);
  const accent1 = container.dataset.accent1 || '#00f0ff';
  const accent2 = container.dataset.accent2 || '#7928ca';
  const bgColor = container.dataset.bg || '#07080d';
  const isWireframe = container.dataset.wireframe !== '0';
  const mobilePerf = container.dataset.mobilePerf === '1';

  // Check prefers-reduced-motion
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // WebGL Availability Check
  function isWebGLAvailable() {
    try {
      const canvas = document.createElement('canvas');
      return !!(window.WebGLRenderingContext && (canvas.getContext('webgl') || canvas.getContext('experimental-webgl')));
    } catch (e) {
      return false;
    }
  }

  // Hide loader with smooth fade-out
  function hideLoader() {
    if (loader) {
      loader.style.opacity = '0';
      setTimeout(() => {
        loader.style.display = 'none';
      }, 600);
    }
  }

  // ----------------------------------------------------
  // 1. WEBGL FALLBACK ENGINE (HTML5 Canvas Constellation)
  // ----------------------------------------------------
  function initFallbackCanvas() {
    const canvas = document.createElement('canvas');
    canvas.id = 'three-canvas';
    container.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    let width = (canvas.width = container.clientWidth);
    let height = (canvas.height = container.clientHeight);

    const pts = [];
    const count = Math.min(particleCount, 120);

    for (let i = 0; i < count; i++) {
      pts.push({
        x: Math.random() * width,
        y: Math.random() * height,
        vx: (Math.random() - 0.5) * 0.8,
        vy: (Math.random() - 0.5) * 0.8,
        radius: Math.random() * 2 + 1,
        color: Math.random() > 0.5 ? accent1 : accent2,
      });
    }

    let mouseX = width / 2;
    let mouseY = height / 2;

    window.addEventListener('mousemove', (e) => {
      const rect = container.getBoundingClientRect();
      mouseX = e.clientX - rect.left;
      mouseY = e.clientY - rect.top;
    });

    window.addEventListener('resize', () => {
      width = canvas.width = container.clientWidth;
      height = canvas.height = container.clientHeight;
    });

    function drawFallback() {
      ctx.fillStyle = bgColor;
      ctx.fillRect(0, 0, width, height);

      // Connect particles
      for (let i = 0; i < pts.length; i++) {
        const p1 = pts[i];
        p1.x += p1.vx;
        p1.y += p1.vy;

        if (p1.x < 0 || p1.x > width) p1.vx *= -1;
        if (p1.y < 0 || p1.y > height) p1.vy *= -1;

        // Draw point
        ctx.beginPath();
        ctx.arc(p1.x, p1.y, p1.radius, 0, Math.PI * 2);
        ctx.fillStyle = p1.color;
        ctx.shadowBlur = 10;
        ctx.shadowColor = p1.color;
        ctx.fill();

        // Connect with nearby points
        for (let j = i + 1; j < pts.length; j++) {
          const p2 = pts[j];
          const dist = Math.hypot(p1.x - p2.x, p1.y - p2.y);
          if (dist < 130) {
            ctx.beginPath();
            ctx.moveTo(p1.x, p1.y);
            ctx.lineTo(p2.x, p2.y);
            ctx.strokeStyle = `rgba(0, 240, 255, ${0.2 * (1 - dist / 130)})`;
            ctx.lineWidth = 1;
            ctx.shadowBlur = 0;
            ctx.stroke();
          }
        }
      }

      if (!prefersReducedMotion) {
        requestAnimationFrame(drawFallback);
      }
    }

    drawFallback();
    hideLoader();
  }

  // If 3D disabled or WebGL unavailable or Three not loaded, run fallback
  if (!isEnabled || !isWebGLAvailable() || typeof THREE === 'undefined') {
    initFallbackCanvas();
    return;
  }

  // ----------------------------------------------------
  // 2. THREE.JS IMMERSIVE 3D SCENE
  // ----------------------------------------------------
  try {
    const scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(bgColor, 0.04);

    const camera = new THREE.PerspectiveCamera(
      45,
      container.clientWidth / container.clientHeight,
      0.1,
      1000
    );
    camera.position.z = 7;

    const renderer = new THREE.WebGLRenderer({
      antialias: true,
      alpha: true,
      powerPreference: 'high-performance',
    });

    const isMobile = window.innerWidth < 768;
    const maxRatio = mobilePerf && isMobile ? 1 : Math.min(window.devicePixelRatio, 2);
    renderer.setPixelRatio(maxRatio);
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setClearColor(0x000000, 0); // Transparent canvas over dark CSS background
    renderer.domElement.id = 'three-canvas';
    container.appendChild(renderer.domElement);

    // Group to hold all 3D objects
    const mainGroup = new THREE.Group();
    scene.add(mainGroup);

    // 1. Core Futuristic Sculpture
    let coreGeometry;
    switch (geomType) {
      case 'icosahedron':
        coreGeometry = new THREE.IcosahedronGeometry(1.6, 2);
        break;
      case 'cyber_sphere':
        coreGeometry = new THREE.SphereGeometry(1.6, 32, 32);
        break;
      case 'morphing_cube':
        coreGeometry = new THREE.BoxGeometry(2.2, 2.2, 2.2, 8, 8, 8);
        break;
      case 'torus_knot':
      default:
        coreGeometry = new THREE.TorusKnotGeometry(1.4, 0.38, 128, 32, 2, 3);
        break;
    }

    // Outer wireframe glowing shell
    const wireMaterial = new THREE.MeshBasicMaterial({
      color: new THREE.Color(accent1),
      wireframe: true,
      transparent: true,
      opacity: 0.35,
    });
    const wireMesh = new THREE.Mesh(coreGeometry, wireMaterial);
    mainGroup.add(wireMesh);

    // Inner glowing solid mesh with physical shading
    const innerMaterial = new THREE.MeshStandardMaterial({
      color: new THREE.Color('#0a0d18'),
      emissive: new THREE.Color(accent2),
      emissiveIntensity: 0.45,
      roughness: 0.2,
      metalness: 0.9,
      wireframe: !isWireframe,
    });
    const innerMesh = new THREE.Mesh(coreGeometry, innerMaterial);
    innerMesh.scale.set(0.97, 0.97, 0.97);
    mainGroup.add(innerMesh);

    // 2. Interactive Orbiting Particles
    const pCount = isMobile ? Math.floor(particleCount * 0.4) : particleCount;
    const particleGeometry = new THREE.BufferGeometry();
    const positions = new Float32Array(pCount * 3);
    const colors = new Float32Array(pCount * 3);

    const c1 = new THREE.Color(accent1);
    const c2 = new THREE.Color(accent2);

    for (let i = 0; i < pCount; i++) {
      // Distribute particles in a spherical cloud around the sculpture
      const r = 2.5 + Math.random() * 4.5;
      const theta = Math.random() * Math.PI * 2;
      const phi = Math.acos(Math.random() * 2 - 1);

      positions[i * 3] = r * Math.sin(phi) * Math.cos(theta);
      positions[i * 3 + 1] = r * Math.sin(phi) * Math.sin(theta);
      positions[i * 3 + 2] = r * Math.cos(phi);

      const mixed = c1.clone().lerp(c2, Math.random());
      colors[i * 3] = mixed.r;
      colors[i * 3 + 1] = mixed.g;
      colors[i * 3 + 2] = mixed.b;
    }

    particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    particleGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    // Particle Material
    const particleMaterial = new THREE.PointsMaterial({
      size: isMobile ? 0.04 : 0.055,
      vertexColors: true,
      transparent: true,
      opacity: 0.8,
      blending: THREE.AdditiveBlending,
    });

    const particles = new THREE.Points(particleGeometry, particleMaterial);
    scene.add(particles);

    // 3. Dynamic Lighting System
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
    scene.add(ambientLight);

    const lightCyan = new THREE.PointLight(new THREE.Color(accent1), 3.5, 50);
    lightCyan.position.set(4, 3, 4);
    scene.add(lightCyan);

    const lightPurple = new THREE.PointLight(new THREE.Color(accent2), 3.5, 50);
    lightPurple.position.set(-4, -3, 3);
    scene.add(lightPurple);

    // 4. Mouse Interactivity & Smooth Damping
    let mouseX = 0;
    let mouseY = 0;
    let targetX = 0;
    let targetY = 0;

    window.addEventListener('mousemove', (e) => {
      mouseX = (e.clientX / window.innerWidth) * 2 - 1;
      mouseY = -(e.clientY / window.innerHeight) * 2 + 1;
    });

    // 5. Scroll Interaction
    let scrollY = 0;
    window.addEventListener('scroll', () => {
      scrollY = window.scrollY;
    });

    // 6. Responsive Window Resizing
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        if (!container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
      }, 100);
    });

    // 7. Animation Loop
    let clock = new THREE.Clock();

    function animate() {
      requestAnimationFrame(animate);

      const elapsedTime = clock.getElapsedTime();

      // Smooth mouse interpolation
      targetX += (mouseX - targetX) * sensitivity;
      targetY += (mouseY - targetY) * sensitivity;

      if (!prefersReducedMotion) {
        // Continuous rotation
        mainGroup.rotation.x += speedX;
        mainGroup.rotation.y += speedY;

        // Mouse tilt
        mainGroup.rotation.x += targetY * 0.02;
        mainGroup.rotation.y += targetX * 0.02;

        // Particle subtle pulse and orbit
        particles.rotation.y = elapsedTime * 0.04;
        particles.rotation.x = elapsedTime * 0.02;

        // Scroll Parallax reaction
        const scrollFactor = scrollY * 0.0015;
        mainGroup.position.y = -scrollFactor;
        camera.position.z = 7 + scrollFactor * 1.5;

        // Dynamic light orbits
        lightCyan.position.x = Math.sin(elapsedTime * 0.7) * 4.5;
        lightCyan.position.y = Math.cos(elapsedTime * 0.5) * 3.5;

        lightPurple.position.x = -Math.sin(elapsedTime * 0.6) * 4.5;
        lightPurple.position.y = -Math.cos(elapsedTime * 0.8) * 3.5;
      }

      renderer.render(scene, camera);
    }

    animate();
    hideLoader();
  } catch (err) {
    console.warn('Three.js initialization failed, falling back to 2D Canvas:', err);
    initFallbackCanvas();
  }
})();

