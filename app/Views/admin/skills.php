<div class="page-header">
  <div>
    <h1 class="page-title">Skills & Technical Competencies</h1>
    <p class="page-subtitle">Manage programming languages, graphics pipelines, frameworks, and proficiency percentages.</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 2rem;">
  <!-- Add/Edit Skill Form -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><?= $editSkill ? 'Edit Skill: ' . e($editSkill['name']) : '+ Add New Skill' ?></h3>
        <?php if ($editSkill): ?>
          <a href="/admin/skills.php" class="btn btn-outline btn-sm">Cancel</a>
        <?php endif; ?>
      </div>
      <div class="admin-card-body">
        <form action="/admin/skills.php" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="skill_id" value="<?= $editSkill ? (int)$editSkill['id'] : 0 ?>">

          <div class="admin-form-group">
            <label class="admin-form-label" for="skill_category">Category</label>
            <select id="skill_category" name="category_id" class="admin-select" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($editSkill && $editSkill['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                  <?= e($cat['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="skill_name">Skill Name</label>
            <input type="text" id="skill_name" name="name" class="admin-input" value="<?= e($editSkill['name'] ?? '') ?>" placeholder="e.g. Three.js / WebGL" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="skill_proficiency">Proficiency: <span id="prof-val" style="color:var(--admin-primary); font-family:var(--font-mono);"><?= e($editSkill['proficiency'] ?? '85') ?>%</span></label>
            <input type="range" min="10" max="100" step="1" id="skill_proficiency" name="proficiency" value="<?= e($editSkill['proficiency'] ?? '85') ?>" style="width: 100%;" oninput="document.getElementById('prof-val').innerText = this.value + '%';">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-form-label" for="skill_icon">Icon Key</label>
              <select id="skill_icon" name="icon" class="admin-select">
                <option value="box" <?= ($editSkill && $editSkill['icon'] === 'box') ? 'selected' : '' ?>>box (3D)</option>
                <option value="code" <?= ($editSkill && $editSkill['icon'] === 'code') ? 'selected' : '' ?>>code (Programming)</option>
                <option value="server" <?= ($editSkill && $editSkill['icon'] === 'server') ? 'selected' : '' ?>>server (Backend)</option>
                <option value="database" <?= ($editSkill && $editSkill['icon'] === 'database') ? 'selected' : '' ?>>database (SQL)</option>
                <option value="sparkles" <?= ($editSkill && $editSkill['icon'] === 'sparkles') ? 'selected' : '' ?>>sparkles (Shaders/FX)</option>
                <option value="zap" <?= ($editSkill && $editSkill['icon'] === 'zap') ? 'selected' : '' ?>>zap (Performance)</option>
                <option value="shield" <?= ($editSkill && $editSkill['icon'] === 'shield') ? 'selected' : '' ?>>shield (Security)</option>
                <option value="layout" <?= ($editSkill && $editSkill['icon'] === 'layout') ? 'selected' : '' ?>>layout (Frontend/UI)</option>
                <option value="terminal" <?= ($editSkill && $editSkill['icon'] === 'terminal') ? 'selected' : '' ?>>terminal (DevOps)</option>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-form-label" for="skill_sort">Sort Order</label>
              <input type="number" id="skill_sort" name="sort_order" class="admin-input" value="<?= e($editSkill['sort_order'] ?? '0') ?>">
            </div>
          </div>

          <div class="admin-form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="is_featured" value="1" <?= (!$editSkill || $editSkill['is_featured']) ? 'checked' : '' ?>>
              <span>Highlight on Homepage Preview</span>
            </label>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <span><?= $editSkill ? 'Update Skill' : '+ Add Skill' ?></span>
          </button>
        </form>
      </div>
    </div>

    <!-- Quick Add Category Form -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">+ New Category</h3>
      </div>
      <div class="admin-card-body">
        <form action="/admin/skills.php" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="sub_action" value="create_category">
          <div class="admin-form-group">
            <label class="admin-form-label" for="category_name">Category Name</label>
            <input type="text" id="category_name" name="category_name" class="admin-input" placeholder="e.g. AI & Machine Learning" required>
          </div>
          <button type="submit" class="btn btn-outline btn-sm" style="width: 100%;">Create Category</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Skills List Table -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Configured Skills Database (<?= count($skills) ?>)</h3>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Skill</th>
              <th>Category</th>
              <th>Proficiency</th>
              <th>Featured</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($skills as $s): ?>
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 28px; height: 28px; color: var(--admin-primary);">
                      <?= render_icon($s['icon'] ?? 'code') ?>
                    </div>
                    <strong><?= e($s['name']) ?></strong>
                  </div>
                </td>
                <td><span class="badge badge-info"><?= e($s['category_name']) ?></span></td>
                <td>
                  <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="flex-grow: 1; width: 60px; height: 6px; background: rgba(255,255,255,0.06); border-radius: 4px; overflow: hidden;">
                      <div style="width: <?= (int)$s['proficiency'] ?>%; height: 100%; background: var(--admin-primary);"></div>
                    </div>
                    <span style="font-family: var(--font-mono); font-size: 0.8rem;"><?= (int)$s['proficiency'] ?>%</span>
                  </div>
                </td>
                <td>
                  <?= $s['is_featured'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-warning">No</span>' ?>
                </td>
                <td>
                  <div style="display: flex; gap: 0.5rem;">
                    <a href="/admin/skills.php?action=edit&id=<?= $s['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                    <a href="/admin/skills.php?action=delete&id=<?= $s['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Are you sure you want to delete '<?= e($s['name']) ?>'?">Delete</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

