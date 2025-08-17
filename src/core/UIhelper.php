<?php
/**
 * Renders the styled page header with a title, description, and icon.
 */
function render_page_header($page_slug)
{
    $page_data = [
        'project-ingest-bulk' => ['title' => 'Project Data Ingest (Bulk)', 'icon' => '<i class="fas fa-upload fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'project-ingest-single' => ['title' => 'Project Data Ingest (Single)', 'icon' => '<i class="fas fa-file-alt fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'ai-narrative-architect' => ['title' => 'AI Narrative Architect (PR)', 'icon' => '<i class="fas fa-magic fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'brand-management' => ['title' => 'Brand Identity Management', 'icon' => '<i class="fas fa-building fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'support-concierge' => ['title' => 'AI Support Concierge', 'icon' => '<i class="fas fa-question-circle fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'feedback' => ['title' => 'Feedback Intelligence', 'icon' => '<i class="fas fa-comment-dots fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'feature-voting' => ['title' => 'Feature Voting & Suggestions', 'icon' => '<i class="fas fa-lightbulb fa-fw"></i>', 'description' => 'Share your ideas and vote on community suggestions.'],
        'join-now' => ['title' => 'Join Now', 'icon' => '<i class="fas fa-pen-fancy fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'knowledge-library' => ['title' => 'Knowledge Library', 'icon' => '<i class="fas fa-book fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'analytics' => ['title' => 'Analytics', 'icon' => '<i class="fas fa-chart-pie fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'service-plan' => ['title' => 'Service Plan', 'icon' => '<i class="fas fa-credit-card fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'status-update' => ['title' => 'Status Update', 'icon' => '<i class="fas fa-tasks fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'book-catchup' => ['title' => 'Book Strategic Catchup', 'icon' => '<i class="fas fa-calendar-alt fa-fw"></i>', 'description' => 'Complete the form below to proceed with your submission.'],
        'settings' => ['title' => 'Settings', 'icon' => '<i class="fas fa-cog fa-fw"></i>', 'description' => 'Manage your account and notification preferences.'],
    ];
    $current_page = $page_data[$page_slug] ?? ['title' => 'Page Not Found', 'description' => '', 'icon' => '<i class="fas fa-exclamation-circle fa-fw"></i>'];
    echo "<header class='page-header-internal'>";
    echo "<div class='page-header-icon'>{$current_page['icon']}</div>";
    echo "<div class='page-header-text'>";
    echo "<h1>" . htmlspecialchars($current_page['title']) . "</h1>";
    echo "<p class='page-description'>" . htmlspecialchars($current_page['description']) . "</p>";
    echo "</div>";
    echo "</header>";
}

/**
 * Renders a form group with a label correctly linked to an input field.
 */
function render_form_group($label, $input_id, $input_html)
{
    echo "<div class='form-group'>";
    if ($label) {
        echo "<label for='" . htmlspecialchars($input_id) . "'>" . htmlspecialchars($label) . "</label>";
    }
    echo $input_html;
}

/**
 * Renders the complete AI Text Assistant component.
 */
function render_ai_description_field($name, $label, $placeholder = '')
{
    // The main textarea needs the correct 'name' attribute for form submission.
    $textarea_html = "<textarea id='" . htmlspecialchars($name) . "' name='" . htmlspecialchars($name) . "' class='form-control' placeholder='" . htmlspecialchars($placeholder) . "' rows='6'></textarea>";

    $html = "
    <div class='ai-text-assistant'>
        <div class='assistant-header'>
            <span class='assistant-icon'>✧</span>
            <span class='assistant-title'>AI Text Assistant</span>
        </div>

        <div class='original-text-container' style='display: none;'>
            <p><strong>Original Text:</strong></p>
            <div class='original-text-content'></div>
            <button type='button' class='btn-revert-text'>Revert to Original</button>
        </div>
        <div class='toggle-original-wrapper'>
            <a href='#' class='toggle-original-link' style='display: none;'>Show Original</a>
        </div>

        <div class='current-text-wrapper'>
            {$textarea_html}
        </div>

        <div class='quick-actions'>
            <button type='button' class='action-button' data-action='professional'>Make it more professional</button>
            <button type='button' class='action-button' data-action='shorter'>Make it shorter</button>
            <button type='button' class='action-button' data-action='expand'>Expand with more details</button>
            <button type='button' class='action-button' data-action='clarity'>Improve clarity</button>
        </div>

        <div class='refine-input-group'>
            <input type='text' id='custom-refine-input' class='form-control' placeholder='Ask me to refine, expand, shorten...'>
            <button type='button' id='send-refine-request'>
                <i class='fas fa-paper-plane'></i>
            </button>
        </div>
    </div>";

    render_form_group($label, $name, $html);
}


/**
 * Renders the complete sidebar navigation.
 */
function render_sidebar($active_page_slug)
{
    $nav_items = [
        ['slug' => 'project-ingest-bulk', 'title' => 'Project Data Ingest (Bulk)', 'icon' => '<i class="fas fa-upload fa-fw"></i>'],
        ['slug' => 'project-ingest-single', 'title' => 'Project Data Ingest (Single)', 'icon' => '<i class="fas fa-file-alt fa-fw"></i>'],
        ['slug' => 'ai-narrative-architect', 'title' => 'AI Narrative Architect', 'icon' => '<i class="fas fa-magic fa-fw"></i>'],
        ['slug' => 'brand-management', 'title' => 'Brand Identity Management', 'icon' => '<i class="fas fa-building fa-fw"></i>'],
        ['slug' => 'support-concierge', 'title' => 'AI Support Concierge', 'icon' => '<i class="fas fa-question-circle fa-fw"></i>'],
        ['slug' => 'feedback', 'title' => 'Feedback Intelligence', 'icon' => '<i class="fas fa-comment-dots fa-fw"></i>'],
        ['slug' => 'feature-voting', 'title' => 'Feature Voting & Suggestions', 'icon' => '<i class="fas fa-lightbulb fa-fw"></i>'],
        ['slug' => 'join-now', 'title' => 'Join Now', 'icon' => '<i class="fas fa-pen-fancy fa-fw"></i>'],
        ['slug' => 'knowledge-library', 'title' => 'Knowledge Library', 'icon' => '<i class="fas fa-book fa-fw"></i>'],
        ['slug' => 'analytics', 'title' => 'Analytics', 'icon' => '<i class="fas fa-chart-pie fa-fw"></i>'],
        ['slug' => 'service-plan', 'title' => 'Service Plan', 'icon' => '<i class="fas fa-credit-card fa-fw"></i>'],
        ['slug' => 'status-update', 'title' => 'Status Update', 'icon' => '<i class="fas fa-tasks fa-fw"></i>'],
        ['slug' => 'book-catchup', 'title' => 'Book Strategic Catchup', 'icon' => '<i class="fas fa-calendar-alt fa-fw"></i>'],
    ];
    echo '<aside class="sidebar">';
    echo '<div class="sidebar-header">';
    echo '<a href="' . APP_URL . '/public/dashboard" class="back-link">← Back to Dashboard</a>';
    echo '<h3 class="portal-nav-title">Portal Navigation</h3>';
    echo '</div>';
    echo '<nav class="sidebar-nav">';
    foreach ($nav_items as $item) {
        $is_active = ($item['slug'] === $active_page_slug) ? 'active' : '';
        $link_url = APP_URL . '/public/' . $item['slug'];
        echo "<a href='{$link_url}' class='nav-item {$is_active}'>";
        echo "<span class='nav-icon'>{$item['icon']}</span><span class='nav-text'>{$item['title']}</span>";
        echo "</a>";
    }
    echo '</nav>';
    echo '<div class="sidebar-footer">';
    $is_active = ('settings' === $active_page_slug) ? 'active' : '';
    echo '<a href="' . APP_URL . '/public/settings" class="nav-item ' . $is_active . '">';
    echo '<span class="nav-icon"><i class="fas fa-cog fa-fw"></i></span><span class="nav-text">Settings</span>';
    echo '</a>';
    echo '<a href="' . APP_URL . '/public/actions/logout.php" class="nav-item">';
    echo '<span class="nav-icon"><i class="fas fa-sign-out-alt fa-fw"></i></span><span class="nav-text">Logout</span>';
    echo '</a>';
    echo '</div>';
    echo '</aside>';
}

/**
 * Renders a complete, self-contained file dropzone component.
 */
function render_file_dropzone($name)
{
    echo "
    <div class='file-dropzone-container'>
        <label for='" . htmlspecialchars($name) . "' class='file-dropzone'>
            <input type='file' id='" . htmlspecialchars($name) . "' name='" . htmlspecialchars($name) . "[]' class='file-input-hidden' multiple>
            <div class='dropzone-prompt'>
                <i class='fas fa-upload'></i>
                <p>Drag & drop files here, or click to browse</p>
                <span>Supports: Images, PDFs, Documents, Spreadsheets (Max 10MB each)</span>
            </div>
        </label>
        <div class='file-list-container'></div>
    </div>
    ";
}