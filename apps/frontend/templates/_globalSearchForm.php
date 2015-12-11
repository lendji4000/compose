<?php if (isset($project_id) && isset($project_ref)): ?>
    <form class="form-search" id="globalSearchForm"
          action ="<?php
          echo url_for2('bugManagementSearch', array(
              'project_id' => $project_id,
              'project_ref' => $project_ref))
          ?>">
        <input type="text" class="input-medium search-query" name="reference">
        <button type="submit" class="btn btn-small"><i class="icon icon-search"></i></button>
    </form>
<?php endif; ?>