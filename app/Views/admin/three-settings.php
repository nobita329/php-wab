<div class="page-header">
  <div>
    <h1 class="page-title">Three.js 3D Engine & Shader Config</h1>
    <p class="page-subtitle">Fine-tune the interactive 3D hero scene, particle counts, lighting intensity, and mobile optimization.</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 2rem;">
  <!-- Settings Form -->
  <form action="/admin/three-settings.php" method="POST">
    <?= csrf_field() ?>

    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Core Geometry & Physics</h3>
      </div>
      <div class="admin-card-body">
        <div class="admin-form-group">
          <label class="admin-form-label" for="three_enabled">3D Engine State</label>
          <select id="three_enabled" name="three_enabled" class="admin-select">
            <option value="1" <?= setting('three_enabled', '1') === '1' ? 'selected' : '' ?>>Enabled (Interactive WebGL with 2D Canvas Fallback)</option>
            <option value="0" <?= setting('three_enabled') === '0' ? 'selected' : '' ?>>Disabled (Pure HTML/CSS Background)</option>
          </select>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="three_geometry">Sculpture Geometry</label>
          <select id="three_geometry" name="three_geometry" class="admin-select">
            <option value="torus_knot" <?= setting('three_geometry') === 'torus_knot' ? 'selected' : '' ?>>Cybernetic Torus Knot (Signature)</option>
            <option value="icosahedron" <?= setting('three_geometry') === 'icosahedron' ? 'selected' : '' ?>>Geometric Icosahedron Core</option>
            <option value="cyber_sphere" <?= setting('three_geometry') === 'cyber_sphere' ? 'selected' : '' ?>>Planetary Cyber Sphere</option>
            <option value="morphing_cube" <?= setting('three_geometry') === 'morphing_cube' ? 'selected' : '' ?>>Tesseract Cube Array</option>
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="admin-form-group">
            <label class="admin-form-label" for="three_rotation_speed_x">Rotation Speed X</label>
            <input type="number" step="0.001" id="three_rotation_speed_x" name="three_rotation_speed_x" class="admin-input" value="<?= e(setting('three_rotation_speed_x', '0.003')) ?>">
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="three_rotation_speed_y">Rotation Speed Y</label>
            <input type="number" step="0.001" id="three_rotation_speed_y" name="three_rotation_speed_y" class="admin-input" value="<?= e(setting('three_rotation_speed_y', '0.005')) ?>">
          </div>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="three_camera_sensitivity">Camera Mouse Parallax Sensitivity</label>
          <input type="number" step="0.01" id="three_camera_sensitivity" name="three_camera_sensitivity" class="admin-input" value="<?= e(setting('three_camera_sensitivity', '0.05')) ?>">
          <div class="admin-form-hint">Controls how responsively the camera and mesh tilt with mouse cursor coordinates.</div>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="three_particle_count">Stardust Particle Count: <span id="particle-val" style="color:var(--admin-primary); font-family:var(--font-mono);"><?= e(setting('three_particle_count', '850')) ?></span></label>
          <input type="range" min="100" max="3000" step="50" id="three_particle_count" name="three_particle_count" value="<?= e(setting('three_particle_count', '850')) ?>" style="width: 100%;" oninput="document.getElementById('particle-val').innerText = this.value;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
          <div class="admin-form-group">
            <label class="admin-form-label" for="three_accent_color_1">Accent Color 1 (Primary Cyan)</label>
            <input type="color" id="three_accent_color_1" name="three_accent_color_1" class="admin-input" value="<?= e(setting('three_accent_color_1', '#00f0ff')) ?>" style="height: 44px; padding: 0.2rem;">
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="three_accent_color_2">Accent Color 2 (Neon Violet)</label>
            <input type="color" id="three_accent_color_2" name="three_accent_color_2" class="admin-input" value="<?= e(setting('three_accent_color_2', '#7928ca')) ?>" style="height: 44px; padding: 0.2rem;">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
          <div class="admin-form-group">
            <label class="admin-form-label" for="three_wireframe">Render Outer Wireframe</label>
            <select id="three_wireframe" name="three_wireframe" class="admin-select">
              <option value="1" <?= setting('three_wireframe', '1') === '1' ? 'selected' : '' ?>>Yes (Holographic Wireframe Grid)</option>
              <option value="0" <?= setting('three_wireframe') === '0' ? 'selected' : '' ?>>No (Solid Only)</option>
            </select>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="three_mobile_perf">Mobile Performance Optimizer</label>
            <select id="three_mobile_perf" name="three_mobile_perf" class="admin-select">
              <option value="1" <?= setting('three_mobile_perf', '1') === '1' ? 'selected' : '' ?>>Enabled (Clamps DPR to 1.0 & reduces particle load on phones)</option>
              <option value="0" <?= setting('three_mobile_perf') === '0' ? 'selected' : '' ?>>Disabled (Maximum Resolution)</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
      <button type="submit" class="btn btn-primary">
        <span>Save 3D Engine Parameters</span>
      </button>
    </div>
  </form>

  <!-- Live 3D Preview Card -->
  <div>
    <div class="admin-card" style="position: sticky; top: 90px;">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Live 3D Viewport Monitor</h3>
        <span class="badge badge-info">WebGL 2.0</span>
      </div>
      <div class="admin-card-body">
        <div id="three-preview-box" class="three-preview-canvas">
          <canvas id="admin-preview-canvas" style="width:100%; height:100%; display:block;"></canvas>
        </div>
        <p style="font-size: 0.85rem; color: var(--admin-muted); margin-top: 1rem;">
          Rotate and interact with the canvas using your mouse. This preview reflects current saved scene attributes.
        </p>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const box = document.getElementById('three-preview-box');
  const canvas = document.getElementById('admin-preview-canvas');
  if (!box || !canvas || typeof THREE === 'undefined') return;

  try {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, box.clientWidth / box.clientHeight, 0.1, 100);
    camera.position.z = 6;

    const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
    renderer.setSize(box.clientWidth, box.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const geom = new THREE.TorusKnotGeometry(1.3, 0.35, 100, 24);
    const wireMat = new THREE.MeshBasicMaterial({ color: 0x00f0ff, wireframe: true, transparent: true, opacity: 0.4 });
    const solidMat = new THREE.MeshStandardMaterial({ color: 0x0a0d18, emissive: 0x7928ca, emissiveIntensity: 0.5, roughness: 0.3 });

    const wire = new THREE.Mesh(geom, wireMat);
    const solid = new THREE.Mesh(geom, solidMat);
    solid.scale.set(0.96, 0.96, 0.96);

    const group = new THREE.Group();
    group.add(wire);
    group.add(solid);
    scene.add(group);

    const light1 = new THREE.PointLight(0x00f0ff, 2, 20);
    light1.position.set(3, 3, 3);
    scene.add(light1);

    const light2 = new THREE.PointLight(0x7928ca, 2, 20);
    light2.position.set(-3, -3, 3);
    scene.add(light2);

    function renderPreview() {
      requestAnimationFrame(renderPreview);
      group.rotation.x += 0.005;
      group.rotation.y += 0.008;
      renderer.render(scene, camera);
    }
    renderPreview();
  } catch(e) {
    console.warn("Admin 3D Preview Error:", e);
  }
});
</script>

